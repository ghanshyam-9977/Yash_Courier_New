<?php

if (!function_exists('generateTrackingId')) {
    function generateTrackingId()
    {
        return 'WC' . date('Ymd') . strtoupper(uniqid());
    }
}

