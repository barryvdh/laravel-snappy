## Snappy PDF/Image Wrapper for Laravel 5 and Lumen 5.1

### For Laravel 4.x, check [version 0.1](https://github.com/barryvdh/laravel-snappy/tree/0.1)

This package is a ServiceProvider for Snappy: [https://github.com/KnpLabs/snappy](https://github.com/KnpLabs/snappy).

You need to have wkhtmltopdf/wkhtmltoimage installed. You can download wkhtmltopdf from http://wkhtmltopdf.org/downloads.html See [https://github.com/KnpLabs/snappy#wkhtmltopdf-binary-as-composer-dependencies](https://github.com/KnpLabs/snappy#wkhtmltopdf-binary-as-composer-dependencies) how to do it with composer. Please note that some dependencies (libXrender for example) may not be present on your system and may require manual installation. After installing, verify first if wkhtmltopdf works correctly when invoked from the command line / shell.

The package provides $app['snappy.pdf'] and $app['snappy.image']. You have to set the binary location in the config file. Copy `config/snappy.php` to your own config, or use your ConfigServiceProvider so set the config keys.

and then adapt the "binary" line in the published config file (afer publishing should be present in: app/config/packages/barryvdh/laravel-snappy/config.php).

For example, when loaded with composer, the line should look like:

    'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),

In addition to the Snappy classes, it provides a wrapper, similar to https://github.com/barryvdh/laravel-dompdf

Require this package in your composer.json and update composer.

    composer require barryvdh/laravel-snappy

### Laravel

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

    Barryvdh\Snappy\ServiceProvider::class,

You can optionally use the facade for shorter code. Add this to your facades:

    'PDF' => Barryvdh\Snappy\Facades\SnappyPdf::class,
    'Image' => Barryvdh\Snappy\Facades\SnappyImage::class,

You can  publish the config-file to change some settings (default paper etc).

    php artisan vendor:publish
    
#### Homestead

If you are using homestead for your Laravel development you might have the problem that the shipped version of wkhtmltopdf for Ubuntu is compiled with the need for an installed x-server.  

However to avoid installing a complet x-server in your Homestead enviroment you could use xvfb (X virtual framebuffer).

Steps to setup wkhtmltopdf in Homestead with xvfb:

1. apt-get install wkhtmltopdf
2. apt-get install xvfb
3. echo -e '#!/bin/bash\nxvfb-run -a --server-args="-screen 0, 1920x1080x24" /usr/bin/wkhtmltopdf -q $*' > /usr/bin/wkhtmltopdf.sh; chmod a+x /usr/bin/wkhtmltopdf.sh; ln -s /usr/bin/wkhtmltopdf.sh /usr/local/bin/wkhtmltopdf
4. Test out making a PDF file: wkhtmltopdf http://www.google.com google.pdf

This should now allow you to use wkhtmltopdf in Homestead without installing a complete x-server.

### Lumen
In `bootstrap/app.php` add:
   
    class_alias('Barryvdh\Snappy\Facades\SnappyPdf', 'PDF');
    $app->register(Barryvdh\Snappy\LumenServiceProvider::class);

Optionally, add the facades like so:

    class_alias(Barryvdh\Snappy\Facades\SnappyPdf::class, 'PDF');
    class_alias(Barryvdh\Snappy\Facades\SnappyImage::class, 'Image');

To customise the configuration file, copy the file `/vendor/barryvdh/laravel-snappy/config/snappy.php` to the `/config` folder.

### Usage

You can create a new Snappy PDF/Image instance and load a HTML string, file or view name. You can save it to a file, or inline (show in browser) or download.

Using the App container:

    $snappy = App::make('snappy.pdf');
    //To file
    $snappy->generateFromHtml('<h1>Bill</h1><p>You owe me money, dude.</p>', '/tmp/bill-123.pdf');
    $snappy->generate('http://www.github.com', '/tmp/github.pdf'));
    //Or output:
    return new Response(
        $snappy->getOutputFromHtml($html),
        200,
        array(
            'Content-Type'          => 'application/pdf',
            'Content-Disposition'   => 'attachment; filename="file.pdf"'
        )
    );

Using the wrapper:

    $pdf = App::make('snappy.pdf.wrapper');
    $pdf->loadHTML('<h1>Test</h1>');
    return $pdf->inline();

Or use the facade:

    $pdf = PDF::loadView('pdf.invoice', $data);
    return $pdf->download('invoice.pdf');

You can chain the methods:

    return PDF::loadFile('http://www.github.com')->inline('github.pdf');

You can change the orientation and paper size

    PDF::loadHTML($html)->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0)->save('myfile.pdf')

If you need the output as a string, you can get the rendered PDF with the output() function, so you can save/output it yourself.

See the [wkhtmltopdf manual](http://wkhtmltopdf.org/usage/wkhtmltopdf.txt) for more information/settings.

### License

This Snappy Wrapper for Laravel is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
