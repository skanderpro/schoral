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
	public function register() {

		$this->publishes([
			__DIR__.'/config.php' => config_path('courier.php'),
		]);
		$this->mergeConfigFrom(
			__DIR__.'/config.php', 'courier'
		);

		if (!file_exists(public_path('admin-constructor'))) {
			symlink(__DIR__ . '/public', public_path('admin-constructor'));
		}
	}

}