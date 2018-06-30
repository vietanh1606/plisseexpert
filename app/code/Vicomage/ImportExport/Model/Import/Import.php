<?php

namespace Vicomage\ImportExport\Model\Import;

use Magento\Store\Model\StoreManagerInterface;
use Vicomage\ImportExport\Model\Import\Content\Block;
use Vicomage\ImportExport\Model\Import\Content\Page;
use Vicomage\ImportExport\Model\Import\Content\Config;
use Vicomage\ImportExport\Model\Import\Content\Menu;
use Vicomage\ImportExport\Model\Import\Content\Slider;

class Import extends \Magento\Framework\Model\AbstractModel
{
    CONST IMPORT_BLOCK_FILE_NAME = 'block.xml';
    CONST IMPORT_PRODUCT_FILE_NAME = 'product.xml';
    CONST IMPORT_PAGE_FILE_NAME = 'page.xml';
    CONST IMPORT_SYSTEM_CONFIG_FILE_NAME = 'systemconfig.xml';
    CONST CMS = 'code/Vicomage/ImportExport/data/import/%s/';

    /**
     * Save store id
     *
     * @var int
     */
    protected $_store = 0;

    /**
     * @var string
     */
    protected $_filePath = '';

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Vicomage\ImportExport\Helper\Data
     */
    protected $_helper;

    /**
     * @var Block
     */
    protected $_blockImport;

    /**
     * @var Page
     */
    protected $_pageImport;

    /**
     * @var Config
     */
    protected $_configImport;

    /**
     * @var MegaMenu
     */
    protected $_megaMenuImport;

    /**
     * @var Slider
     */
    protected $_sliderImport;

    /**
     * Import constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param \Vicomage\ImportExport\Helper\Data $helper
     * @param Block $blockImport
     * @param Page $pageImport
     * @param Config $configImport
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        StoreManagerInterface $storeManager,
        \Vicomage\ImportExport\Helper\Data $helper,
        Block $blockImport,
        Page $pageImport,
        Config $configImport,
        Menu $megaMenuImport,
        Slider $sliderImport,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
        $this->_blockImport = $blockImport;
        $this->_pageImport = $pageImport;
        $this->_configImport = $configImport;
        $this->_megaMenuImport = $megaMenuImport;
        $this->_sliderImport = $sliderImport;
    }

    /**
     * Handle xml file to import
     *
     * @param array $request
     */
    public function importXml($request)
    {
        $themePath = $request['theme_path'];
        $this->_filePath = sprintf(\Vicomage\ImportExport\Helper\Data::CMS, $themePath);
        $stores = isset($request['store_ids']) ? $request['store_ids'] : array(0);
        $scope = 'default';
        if (isset($request['scope']) && isset($request['scope_id'])) {
            $scope = $request['scope'];
            if ($request['scope'] == 'websites') {
                $stores = $this->_storeManager->getWebsite($request['scope_id'])->getStoreIds();
            } else {
                $stores = $request['scope_id'];
            }
        }
        $this->_store = is_array($stores) ? $stores : explode(',', $stores);

        if (isset($request['block']) && $request['block']) {
            $this->_blockImport->importBlock(isset($request['overwrite_block']), \Vicomage\ImportExport\Helper\Data::IMPORT_BLOCK_FILE_NAME, $this->_filePath, $this->_store);
        }
        if (isset($request['page']) && $request['page']) {
            $this->_pageImport->importPage(isset($request['overwrite_page']), \Vicomage\ImportExport\Helper\Data::IMPORT_PAGE_FILE_NAME, $this->_filePath, $this->_store);
        }
        if (isset($request['config']) && $request['config']) {
            $this->_configImport->importConfig(\Vicomage\ImportExport\Helper\Data::IMPORT_SYSTEM_CONFIG_FILE_NAME, $this->_filePath, $this->_store, $scope);
        }
        if (isset($request['menu']) && $request['menu']) {
            $this->_megaMenuImport->importMenu(isset($request['overwrite_menu']), \Vicomage\ImportExport\Helper\Data::IMPORT_MENU_FILE_NAME, $this->_filePath, $this->_store);
        }
        if (isset($request['slider']) && $request['slider']) {
            $this->_sliderImport->importSlider(isset($request['overwrite_slider']), \Vicomage\ImportExport\Helper\Data::IMPORT_SLIDER_FILE_NAME, $this->_filePath, $this->_store);
        }
    }

    
}