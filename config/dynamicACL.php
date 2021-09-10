<?php

return [
    /**
     * between rtl, ltr
     */
    'alignment' => 'ltr',

	'controllers_path' => 'App\\Http\\Controllers',

    /*
     * "name" means route name
     */
	'separator_driver' => 'name',

    'ignore_list' => ['', 'admin', 'admin.', 'admin.dashboard', 'dashboard', 'panel']
];