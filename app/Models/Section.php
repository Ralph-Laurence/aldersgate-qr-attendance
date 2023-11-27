<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    const FIELD_ID              = 'id';
    const FIELD_SECTION_NAME    = 'section_name';
}
