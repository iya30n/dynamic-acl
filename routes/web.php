<?php

use Iya30n\DynamicAcl\Http\Controllers\Admin\Role\RoleController;

Route::resource('roles', RoleController::class)->name('admin.role');