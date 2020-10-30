<?php namespace Barryvdh\Snappy;

use Knp\Snappy\Pdf;
use Illuminate\Filesystem\Filesystem;

class IlluminateSnappyPdf extends Pdf {

	/**
	 * @param \Illuminate\Filesystem\Filesystem
     * @param string $binary
     * @param array $options
	 */
	public function __construct(Filesystem $fs, $binary, array $options, array $env)
	{
		parent::__construct($binary, $options, $env);

		$this->fs = $fs;
	}

    /**
     * Wrapper for the "file_get_contents" function
     *
     * @param string $filename
     *
     * @return string
     */
    protected function getFileContents($filename) : string
    {
        return $this->fs->get($filename);
    }

    /**
     * Wrapper for the "file_exists" function
     *
     * @param string $filename
     *
     * @return boolean
     */
    protected function fileExists($filename) : bool
    {
        return $this->fs->exists($filename);
    }

    /**
     * Wrapper for the "is_file" method
     *
     * @param string $filename
     *
     * @return boolean
     */
    protected function isFile($filename) : bool
    {
        return strlen($filename) <= PHP_MAXPATHLEN && $this->fs->isFile($filename);
    }

    /**
     * Wrapper for the "filesize" function
     *
     * @param string $filename
     *
     * @return integer or FALSE on failure
     */
    protected function filesize($filename) : int
    {
        return $this->fs->size($filename);
    }

    /**
     * Wrapper for the "unlink" function
     *
     * @param string $filename
     *
     * @return boolean
     */
    protected function unlink($filename) : bool
    {
        return $this->fs->delete($filename);
    }

    /**
     * Wrapper for the "is_dir" function
     *
     * @param string $filename
     *
     * @return boolean
     */
    protected function isDir($filename) : bool
    {
        return $this->fs->isDirectory($filename);
    }

    /**
     * Wrapper for the mkdir function
     *
     * @param string $pathname
     *
     * @return boolean
     */
    protected function mkdir($pathname) : bool
    {
        return $this->fs->makeDirectory($pathname, 0777, true, true);
    }
   
}
