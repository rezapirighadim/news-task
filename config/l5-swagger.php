<?php

return [

    'default' => 'default',

    'documentations' => [

        'default' => [
            'api' => [
                'title' => 'News Aggregator API',
            ],

            'routes' => [
                'api' => 'api/documentation',
            ],

            'paths' => [
                'docs_json' => 'api-docs.json',
                'docs' => storage_path('api-docs'),
                'annotations' => [
                    base_path('app/Http/OpenApi'),
                ],
                'base' => env('L5_SWAGGER_BASE_PATH', null),
                'views' => base_path('resources/views/vendor/l5-swagger'),
            ],

            'securityDefinitions' => [
                'bearerAuth' => [
                    'type' => 'apiKey',
                    'description' => 'Use a valid API token',
                    'name' => 'Authorization',
                    'in' => 'header',
                ],
            ],

            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        ],
    ],
];
