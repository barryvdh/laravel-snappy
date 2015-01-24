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
        $config = require __DIR__ . '/../config/snappy.php';
        $config = array_merge($config, $this->app['config']->get('snappy', []));
        $this->app['config']->set('snappy', $config);
    }

    public function boot()
    {
        if($this->app['config']->get('snappy.pdf.enabled')){
            $this->app['snappy.pdf'] = $this->app->share(function($app)
            {
                $binary = $app['config']->get('snappy.pdf.binary');
                $options = $app['config']->get('snappy.pdf.options');
                $timeout = $app['config']->get('snappy.pdf.timeout', false);

                $snappy = new IlluminateSnappyPdf($app['files'], $binary, $options);
                if (false !== $timeout) {
                    $snappy->setTimeout($timeout);
                }

                return $snappy;
            });

            $this->app['snappy.pdf.wrapper'] = $this->app->share(function($app)
            {
                return new PdfWrapper($app['snappy.pdf']);
            });
        }


        if($this->app['config']->get('snappy.image.enabled')){
            $this->app['snappy.image'] = $this->app->share(function($app)
            {
                $binary = $app['config']->get('snappy.image.binary');
                $options = $app['config']->get('snappy.image.options');
                $timeout = $app['config']->get('snappy.image.timeout', false);

                $image = new IlluminateSnappyImage($app['files'], $binary, $options);
                if (false !== $timeout) {
                    $image->setTimeout($timeout);
                }

                return $image;
            });

            $this->app['snappy.image.wrapper'] = $this->app->share(function($app)
            {
                return new ImageWrapper($app['snappy.image']);
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
		return array('snappy.pdf', 'snappy.pdf.wrapper', 'snappy.image', 'snappy.image.wrapper');
	}

}
