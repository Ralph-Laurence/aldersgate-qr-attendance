<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FlatSelect extends Component
{ 
    public $items;          // Array of items
    public $useCaption;     // Should we use caption labels?
    public $caption;        // The label above
    public $text;           // The button text
    public $as;             // The element's name and id.

    /**
     * Create the component instance. 
     */
    public function __construct($as, $items = null, $text = null, $useCaption = null, $caption = null)
    {  
        $this->as = $as;

        if (is_null($items) || empty($items))
            $this->items = array();
        else
            $this->items = $items;

        if (is_null($text) || empty($text))
            $this->text = 'Select';
        else
            $this->text = $text;
 
        $this->useCaption = $useCaption;
        $this->caption = (!empty($caption)) ? $caption : '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.flat-select');
    }
}
