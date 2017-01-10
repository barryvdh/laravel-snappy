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
        $configPath = __DIR__ . '/../config/snappy.php';
        $this->mergeConfigFrom($configPath, 'snappy');
    }

    public function boot()
    {
        $configPath = __DIR__ . '/../config/snappy.php';
        $this->publishes([$configPath => config_path('snappy.php')], 'config');
        
        if($this->app['config']->get('snappy.pdf.enabled')){
            $this->app->singleton('snappy.pdf', function($app)
            {
                $binary = $app['config']->get('snappy.pdf.binary', '/usr/local/bin/wkhtmltopdf');
                $options = $app['config']->get('snappy.pdf.options', array());
                $env = $app['config']->get('snappy.pdf.env', array());
                $timeout = $app['config']->get('snappy.pdf.timeout', false);

                $snappy = new IlluminateSnappyPdf($app['files'], $binary, $options, $env);
                if (false !== $timeout) {
                    $snappy->setTimeout($timeout);
                }

                return $snappy;
            });

            $this->app->singleton('snappy.pdf.wrapper', function($app)
            {
                return new PdfWrapper($app['snappy.pdf']);
            });
            $this->app->alias('snappy.pdf.wrapper', 'Barryvdh\Snappy\PdfWrapper');
        }


        if($this->app['config']->get('snappy.image.enabled')){
            $this->app->singleton('snappy.image', function($app)
            {
                $binary = $app['config']->get('snappy.image.binary', '/usr/local/bin/wkhtmltoimage');
                $options = $app['config']->get('snappy.image.options', array());
                $env = $app['config']->get('snappy.image.env', array());
                $timeout = $app['config']->get('snappy.image.timeout', false);

                $image = new IlluminateSnappyImage($app['files'], $binary, $options, $env);
                if (false !== $timeout) {
                    $image->setTimeout($timeout);
                }

                return $image;
            });

            $this->app->singleton('snappy.image.wrapper', function($app)
            {
                return new ImageWrapper($app['snappy.image']);
            });
            $this->app->alias('snappy.image.wrapper', 'Barryvdh\Snappy\ImageWrapper');
        }

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('snappy.pdf', 'snappy.pdf.wrapper', 'snappy.image', 'snappy.image.wrapper');
	}
}
