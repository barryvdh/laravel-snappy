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