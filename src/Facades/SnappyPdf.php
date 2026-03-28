<?php
namespace Barryvdh\Snappy\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;
use Barryvdh\Snappy\PdfFaker;


/**
 * @method static \Barryvdh\Snappy\PdfWrapper setPaper($paper, $orientation = 'portrait')
 * @method static \Barryvdh\Snappy\PdfWrapper setWarnings($warnings)
 * @method static \Barryvdh\Snappy\PdfWrapper setOptions(array $options)
 * @method static \Barryvdh\Snappy\PdfWrapper loadView($view, $data = array(), $mergeData = array(), $encoding = null)
 * @method static \Barryvdh\Snappy\PdfWrapper loadHTML($string, $encoding = null)
 * @method static \Barryvdh\Snappy\PdfWrapper loadFile($file)
 * @method static string output($options = [])
 * @method static \Barryvdh\Snappy\PdfWrapper save()
 * @method static \Illuminate\Http\Response download($filename = 'document.pdf')
 * @method static \Illuminate\Http\Response inline($filename = 'document.pdf')
 *
 * @method static \Barryvdh\Snappy\PdfFaker ensureResponseHasView() Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertViewIs(string $value) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertViewHas(string|array $key, mixed $value = null) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertViewHasAll(array $bindings) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertViewMissing(string $key) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertSee(string $value) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertSeeText(string $value) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertDontSee(string $value) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertDontSeeText(string $value) Only available when using ::fake()
 * @method static \Barryvdh\Snappy\PdfFaker assertFileNameIs(string $value) Only available when using ::fake()
 */
class SnappyPdf extends BaseFacade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'snappy.pdf.wrapper'; }

    /**
     * Replace the bound instance with a fake.
     *
     * @return void
     */
    public static function fake()
    {
        static::swap(new PdfFaker(app('snappy.pdf')));
    }

}