<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Sms
{
    public static function send($message){
        //TODO: Implement SMS sending logic
        Log::info("Sending SMS ".$message);
    }
}
