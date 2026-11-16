<?php

return [
    ['GET',  '/admin/login',              [AuthController::class, 'showLogin'],         null],
    ['POST', '/admin/login',              [AuthController::class, 'login'],             null],
    ['POST', '/admin/logout',             [AuthController::class, 'logout'],            'login'],

    ['GET',  '/admin/users',              [UserController::class, 'index'],             'admin'],
    ['GET',  '/admin/users/create',       [UserController::class, 'create'],            'admin'],
    ['POST', '/admin/users/store',        [UserController::class, 'store'],             'admin'],
    ['GET',  '/admin/users/{id}/edit',    [UserController::class, 'edit'],              'admin'],
    ['POST', '/admin/users/{id}/update',  [UserController::class, 'update'],            'admin'],
    ['POST', '/admin/users/{id}/toggle',  [UserController::class, 'toggleStatus'],      'admin'],
    ['POST', '/admin/users/{id}/delete',  [UserController::class, 'destroy'],           'admin'],
];
