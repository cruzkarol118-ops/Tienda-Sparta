<?php

namespace App\View\Components\Molecules;

use Illuminate\View\Component;

class ProductCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

     public $image, $category, $title, $price, $slug;

    public function __construct($image, $category, $title, $price, $slug = null)
    {
        $this->image = $image;
        $this->category = $category;
        $this->title = $title;
        $this->price = $price;
        $this->slug = $slug;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('client.components.molecules.product-card');
    }
}
