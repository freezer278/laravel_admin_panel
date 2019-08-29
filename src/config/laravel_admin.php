<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Prefix before all routes of admin panel defined in routes/admin.php
    |--------------------------------------------------------------------------
    */
    'route_prefix' => 'admin',
    'export' => [
        'memory_limit' => '512M',
        'single_chunk_size' => 100,
    ],
];
