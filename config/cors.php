<?php

return [
    'paths' => ['api/*', 'login', 'sanctum/csrf-cookie'], // Apply CORS to API routes, login, and Sanctum endpoints
    'allowed_methods' => ['*'], // Allow all HTTP methods
    'allowed_origins' => ['http://127.0.0.1:5500'], // Allow your frontend origin
    'allowed_origins_patterns' => [], // No wildcard origin patterns
    'allowed_headers' => ['*'], // Allow all headers; you can limit this for security
    'exposed_headers' => [], // No need to expose custom headers
    'max_age' => 0, // Set cache duration for preflight responses (default: 0)
    'supports_credentials' => false, // Set to true if using cookies/auth tokens
];
?>