<?php

namespace App\View\Components\Organisms;

use Illuminate\View\Component;

class Reviews extends Component
{
    public $reviews;

    public function __construct($reviews = [])
    {
        $this->reviews = $reviews;
    }

    public function render()
    {
        return view('client.components.organisms.reviews');
    }
}
