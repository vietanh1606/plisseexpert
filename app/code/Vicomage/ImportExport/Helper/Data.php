<?php

namespace Vicomage\ImportExport\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    CONST IMPORT_MENU_FILE_NAME = 'megamenu.xml';
    CONST IMPORT_SLIDER_FILE_NAME = 'slider.xml';
    CONST IMPORT_BLOCK_FILE_NAME = 'block.xml';
    CONST IMPORT_PRODUCT_FILE_NAME = 'product.xml';
    CONST IMPORT_PAGE_FILE_NAME = 'page.xml';
    CONST IMPORT_SYSTEM_CONFIG_FILE_NAME = 'system.xml';
    CONST CMS = 'code/Vicomage/ImportExport/data/import/%s/';
    const FILE_TOP_LEVEL_DIR	= 'vicomage';
    const FILE_MAIN_DIR			= 'importExport';

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_dir;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var string
     */
    protected $_tmpFileBaseDir;


    /**
     * Modules associated with package
     *
     * @var array
     */
    protected $_packageModules = [
        'Vicomage_General',
        'Vicomage_Color',
        'Vicomage_Brand',
        'Vicomage_Blog',
        'Vicomage_Megamenu',
        'Vicomage_Quickview',
    ];

    /**
     * Human-readable names of modules
     *
     * @var array
     */
    protected $_moduleNames = [
        'Vicomage_General'          =>  'General Setting',
        'Vicomage_Color'            =>  'Color Setting',
        'Vicomage_Brand'            =>  'Brand Setting',
        'Vicomage_Blog'             =>  'Blog Setting',
        'Vicomage_Megamenu'         =>  'Mega Menu Setting',
        'Vicomage_Quickview'        =>  'Quickview Setting',
    ];

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Filesystem $filesystem
     * @param \Magento\Framework\Message\ManagerInterface $messageInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Filesystem $filesystem,
        \Magento\Framework\Message\ManagerInterface $messageInterface
    ) {
        $this->_filesystem = $filesystem;
        $this->_tmpFileBaseDir = $this->_filesystem->getDirectoryWrite('media')->getAbsolutePath() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . self::FILE_TOP_LEVEL_DIR . DIRECTORY_SEPARATOR;
        parent::__construct($context);

        $this->_messageManager = $messageInterface;
        $this->_dir = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
    }

    public function getFile($fileName, $filePath)
    {
        $filePath = $filePath .$fileName;
        return $this->_dir->getAbsolutePath($filePath);
    }

    public function getDir()
    {
        return $this->_dir;
    }

    public function showMessage($message, $type)
    {
        switch ($type) {
            case 'success':
                $this->_messageManager->addSuccessMessage($message);
                break;
            case 'error':
                $this->_messageManager->addErrorMessage($message);
                break;
            default:
                break;
        }
    }

    /**
     * Get modules associated with package
     *
     * @param string
     * @return array
     */
    public function getPackageModules()
    {
        if (isset($this->_packageModules))
        {
            return $this->_packageModules;
        }
    }

    /**
     * Get human-readable names of modules
     *
     * @return array
     */
    public function getModuleNames()
    {
        return $this->_moduleNames;
    }


    /**
     * Get desitnation directory for files uploaded via form
     *
     * @return string
     */
    public function getTmpFileBaseDir()
    {
        return $this->_tmpFileBaseDir;
    }
}