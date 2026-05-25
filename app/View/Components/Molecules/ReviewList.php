<?php

namespace App\View\Components\Molecules;

use Illuminate\View\Component;

class ReviewList extends Component
{
    public $reviews;
    public $product;

    public function __construct($reviews, $product)
    {
        $this->reviews = $reviews;
        $this->product = $product;
    }

    public function render()
    {
        return view('client.components.molecules.review-list');
    }
}
