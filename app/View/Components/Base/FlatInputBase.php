<?php

namespace App\View\Components\Base;

use Illuminate\View\Component;

class FlatInputBase extends Component
{
    public $as;
    public $limit;
    public $fill;
    public $gravity;
    public $clamp;
    public $defaultValue;
    public $initial;

    private $view;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($as, $limit = null, $fill = null, $gravity = null, $clamp = null, $initial = null)
    {
        $this->as    = $as;                                 // Name, Id, Class
        $this->limit = !is_null($limit) ? $limit : 32;      // Maxlength for text field
        $this->fill  = !is_null($fill)  ? $fill  : '';      // Placeholder text / label
        $this->clamp = !is_null($clamp) ? $clamp : '';      // Limit input data to specific type

        // Text alignment inside the input text
        if (!is_null($gravity))
        {
            switch ($gravity) 
            {
                case 'start':
                    $this->gravity = 'text-start';
                    break;

                case 'center':
                    $this->gravity = 'text-center';
                    break;

                case 'end':
                    $this->gravity = 'text-end';
                    break;
            }
        }

        $this->defaultValue = (!is_null($initial)) ? $initial : old($as);
        
        $this->onCreate();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return $this->view; //view('components.flat-time-picker');
    }

    /* Derived class will call this method
     * which will be used for initialization
     */
    protected function onCreate() {}

    public function setView($view) 
    {
        $this->view = $view;
    }
}
