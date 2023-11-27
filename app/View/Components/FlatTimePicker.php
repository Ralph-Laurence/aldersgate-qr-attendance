<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FlatTimePicker extends Component
{
    public $as;
    public $limit;
    public $fill;
    public $gravity;
    public $clamp;

    private $carbon;
    public  $initialHrs;
    public  $initialMins;
    public  $initialAmPm;
    public  $timePeriod;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($as, $limit = null, $fill = null, $gravity = null, $clamp = null)
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



        // $timeString = "12:00 PM";
        // $time = \Carbon\Carbon::parse($timeString);
        // echo $time->format('H:i:s a');
        $this->carbon         = Carbon::now();
        $this->initialHrs     = $this->carbon->format('h');
        $this->initialMins    = $this->carbon->format('i');
        $this->initialAmPm    = $this->carbon->format('a');
        //$elementId      = $attributes->has('as') ? $attributes->get('as') : 'flat-time-picker-' . Str::random(4);

        $this->timePeriod = $this->getCurrentTimePeriod();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.flat-time-picker');
    }

    private function getCurrentTimePeriod()
    {
        $hour = $this->carbon->format('H');

        if ($hour >= 5 && $hour < 12)
            return "morning";

        else if ($hour >= 12 && $hour < 17)
            return "noon";

        else
            return "night";
    }
}
