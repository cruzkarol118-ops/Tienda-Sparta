<?php

namespace App\Http\Controllers\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Carousel;
use App\Models\ContactForm;
use App\Models\Visit;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use Validator;
use Str;


class ClientController extends Controller
{
    public function index()
    {
        if(!Shop::exists()){
            return redirect()->route('register');
        }
    
        $data = [
            'shop' => Shop::first(),
            'dataCarousel' => Carousel::all(),
            'product' => Product::with(['skus', 'category', 'productImage'])
                            ->featured(10)
                            ->get(),
            'category' => Category::orderByDesc('id')->take(4)->get(),
            'reviews' => Review::with(['customer', 'product.productImage'])
                            ->approved()
                            ->latest()
                            ->take(10)
                            ->get(),
            'title' => 'Home'
        ];

   
        return view('client.index', $data);
    }
    public function products(){

        $products = Product::with(['productImage', 'category'])
        ->featured(10)
        ->paginate(10);

        $data = [
            'shop' => Shop::first(),
            'product' =>  $products,
            'category' => Category::all()->sortByDesc('id'),
            'title' => 'Products'
        ];

        

        return view('client.products', $data);
    }

    public function searchProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'product' => 'required'
        ]);

        if($validator->fails()){
            return redirect()->route('clientHome')->withErrors($validator)->withInput();
        }else{
            
            $search = str_replace(' ', '-', strtolower($request->product));

            $data = [
                'title' => 'Result',
                'shop' => Shop::first(),
                'product' => Product::where('title', 'LIKE', '%'.$search.'%')->orderBy('id', 'DESC')->paginate(20),
                'search' => $request->product
            ];

            return view('client.productSearch', $data);

        }
    }

    public function category(){
        $data = [
            'shop' => Shop::first(),
            'category' => Category::orderBy('id', 'DESC')->paginate(12),
            'title' => 'Products'
        ];

        return view('client.category', $data);
    }

    public function categoryProducts($category){
        $data = [
            'shop' => Shop::first(),
            'category' => Category::with('products')->where('slug', $category)->first(),
            'title' => 'Category - '.str_replace('-', ' ', ucwords($category))
        ];

        return view('client.categoryProducts', $data);
    }
    


    public function productDetail($product)
    {
        $product = Product::with([
                    'category.products', // Carga la categoría y sus productos
                    'productImage',
                    'skus.variantOptions.variant',
                    'variants.options'
                  ])
                  ->where('slug', $product)
                  ->firstOrFail();
    
        // Obtener productos recomendados
        $recommendationProducts = $product->category->products()
                                ->where('id', '!=', $product->id)
                                ->with(['productImage'])
                                ->take(8)
                                ->get();
    
        // Si no hay suficientes, completar con productos aleatorios
        if($recommendationProducts->count() < 4) {
            $additionalProducts = Product::where('category_id', '!=', $product->category_id)
                                   ->with(['productImage'])
                                   ->inRandomOrder()
                                   ->take(8 - $recommendationProducts->count())
                                   ->get();
            
            $recommendationProducts = $recommendationProducts->merge($additionalProducts);
        }
    
        $reviews = Review::with('customer')
            ->where('product_id', $product->id)
            ->approved()
            ->latest()
            ->get();

        $data = [
            'shop' => Shop::first(),
            'product' => $product,
            'recomendationProducts' => $recommendationProducts,
            'reviews' => $reviews,
            'title' => str_replace('-', ' ', ucwords($product->title))
        ];

        return view('client.productDetail', $data);
    }


    public function checkout(){
        $data = [
            'shop' => Shop::first(),
            'title' => 'Checkout'
        ];

        return view('client.checkout', $data);
    }

    public function profile(){
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login');
        }
        $data = [
            'shop' => Shop::first(),
            'customer' => $customer,
            'title' => 'Mi Perfil'
        ];
        return view('customer.profile', $data);
    }

    public function profileUpdate(Request $request){
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'current_password' => 'nullable|string|min:6',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.'])->withInput();
            }
            if ($request->filled('new_password')) {
                $customer->password = Hash::make($request->new_password);
            }
        }

        $customer->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function checkoutSave(Request $request) {
        $validator = Validator($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required'
        ]);
    
        if($validator->fails()) {
            return redirect()->route('clientCheckout')->withErrors($validator)->withInput();
        }

        // Verificar carrito vacío
        if (!session('cart') || count((array) session('cart')) === 0) {
            return redirect()->route('clientCheckout')
                   ->with('error', 'Tu carrito está vacío. Agrega productos antes de ordenar.');
        }

        // Si no está autenticado, intentar login o crear cuenta
        if (!Auth::guard('customer')->check()) {
            if ($request->filled('checkout_password')) {
                if (Auth::guard('customer')->attempt([
                    'email' => $request->email,
                    'password' => $request->checkout_password
                ])) {
                    $request->session()->regenerate();
                } else {
                    // Si el email existe pero la contraseña no coincide
                    $existingCustomer = Customer::where('email', $request->email)->first();
                    if ($existingCustomer) {
                        return redirect()->route('clientCheckout')
                               ->withErrors(['checkout_password' => 'Correo ya registrado. Verifica tu contraseña.'])
                               ->withInput();
                    }
                    // Crear cuenta nueva
                    $customer = Customer::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->checkout_password),
                        'phone' => $request->phone,
                        'address' => $request->address,
                    ]);
                    Auth::guard('customer')->login($customer);
                    $request->session()->regenerate();
                }
            }
        }
    
        // Verificar stock antes de procesar
        foreach((array) session('cart') as $id => $details) {
          
            $product = Product::with([
                'skus.variantOptions.variant',
              ])
             // ->where('slug', $product)
              ->where('id', $details['product_id'])
              ->firstOrFail();

            
           
            if(!$product) {
                return redirect()->route('clientCheckout')
                       ->with('error', 'El producto '.$details['title'].' ya no está disponible');
            }
            if($details['item_id']==0 && !$product->has_variants){
                if($product->base_stock < $details['quantity']) {
                    return redirect()->route('clientCheckout')
                           ->with('error', 'No hay suficiente stock de '.$details['title'].' (Disponibles: '.$product->base_stock.')');
                }
            }else{
                $sku = $product->skus->firstWhere('id', $details['item_id']); // si el SKU ID está en $details
                if (!$sku || $sku->stock < $details['quantity']) {
                    return redirect()->route('clientCheckout')
                        ->with('error', 'No hay suficiente stock de '.$details['title'].' (Disponibles: '.($sku ? $sku->stock : 0).')');
                }
            }
    
         
        }
        
        // Procesar compra con transacción
        DB::beginTransaction();
        try {
            $order_code = Str::random(3).'-'.Date('Ymd');
            $total = 0;
            $data = [];
    
            foreach((array) session('cart') as $id => $details) {

                $product = Product::with([
                    'skus.variantOptions.variant',
                  ])
                 // ->where('slug', $product)
                  ->where('id', $details['product_id'])
                  ->firstOrFail();
    
               
                if($details['item_id']==0 && !$product->has_variants){
                    $product->decrement('base_stock', $details['quantity']);
                }else{
                    $sku = $product->skus->firstWhere('id', $details['item_id']);
                    $sku->decrement('stock', $details['quantity']);
                }
             
                $total += $details['price'] * $details['quantity'];
           
                $data[] = [
                    'order_code' => $order_code,
                    'title' => $details['title'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                    'product_sku_id' => $details['item_id'] > 0 ? $details['item_id'] : null,
                    'product_id' => $details['product_id']
                ];
            }
    
            Order::create([
                'shop_id' => Shop::first()->id,
                'order_code' => $order_code,
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'note' => $request->note,
                'email' => $request->email,
                'total' => $total,
                'status' => 0,
                'customer_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null
            ]);
    
            OrderDetail::insert($data);
            session()->forget('cart');
            DB::commit();
    
            return redirect()->route('clientOrderCode', $order_code);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en checkout: '.$e->getMessage());
            return redirect()->route('clientCheckout')
                   ->with('error', 'Ocurrió un error al procesar tu pedido. Por favor intenta nuevamente.');
        }
    }

    public function successOrder($order_code){
        // Obtener la orden principal
        $order = Order::where('order_code', $order_code)->first();
        // Obtener los detalles manualmente (sin relación Eloquent)
        $orderDetails = OrderDetail::where('order_code', $order_code)->get();
      
        $data = [
            'shop' => Shop::first(),
            'order_code' => $order_code,
            'order' => $order,
            'order_details' => $orderDetails,
            'title' => 'Checkout'
        ];

        return view('client.success-order', $data);
    }
    

    public function checkOrder(){
        $data = [
            'shop' => Shop::first(),
            'title' => 'Consultar Orden'
        ];

        return view('client.check-order', $data);
    }

    public function checkOrderStatus(Request $request)
    {
        $shop = Shop::first(); // Mejor obtener esto una sola vez
        $data = [
            'shop' => $shop,
            'title' => 'Consultar Orden'
        ];
    
        if ($request->order_code) {
            $order = Order::with([
                'details.sku.variantOptions.variant', // Cargar relación de variantes
                'details.product' // Cargar relación del producto base
            ])->where('order_code', $request->order_code)->first();

            if ($order) {
                // Transformar los detalles para mostrar mejor la información
                $orderDetails = $order->details->map(function($detail) {
                  
                    $item = [
                        'product_name' => $detail->title,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'total' => $detail->price * $detail->quantity
                    ];
    
                    // Si tiene SKU (producto con variantes)
                    if ($detail->sku) {
                        $item['variants'] = $detail->sku->variantOptions->map(function($option) {
                            return $option->variant->name . ': ' . $option->value;
                        })->implode(', ');
                    }
    
                    return $item;
                });
    
                $data['order'] = $order;
                $data['orderDetail'] = $orderDetails;
                $data['orderTotal'] = $orderDetails->sum('total');
               
            } else {
                $data['error'] = 'No se encontró una orden con ese código';
            }
        }
    
        return view('client.check-order', $data);
    }

    public function about(){
        $data = [
            'shop' => Shop::first(),
            'title' => 'About'
        ];

        return view('client.about', $data);
    }

    public function contact(){
        $data = [
            'shop' => Shop::first(),
            'title' => 'Contacto'
        ];

        return view('client.contact', $data);
    }


    public function myOrders()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }

        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)
                    ->with('details')
                    ->orderByDesc('id')
                    ->get();

        $data = [
            'shop' => Shop::first(),
            'orders' => $orders,
            'title' => 'Mis Órdenes'
        ];

        return view('client.my-orders', $data);
    }

    public function contactForm(Request $request){

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:100',
            'subject' => 'nullable|string',
        ]);
    
        ContactForm::create($request->all());
    
        $data = [
            'shop' => Shop::first(),
            'title' => 'Contacto'
        ];

        return redirect()->route('clientHome')->with('success', 'Gracias por tu mensaje. Te responderemos pronto');
    }


}
