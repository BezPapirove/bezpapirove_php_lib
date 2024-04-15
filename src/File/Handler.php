<?php
namespace Bezpapirove\BezpapirovePhpLib\File;

use Bezpapirove\BezpapirovePhpLib\Exception\FileNotFoundException;

/**
* Handler is simple file handler for working with files on filesystem 
*
* @author Tomáš Otruba <tomas@bezpapirove.cz>
* @copyright 2024 BezPapírově s.r.o.
* 
* @version 1.0
*/
class Handler {

    protected $base_path;

    public function __construct(string $base_path)
    {
        $this->base_path = $base_path;
        if (false === is_dir($this->base_path)) {
            throw new FileNotFoundException('Base folder does not exists: ' . $this->base_path);
        }
        return $this;
    }

    /**
    * download
    *
    * @param string $file_name accept UUID v4 file name
    * 
    * @return array returns stream with file content
    * 
    * @throws throws \NotValidInputException when bad file name provided or file doesnt exists
    */
    public function download(string $file_name)
    {
        return false;
    }

    /**
    * upload
    *
    * @param string $file_path accept existing file on filesystem
    * 
    * @return string returns UUID name on filesystem
    * 
    * @throws throws \NotValidInputException when bad file name provided or file doesnt exists
    */
    public function upload(string $file_path) : string
    {
        return '';
    }

    /**
    * download
    *
    * @param string $file_name accept UUID v4 file name
    * 
    * @return array returns true when file is on filesystem stored
    * 
    * @throws throws \NotValidInputException when bad file name provided or file doesnt exists
    */
    public function exists(string $file_name) : bool
    {
        return false;
    }
 
}
?>