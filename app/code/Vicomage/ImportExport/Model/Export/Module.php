<?php

namespace Vicomage\ImportExport\Model\Export;
use Magento\Framework\App\Filesystem\DirectoryList;

class Module implements \Magento\Framework\Option\ArrayInterface
{
	/**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;
    /**
     * @param \Magento\Cms\Model\Block $blockModel
     */
    public function __construct(
        \Magento\Framework\Module\ModuleListInterface $moduleList
        ) {
    	$this->_moduleList = $moduleList;
    }

    public function toArray()
    {
        $output = [];
        $modules = $this->_moduleList->getNames();
        sort($modules);
        foreach ($modules as $k => $v) {
            if(preg_match("/Vicomage/", $v)){
                $output[$k] = $v;
            }
        }
        return $output;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $output = [];
        $modules = $this->_moduleList->getNames();
        sort($modules);
        foreach ($modules as $k => $v) {
            if(preg_match("/Vicomage/", $v)){
                $output[$k] = [
                'value' => $v,
                'label' => $v
                ];
            }
        }
        return $output;
    }

    protected function getInstallConfig()
    {
        $file = $this->_filesystem->getDirectoryRead(DirectoryList::APP)->getAbsolutePath('etc/config.php');
        $installConfig = include $file;
        return $installConfig;
    }
}

