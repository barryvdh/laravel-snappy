<?php namespace Barryvdh\Snappy;

use Knp\Snappy\Pdf as SnappyPDF;
use Illuminate\Support\Facades\View;

/**
 * A Laravel wrapper for SnappyPDF
 *
 * @package laravel-snappy
 * @author Barry vd. Heuvel
 */
class PdfWrapper{

    /**
     * @var \Knp\Snappy\Pdf 
     */
    protected $snappy;

    /**
     * @var array
     */
    protected $options = array();


    /**
     * @param \Knp\Snappy\Pdf $snappy
     */
    public function __construct(SnappyPDF $snappy)
    {
       $this->snappy = $snappy;
    }

    /**
     * Get the Snappy instance.
     * 
     * @return \Knp\Snappy\Pdf
     */
     public function snappy()
     {
         return $this->snappy;
     }

    /**
     * Set the paper size (default A4)
     *
     * @param string $paper
     * @param string $orientation
     * @return $this
     */
    public function setPaper($paper, $orientation=null)
    {
        $this->snappy->setOption('page-size', $paper);
        if($orientation){
            $this->snappy->setOption('orientation', $orientation);
        }
        return $this;
    }

    /**
     * Set the orientation (default portrait)
     *
     * @param string $orientation
     * @return static
     */
    public function setOrientation($orientation)
    {
        $this->snappy->setOption('orientation', $orientation);
        return $this;
    }

    /**
     * Show or hide warnings
     *
     * @param bool $warnings
     * @return $this
     * @deprecated
     */
    public function setWarnings($warnings)
    {
        //Doesn't do anything
        return $this;
    }

    public function setOption($name, $value)
    {
        $this->snappy->setOption($name, $value);
        return $this;
    }

    public function setOptions($options)
    {
        $this->snappy->setOptions($options);
        return $this;
    }

    /**
     * Load a HTML string
     *
     * @param string $string
     * @return static
     */
    public function loadHTML($string)
    {
        $this->html = (string) $string;
        $this->file = null;
        return $this;
    }

    /**
     * Load a HTML file
     *
     * @param string $file
     * @return static
     */
    public function loadFile($file)
    {
        $this->html = null;
        $this->file = $file;
        return $this;
    }

    /**
     * Load a View and convert to HTML
     *
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return static
     */
    public function loadView($view, $data = array(), $mergeData = array())
    {
        $this->html = View::make($view, $data, $mergeData)->render();
        $this->file = null;
        return $this;
    }

    /**
	 * Output the PDF as a string.
	 *
	 * @return string The rendered PDF as string
	 * @throws \InvalidArgumentException
	 */
	public function output()
	{
		if ($this->html)
		{
			return $this->snappy->getOutputFromHtml($this->html, $this->options);
		}

		if ($this->file)
		{
			return $this->snappy->getOutput($this->file, $this->options);
		}

		throw new \InvalidArgumentException('PDF Generator requires a html or file in order to produce output.');
    }

    /**
     * Save the PDF to a file
     *
     * @param $filename
     * @return static
     */
    public function save($filename)
    {

        if ($this->html)
        {
            $this->snappy->generateFromHtml($this->html, $filename, $this->options);
        }
        elseif ($this->file)
        {
            $this->snappy->generate($this->file, $filename, $this->options);
        }

        return $this;
    }

    /**
     * Make the PDF downloadable by the user
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download($filename = 'document.pdf')
    {
        $output = $this->output();
        return \Response::make($output, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'attachment; filename="'.$filename.'"'
        ));
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stream($filename = 'document.pdf')
    {
        $that = $this;
        return \Response::stream(function() use($that){
            echo $that->output();
        }, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ));
    }

    /**
     * Call Snappy instance.
     * 
     * Also shortcut's
     * ->html => loadHtml
     * ->view => loadView
     * ->file => loadFile
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $method = 'load' . ucfirst($name);
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $arguments);
        }
        
        return call_user_func_array (array($this->snappy, $name), $arguments);
    }

}
