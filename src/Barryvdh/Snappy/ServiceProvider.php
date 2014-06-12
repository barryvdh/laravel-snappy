<?php namespace Barryvdh\Snappy;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->package('barryvdh/laravel-snappy');

        if($this->app['config']->get('laravel-snappy::config.pdf.enabled')){
            $this->app['snappy.pdf'] = $this->app->share(function($app)
            {
                $binary = $app['config']->get('laravel-snappy::config.pdf.binary');
                $options = $app['config']->get('laravel-snappy::config.pdf.options');
                $snappy = new IlluminateSnappyPdf($app['files'], $binary, $options);
                return $snappy;
            });

            $this->app['snappy.pdf.wrapper'] = $this->app->share(function($app)
            {
                return new PdfWrapper($app['snappy.pdf']);
            });
        }


        if($this->app['config']->get('laravel-snappy::config.image.enabled')){
            $this->app['snappy.image'] = $this->app->share(function($app)
            {
                $binary = $app['config']->get('laravel-snappy::config.image.binary');
                $options = $app['config']->get('laravel-snappy::config.image.options');
                $image = new IlluminateSnappyImage($app['files'], $binary, $options);
                return $image;
            });
        }

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('snappy.pdf', 'snappy.pdf.wrapper', 'snappy.image');
	}

}