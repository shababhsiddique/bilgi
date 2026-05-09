<?php

namespace App\Helpers;

class Sms
{
    public static function send($message){
        //TODO: Implement SMS sending logic
        Log::info("Sending SMS ".$message);
    }
}
