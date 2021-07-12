<?php

namespace App\DynamicAcl;

class ACL
{
	private $routes;

	private $separator;

	private $handlers = [
		'uri' => 'byUri',
		'route_name' => 'byName',
		'controller@method' => 'byController'
	];

	public function __construct()
	{
		$this->routes = (new Route)->getUserDefined();

		$this->separator = new Separator;
	}

	/** 
	 * Get developer defined routes filter by config('dynamicACL.control_with')
	 * 
	 * @return Array
	 */
	public function getRoutes(): Array
	{
		$handler = $this->handlers[config('dynamicACL.control_with')];

		return $this->separator->{$handler}($this->routes);
	}
}