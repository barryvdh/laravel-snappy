<?php
namespace Barryvdh\Snappy\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;
use Barryvdh\Snappy\PdfFaker;

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