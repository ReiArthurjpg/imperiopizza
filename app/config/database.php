<?php
return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'database' => getenv('DB_NAME') ?: 'imperialpizza',
    'user' => getenv('DB_USER') ?: 'imperial',
    'password' => getenv('DB_PASS') ?: 'imperial123',
];
