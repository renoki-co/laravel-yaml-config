<?php

return [

    /*
    |--------------------------------------------------------------------------
    | File Locations
    |--------------------------------------------------------------------------
    |
    | The list of locations to load configurations from.
    | The list is processed in the given order, from top to the bottom.
    |
    */

    'locations' => [
        ['path' => base_path('.laravel.defaults.yaml')],
        ['path' => base_path('.laravel.defaults.yml')],
        ['path' => base_path('.laravel.yaml')],
        ['path' => base_path('.laravel.yml')],
    ],

];
