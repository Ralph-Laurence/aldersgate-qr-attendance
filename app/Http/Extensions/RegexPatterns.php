<?php

namespace App\Http\Extensions;

class RegexPatterns
{
    public const ALPHA_DASH_DOT_SPACE   = '/^[A-Za-z\.\- ]+$/';
    public const ALPHA_DASH_DOT         = '/^[A-Za-z\.\-]+$/';
    public const NUMERIC_DASH           = '/^[0-9\-]+$/';
    public const MOBILE_NO              = '/^[0-9]{11,12}$/';

    /**
    * In this regex:

        ^(?!_)          --> ensures the username does not start with an underscore.
        (?!.*?__)       --> ensures the username does not contain consecutive underscores.
        [A-Za-z0-9_]+   --> allows any number of letters, numbers, and underscores.
        (?<!_)          --> ensures the username does not end with an underscore.

    * This regex will match usernames that only contain letters, numbers, and underscores, 
    * do not start or end with underscores, and do not have consecutive underscores.
    */
    public const USERNAME = '/^(?!_)(?!.*?__)[A-Za-z0-9_]+(?<!_)$/';
}