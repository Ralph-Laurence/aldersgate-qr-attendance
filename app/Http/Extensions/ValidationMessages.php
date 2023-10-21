<?php

namespace App\Http\Extensions;

class ValidationMessages
{
    public static function required($fieldName = '') : string
    {
        if (!empty($fieldName))
            return "$fieldName must be filled out.";

        return 'Please fill out this field.';
    }

    public static function maxLength($length, $fieldName = '') : string
    {
        if (!empty($fieldName))
            return "$fieldName must not exceed $length characters.";

        return "Must not exceed $length characters.";
    }

    public static function invalid($fieldName = '') : string
    {
        if (!empty($fieldName))
            return "$fieldName doesn't seem to be correct.";

        return "Invalid entry. Please try again.";
    }

    public static function numericDash($fieldName = '') : string 
    {
        if (!empty($fieldName))
            return "$fieldName may only contain numbers and dashes.";

        return 'This field may only contain numbers and dashes.';
    }

    public static function alphaDashDot($fieldName = '') : string 
    {
        if (!empty($fieldName))
            return "$fieldName may only contain letters, dots and dashes.";

        return 'This field may only contain letters, dots and dashes.';
    }

    public static function alphaDashDotSpace($fieldName = '') : string 
    {
        if (!empty($fieldName))
            return "$fieldName may only contain letters, space, dots and dashes.";

        return 'This field may only contain letters, space, dots and dashes.';
    }

    public static function mobile($fieldName = '') : string
    {
        if (!empty($fieldName))
            return "$fieldName must be 11 or 12 digits without special charactes like '#' and '+'.";

        return "Must be 11 or 12 digits without special charactes like '#' and '+'.";
    }

    public static function option($fieldName) : string
    {
        $article = Utils::toIndefiniteArticle($fieldName);

        return "Please select $article $fieldName";
    }
}