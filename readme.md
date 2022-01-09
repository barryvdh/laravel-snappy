## Snappy PDF/Image Wrapper for Laravel

This package is a ServiceProvider for Snappy: [https://github.com/KnpLabs/snappy](https://github.com/KnpLabs/snappy).

### Wkhtmltopdf Installation

Choose one of the following options to install wkhtmltopdf/wkhtmltoimage.

1. Download wkhtmltopdf from [here](http://wkhtmltopdf.org/downloads.html); 
2. Or install as a composer dependency. See [wkhtmltopdf binary as composer dependencies](https://github.com/KnpLabs/snappy#wkhtmltopdf-binary-as-composer-dependencies) for more information.

#### Attention! Please note that some dependencies (libXrender for example) may not be present on your system and may require manual installation. 

### Testing the wkhtmltopdf installation

After installing you should be able to run wkhtmltopdf from the command line / shell.

If you went for the second option the binaries will be at `/vendor/h4cc/wkhtmltoimage-amd64/bin` and `/vendor/h4cc/wkhtmltopdf-amd64/bin`. 

#### Attention vagrant users!

Move the binaries to a path that is not in a synced folder, for example:

```bash
cp vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64 /usr/local/bin/
cp vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64 /usr/local/bin/
```

and make it executable:

```bash
chmod +x /usr/local/bin/wkhtmltoimage-amd64 
chmod +x /usr/local/bin/wkhtmltopdf-amd64
```

This will prevent the error 126.

### Package Installation

Require this package in your composer.json and update composer.

```bash
composer require barryvdh/laravel-snappy
```

### Laravel

**Laravel 5.5** uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider/Facade. If you also use laravel-dompdf, watch out for conflicts. It could be better to manually register the Facade.

After updating composer, add the ServiceProvider to the providers array in config/app.php

```php
Barryvdh\Snappy\ServiceProvider::class,
```

Optionally you can use the Facade for shorter code. Add this to your facades:

```php
'PDF' => Barryvdh\Snappy\Facades\SnappyPdf::class,
'SnappyImage' => Barryvdh\Snappy\Facades\SnappyImage::class,
```

Finally you can publish the config file:

```bash
php artisan vendor:publish --provider="Barryvdh\Snappy\ServiceProvider"
```

### Snappy config file

The main change to this config file (config/snappy.php) will be the path to the binaries.

For example, when loaded with composer, the line should look like:

```php
'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
```

If you followed the vagrant steps, the line should look like:

```php
'binary'  => '/usr/local/bin/wkhtmltopdf-amd64',
```

For windows users you'll have to add double quotes to the bin path for wkhtmltopdf:

```php
'binary' => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"'
```

### Lumen
In `bootstrap/app.php` add:

```php
class_alias('Barryvdh\Snappy\Facades\SnappyPdf', 'PDF');
$app->register(Barryvdh\Snappy\LumenServiceProvider::class);
```

Optionally, add the facades like so:

```php
class_alias(Barryvdh\Snappy\Facades\SnappyPdf::class, 'PDF');
class_alias(Barryvdh\Snappy\Facades\SnappyImage::class, 'SnappyImage');
```

To customise the configuration file, copy the file `/vendor/barryvdh/laravel-snappy/config/snappy.php` to the `/config` folder.

### Usage

You can create a new Snappy PDF/Image instance and load a HTML string, file or view name. You can save it to a file, or inline (show in browser) or download.

Using the App container:

```php
$snappy = App::make('snappy.pdf');
//To file
$html = '<h1>Bill</h1><p>You owe me money, dude.</p>';
$snappy->generateFromHtml($html, '/tmp/bill-123.pdf');
$snappy->generate('http://www.github.com', '/tmp/github.pdf');
//Or output:
return new Response(
    $snappy->getOutputFromHtml($html),
    200,
    array(
        'Content-Type'          => 'application/pdf',
        'Content-Disposition'   => 'attachment; filename="file.pdf"'
    )
);
```

Using the wrapper:

```php
$pdf = App::make('snappy.pdf.wrapper');
$pdf->loadHTML('<h1>Test</h1>');
return $pdf->inline();
```

Or use the facade:

```php
$pdf = PDF::loadView('pdf.invoice', $data);
return $pdf->download('invoice.pdf');
```

You can chain the methods:

```php
return PDF::loadFile('http://www.github.com')->inline('github.pdf');
```

You can change the orientation and paper size

```php
PDF::loadHTML($html)->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0)->save('myfile.pdf')
```

If you need the output as a string, you can get the rendered PDF with the output() function, so you can save/output it yourself.

See the [wkhtmltopdf manual](http://wkhtmltopdf.org/usage/wkhtmltopdf.txt) for more information/settings.

### Testing - PDF fake

As an alternative to mocking, you may use the `PDF` facade's `fake` method. When using fakes, assertions are made after the code under test is executed:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use PDF;

class ExampleTest extends TestCase
{
    public function testPrintOrderShipping()
    {
        PDF::fake();
        
        // Perform order shipping...
        
        PDF::assertViewIs('view-pdf-order-shipping');
        PDF::assertSee('Name');
    }
}
```

#### Other available assertions:

```php
PDF::assertViewIs($value);
PDF::assertViewHas($key, $value = null);
PDF::assertViewHasAll(array $bindings);
PDF::assertViewMissing($key);
PDF::assertSee($value);
PDF::assertSeeText($value);
PDF::assertDontSee($value);
PDF::assertDontSeeText($value);
PDF::assertFileNameIs($value);
```

### License

This Snappy Wrapper for Laravel is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
