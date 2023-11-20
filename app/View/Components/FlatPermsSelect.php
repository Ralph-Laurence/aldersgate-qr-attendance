<?php

namespace App\View\Components;

use App\Models\Security\UserAccountControl as UAC;
use Illuminate\View\Component;

class FlatPermsSelect extends Component
{   
    public $stretchWidth;
    public $as;
    public $caption;
    public $level;
    public $controls = [];
    public $initialValue;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($as, $caption = null, $level = null, $stretchWidth = null)
    {
        $uacFull   = UAC::permToString(UAC::PERM_FULL_CONTROL);
        $uacModify = UAC::permToString(UAC::PERM_MODIFY);
        $uacWrite  = UAC::permToString(UAC::PERM_WRITE);
        $uacRead   = UAC::permToString(UAC::PERM_READ);
        $uacDenied = UAC::permToString(UAC::PERM_DENIED);

        if (!is_null($stretchWidth))
            $this->$stretchWidth = 'w-100';

        $this->as = $as;
        $this->caption = $caption;

        $this->initialValue = old($this->as);
        $this->level = $level;

        $this->controls = array
        (
            $uacFull   => [ 'disable' => '', 'style' => 'success', 'value' => $uacFull  , 'name' => "option-$as", 'id' => "option-$as-full"   ],
            $uacModify => [ 'disable' => '', 'style' => 'indigo' , 'value' => $uacModify, 'name' => "option-$as", 'id' => "option-$as-modify" ],
            $uacWrite  => [ 'disable' => '', 'style' => 'warning', 'value' => $uacWrite , 'name' => "option-$as", 'id' => "option-$as-write"  ],
            $uacRead   => [ 'disable' => '', 'style' => 'primary', 'value' => $uacRead  , 'name' => "option-$as", 'id' => "option-$as-read"   ],
            $uacDenied => [ 'disable' => '', 'style' => 'danger' , 'value' => $uacDenied, 'name' => "option-$as", 'id' => "option-$as-deny"   ],
        );

        if (!is_null($this->level))
        {
            if ($this->level != 3 && $this->level > -1)
                $this->controls[$uacFull]['disable'] = 'disabled';

            else if ($this->level == -1)
            {
                $this->controls[$uacFull  ]['disable'] = 'disabled';
                $this->controls[$uacModify]['disable'] = 'disabled';
                $this->controls[$uacWrite ]['disable'] = 'disabled';
                $this->controls[$uacRead  ]['disable'] = 'disabled';
                $this->controls[$uacDenied]['disable'] = 'disabled';

                $this->initialValue = UAC::permToString(UAC::PERM_DENIED);
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.flat-perms-select');
    }
}
