<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'smsTimeConfig' => [
        'warranty_calim' => env('WARRANTY_CLAIM_SMS_TIME'),
        'wrong_time' => env('WRONGTIME_SMS_TIME'),
        'apply_insurance' => env('APPLYINSURANCE_SMS_TIME'),
        'late_payment' => env('LATEPAYMENT_SMS_TIME'),
        'warning_urgent' => env('WARNING_URGENT_SMS_TIME'),
        'acccessories' => env('ACCESSORIES_SMS_TIME'),
        'total_sale' => env('TOTAL_SALE_SMS_TIME'),
        'overdue_customer' => env('OVERDUE_CUSTOMER_SMS_TIME'),
        'ktdk' => env('KTDK_SMS_TIME'),
        'birthday' => env('BIRTHDAY_SMS_TIME'),
    ]

];
