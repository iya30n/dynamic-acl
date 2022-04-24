<?php

use Iya30n\DynamicAcl\Http\Controllers\RoleController;


if (!config()->has('easy_panel')) {
    Route::group([
        'middleware' => ['web', 'dynamicAcl'],
        'prefix' => 'admin',
        'name' => 'admin.'
    ], function($router) {
        // TODO: name does not work on route group (find a way for it)
    
    //	Route::resource('roles', RoleController::class);
    //    $router->resource('roles', RoleController::class);
    
        Route::get('roles', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('roles/{role}', [RoleController::class, 'edit'])->name('admin.roles.edit');
        Route::patch('roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::get('roles/{role}/delete', [RoleController::class, 'destroy'])->name('admin.roles.delete');
    });
}