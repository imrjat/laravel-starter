<?php

return [
    'prefix' => env('ADMIN_PREFIX', 'admin'),
    'is_live' => env('IS_LIVE', false),
    'trail_days' => (int) env('TRIAL_DAYS', 14),
];
