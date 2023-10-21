<?php

namespace App\Http\Extensions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Utils
{
    public const STATUS_CODE_ACTION_SUCCESS             = '0x0';
    public const STATUS_CODE_ACTION_FAILED              = '0x1';
    public const STATUS_CODE_INVALID_QR_CONTENT         = '0x2';
    public const STATUS_CODE_CREATE_ATTENDANCE_FAILED   = '0x3';
    public const STATUS_CODE_UNRECOGNIZED_STUDENT_NO    = '0x4';
    public const STATUS_CODE_TIMED_IN                   = '0x5';
    public const STATUS_CODE_TIMED_OUT                  = '0x6';
    public const STATUS_CODE_TIMED_OUT_FAILED           = '0x7';
    public const STATUS_CODE_TIMED_IN_FAILED            = '0x8';

    public const STATUS_MESSAGES =
    [
        self::STATUS_CODE_TIMED_IN                      => "Student timed in",
        self::STATUS_CODE_TIMED_OUT                     => "Student timed out",
        self::STATUS_CODE_ACTION_SUCCESS                => "Success",
        self::STATUS_CODE_ACTION_FAILED                 => "Failed",
        self::STATUS_CODE_INVALID_QR_CONTENT            => "Invalid QR Code content",
        self::STATUS_CODE_CREATE_ATTENDANCE_FAILED      => "Failed to create attendance record",
        self::STATUS_CODE_UNRECOGNIZED_STUDENT_NO       => "The QR code isn't recognized. Please check your QR code or contact the system administrator.",
        self::STATUS_CODE_TIMED_OUT_FAILED              => "Failed to record Time Out",
        self::STATUS_CODE_TIMED_IN_FAILED               => "Failed to record Time In",
    ];

    /**
     * This builds a path string for the photo file
     */
    public static function getPhotoPath($photo) : string
    {
        $photoPath = 'profiles/' . $photo;

        if (Storage::disk('public')->exists($photoPath))
            return asset('storage/' . $photoPath);
        else 
            return asset('storage/profiles/common.jpg');
    }

    /**
     * Format a timestamp to readable string
     */
    public static function formatTimestamp(string $timestamp, $format) : string 
    {
        $formatted = Carbon::parse($timestamp)->format($format);

        return $formatted;
    }

    /**
     * This builds a JSON response containing message, status codes and optional data
     */
    public static function makeResponse(string $status, string $msg, $data = null) : string
    {
        $response = [
            'message' => $msg,
            'status'  => $status
        ];

        if ($data != null)
            $response['data'] = $data;

        return json_encode($response);
    }

    public static function makeFailResponse(string $status) : string
    {
        return json_encode([
            'message' => self::STATUS_MESSAGES[$status],
            'status'  => $status
        ]);
    }

    /**
     * Gets a status message based on its code
     */
    public static function getStatusMessage(string $code) : string
    {
        return self::STATUS_MESSAGES[$code];
    }


    public static function dateToday(string $format = 'Y-m-d')
    {
        return date($format);
    }

    /**
     * Formats a date into a user-specific format
     */
    public static function dateToString($date, $format = 'Y-m-d') : string
    {
        if (empty($date))
            return '';

        return date($format, strtotime($date));
    }

    /**
     * Converts a number into its ordinal form.
     * This ordinal function takes a number as input and returns 
     * a string that represents the ordinal form of the number. 
     * It works by appending the appropriate suffix 
     * (‘st’, ‘nd’, ‘rd’, or ‘th’) to the number.
     * 
     * When onlyOrdinal is set to TRUE, only the suffix is returned
     */
    public static function toOrdinal($number, $onlyOrdinal = false) : string 
    {
        $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
    
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $onlyOrdinal ? 'th' : $number. 'th';
        else
            return $onlyOrdinal ? $ends[$number % 10] : $number. $ends[$number % 10];
    }

    public static function toIndefiniteArticle($word)
    {
        $vowels = ['a', 'e', 'i', 'o', 'u'];
        $firstLetter = strtolower($word[0]);

        if (in_array($firstLetter, $vowels))
            return "an";

        return "a";
    }
    
}