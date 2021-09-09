<?php

return [
	'controllers_path' => 'App\\Http\\Controllers',

    /*
     * "name" means route name
     * "uri" means route uri
     */
	'separator_driver' => 'name',

    'ignore_list' => ['', 'admin', 'admin.', 'admin.dashboard', 'dashboard', 'panel']
];