<?php

namespace Vicomage\ImportExport\Model\Import;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\Filesystem;

class Tool implements ArrayInterface
{
    /**
     * @var Filesystem
     */
    protected $_fileSystem;

    /**
     * Tool constructor.
     * @param Filesystem $fileSystem
     */
    public function __construct(
        Filesystem $fileSystem
    ) {
        $this->_fileSystem = $fileSystem;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * Build option to import
     *
     * @param bool $withEmpty
     * @return array
     */
    protected function getAllOptions($withEmpty = true)
    {
        $path = sprintf(Import::CMS, '');
        $path = str_replace("//", "/", $path);
        $dir = $this->_fileSystem->getDirectoryWrite(DirectoryList::APP);
        $path = $dir->getAbsolutePath($path);
        $packages = $this->_listDirectories($path);
        $themeOptions[] = [
            'value' => '',
            'label' => '-- Select Theme --'
        ];
        foreach ($packages as $pkg) {
            $themes = $this->_listDirectories($path.$pkg);
            foreach ($themes as $theme) {
                $themeOptions[] = [
                    'label' => $pkg. '/' .$theme,
                    'value' => $pkg. '/' .$theme
                ];
            }
        }
        $themeOptions[0]['label'] = (count($themeOptions) > 1) ? __('-- Select Theme --') : __('-- Not found theme --');

        return $themeOptions;
    }


    public function getImage($withEmpty = true)
    {


        $path = sprintf(Import::CMS, '');
        $path = str_replace("//", "/", $path);
        $dir = $this->_fileSystem->getDirectoryWrite(DirectoryList::APP);
        $path = $dir->getAbsolutePath($path);
        $packages = $this->_listDirectories($path);
        $themeOptions = [];
        foreach ($packages as $pkg) {
            $themes = $this->_listDirectories($path.$pkg);
            foreach ($themes as $theme) {
                $themeOptions[] =  ucfirst($pkg). '/' .$theme;
            }
        }

        return $themeOptions;
    }

    /**
     * Read folder import to get list theme
     * 
     * @param string $path
     * @param bool $fullPath
     * @return array
     */
    protected function _listDirectories($path, $fullPath = false)
    {
        $result = [];
        if(is_dir($path)){
            $dir = opendir($path);
            if ($dir) {
                while ($entry = readdir($dir)) {
                    if (substr($entry, 0, 1) == '.' || !is_dir($path . DIRECTORY_SEPARATOR . $entry)){
                        continue;
                    }
                    if ($fullPath) {
                        $entry = $path . DIRECTORY_SEPARATOR . $entry;
                    }
                    $result[] = $entry;
                }
                unset($entry);
                closedir($dir);
            }
        }
        return $result;
    }
}