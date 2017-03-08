<?php namespace Qubants\Scholar;

use Illuminate\Support\ServiceProvider;

class ScholarServiceProvider extends ServiceProvider
{

	protected $defer = false;

	public function boot()
	{
		$this->loadRoutesFrom(__DIR__.'/routes.php');
		$this->loadViewsFrom(__DIR__.'/Views', 'scholar');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

	}

}