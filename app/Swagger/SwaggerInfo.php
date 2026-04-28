<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Books API',
    version: '1.0.0'
)]
#[OA\Server(
    url: 'http://book-managment-system.test',
    description: 'Local Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class SwaggerInfo
{
}
