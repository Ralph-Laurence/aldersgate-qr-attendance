<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FlatSelect extends Component
{
    /**
     * The select button text.
     *
     * @var string
     */
    public $label;
    public $withCaption;

    /**
     * The select options.
     *
     * @var string
     */
    public $items;

    public $text;

    /**
     * The element's name and id.
     *
     * @var string
     */
    public $as;

    /**
     * Create the component instance.
     *
     * @param  string  $label
     * @param  array  $items
     * @return void
     */
    public function __construct($label, $items, $as, $text = null, $withCaption = null)
    { 
        $this->items = $items;
        $this->label = $label;
        $this->as    = $as;

        if (is_null($text))
            $this->text = 'Select';
        else
            $this->text = $text;

        $this->withCaption = $withCaption;
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
