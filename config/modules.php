<?php

return [

    'namespace' => 'App\\Modules',

    'stubs' => [
        'enabled' => true,
        'path' => base_path('stubs/modules'),
        'files' => [
            'routes/web' => 'routes/web.php',
            'routes/api' => 'routes/api.php',
            'views/index' => 'resources/views/index.blade.php',
            'scaffold/config' => 'Config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'resources/js/app.js',
            'assets/sass/app' => 'resources/sass/app.scss',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/api' => ['LOWER_NAME', 'STUDLY_NAME'],
            'views/index' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['LOWER_NAME', 'STUDLY_NAME'],
            'composer' => ['LOWER_NAME', 'STUDLY_NAME', 'VENDOR', 'AUTHOR_NAME', 'AUTHOR_EMAIL'],
        ],
        'gitkeep' => true,
    ],

    'paths' => [
        'modules' => base_path('app/Modules'),
        'assets' => public_path('modules'),
        'migration' => base_path('database/migrations'),
        'generator' => [
            'config' => ['path' => 'Config', 'generate' => true],
            'command' => ['path' => 'Console', 'generate' => true],
            'migration' => ['path' => base_path('database/migrations'), 'generate' => false],
            'seeder' => ['path' => 'Database/Seeders', 'generate' => true],
            'factory' => ['path' => 'Database/Factories', 'generate' => true],
            'model' => ['path' => 'Models', 'generate' => true],
            'controller' => ['path' => 'Http/Controllers', 'generate' => true],
            'filter' => ['path' => 'Http/Middleware', 'generate' => true],
            'request' => ['path' => 'Http/Requests', 'generate' => true],
            'provider' => ['path' => 'Providers', 'generate' => true],
            'route-provider' => ['path' => 'Providers', 'generate' => true],
            'routes' => ['path' => 'routes', 'generate' => true],
            'listener' => ['path' => 'Listeners', 'generate' => true],
            'event' => ['path' => 'Events', 'generate' => true],
            'policies' => ['path' => 'Policies', 'generate' => true],
            'rules' => ['path' => 'Rules', 'generate' => true],
            'jobs' => ['path' => 'Jobs', 'generate' => true],
            'emails' => ['path' => 'Mail', 'generate' => true],
            'notifications' => ['path' => 'Notifications', 'generate' => true],
            'resource' => ['path' => 'Http/Resources', 'generate' => true],
            'test' => ['path' => 'Tests/Feature', 'generate' => true],
        ],
    ],

    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],

    'composer' => [
        'vendor' => 'sitiando',
        'author' => [
            'name' => 'Sitiando',
            'email' => 'info@sitiando.com',
        ],
    ],

    'cache' => [
        'enabled' => false,
        'key' => 'sitiando-modules-cache',
        'lifetime' => 60,
    ],

    'register' => [
        'translations' => true,
        'views' => true,
    ],

    'activators' => [
        'file' => [
            'class' => Nwidart\Modules\Activators\FileActivator::class,
            'statuses-file' => base_path('storage/modules_statuses.json'),
        ],
    ],

    'activator' => 'file',
];
