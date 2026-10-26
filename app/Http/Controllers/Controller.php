<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="API Gestión de Productos",
 *     version="1.0.0",
 *     description="API RESTful para la gestión de productos, divisas y precios en múltiples monedas. Desarrollada con Laravel 12 siguiendo principios SOLID.",
 *     @OA\Contact(
 *         email="soporte@example.com",
 *         name="Equipo de Desarrollo"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor local de desarrollo"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación mediante token Bearer JWT"
 * )
 *
 * @OA\Tag(
 *     name="Productos",
 *     description="Endpoints para la gestión de productos (CRUD)"
 * )
 *
 * @OA\Tag(
 *     name="Precios de Productos",
 *     description="Endpoints para gestionar precios de productos en distintas divisas"
 * )
 */
abstract class Controller
{
    //
}
