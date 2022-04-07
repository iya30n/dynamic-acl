<?php

abstract class TestCase extends Orchestra\Testbench\TestCase
{
	protected function getPackageProviders($app)
    {
        return [
            'Iya30n\DynamicAcl\Providers\DynamicAclServiceProvider',
            'Javoscript\MacroableModels\MacroableModelsServiceProvider'
        ];
    }
}
