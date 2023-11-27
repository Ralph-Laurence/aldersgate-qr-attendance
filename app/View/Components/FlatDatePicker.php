<?php

namespace App\View\Components;

use App\View\Components\Base\FlatInputBase;
use Carbon\Carbon;

class FlatDatePicker extends FlatInputBase
{
    protected function onCreate()
    {
        // $this->defaultValue = Carbon::now()->format('M. d, Y');
        $layoutView = view('components.flat-date-picker');
        
        $this->setView($layoutView);

        parent::onCreate();
    }
}
