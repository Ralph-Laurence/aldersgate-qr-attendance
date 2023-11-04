<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FlatRecordsNavItem extends Component
{ 
    public $text;
    public $current;
    public $to;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($text = null, $current = false, $to = null)
    {
        $this->text    = $text;
        $this->current = $current ? 'active' : '';
        $this->to      = $to;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.flat-records-nav-item');
    }
}
