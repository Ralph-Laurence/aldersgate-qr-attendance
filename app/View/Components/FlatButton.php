<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FlatButton extends Component
{
    public $text;       // button text
    public $click;      // onclick
    public $alias;      // id      | name
    public $theme;      // primary | danger | warn | default (none) 
    public $action;     // button  | submit | reset
    public $icon;       // fas icon (i.e fa-plus)
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($as, $text = null, $theme = null, $icon = null, $click = null,  $action = null)
    {
        $this->text   = !empty($text)   ? $text   : 'Button';
        $this->theme  = !empty($theme)  ? $theme  : 'default';
        $this->action = !empty($action) ? $action : 'button';
        
        $this->icon   = $icon;
        $this->click  = $click;
        $this->alias  = $as;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.flat-button');
    }
}
