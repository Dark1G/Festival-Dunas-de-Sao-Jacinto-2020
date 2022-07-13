<?php
use Carbon\Carbon;

if (!function_exists('greetings_pt')) {
    function greetings_pt($date) {
        $time = Carbon::parse($date)->format('H');
        switch ($time) {
            case (13 < $time) && ($time < 20): return 'Boa tarde' ; break; 
            case (05 < $time) && ($time < 13): return 'Bom dia' ; break; 
            default: return 'Boa noite' ; break; 
        } 
    } 
}