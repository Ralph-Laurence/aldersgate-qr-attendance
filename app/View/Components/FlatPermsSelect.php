<?php

namespace App\View\Components;

use App\Models\Security\UserAccountControl as UAC;
use Illuminate\View\Component;

class FlatPermsSelect extends Component
{
    public $FLAG_ATTR_FULL     ; // = '';
    public $FLAG_ATTR_MODIFY   ; // = '';
    public $FLAG_ATTR_WRITE    ; // = '';
    public $FLAG_ATTR_READ     ; // = '';
    public $FLAG_ATTR_DENY     ; // = '';
    
    public $FLAG_EXCEPT_FULL   ; // = '';
    public $FLAG_EXCEPT_MODIFY ; // = '';

    public $stretchWidth;
    public $as;
    public $caption;
    public $dataDefault;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($as, $caption = null, $level = null, $stretchWidth = null)
    {
//         UAC::PERM_FULL_CONTROL) ?
// UAC::PERM_MODIFY)       ?
// UAC::PERM_WRITE)        ?
// UAC::PERM_READ)         ?
// UAC::PERM_DENIED)       ?

        $this->FLAG_ATTR_FULL   = (!is_null($level) && $level == 4) ? 'checked' : '';
        $this->FLAG_ATTR_MODIFY = (!is_null($level) && $level == 3) ? 'checked' : '';
        $this->FLAG_ATTR_WRITE  = (!is_null($level) && $level == 2) ? 'checked' : '';
        $this->FLAG_ATTR_READ   = (!is_null($level) && $level == 1) ? 'checked' : '';
        $this->FLAG_ATTR_DENY   = (!is_null($level) && $level == 0) ? 'checked' : '';

        // Only allow permission options according to the level set.
        // When a permission option is lower than the level specified
        $this->FLAG_EXCEPT_FULL   = (!is_null($level) && $level < 4) ? 'disabled' : '';
        $this->FLAG_EXCEPT_MODIFY = (!is_null($level) && $level < 3) ? 'disabled' : '';

        if (!is_null($stretchWidth))
            $this->$stretchWidth = 'w-100';

        $this->as = $as;
        $this->caption = $caption;

        if (!is_null($level))
            $this->dataDefault = $level;
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
