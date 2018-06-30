<?php

namespace Vicomage\ImportExport\Model\Import\Content;

class Config extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Vicomage\ImportExport\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_configResourceModel;

    /**
     * @var \Magento\Theme\Model\Theme
     */
    protected $_themeModel;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    protected $messageManager;
    protected $store;
    /**
     * Config constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Vicomage\ImportExport\Helper\Data $helper
     * @param \Magento\Config\Model\ResourceModel\Config $configResourceModel
     * @param \Magento\Theme\Model\Theme $themeModel
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $configInterface
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Config\Model\ResourceModel\Config\Data\Collection $store,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Vicomage\ImportExport\Helper\Data $helper,
        \Magento\Config\Model\ResourceModel\Config $configResourceModel,
        \Magento\Theme\Model\Theme $themeModel,
        \Magento\Framework\App\Config\ScopeConfigInterface $configInterface,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->store = $store;
        $this->_helper = $helper;
        $this->_configResourceModel = $configResourceModel;
        $this->_scopeConfig = $configInterface;
        $this->_themeModel = $themeModel;
        $this->messageManager = $messageManager;

    }

    /**
     * Import system configuration from xml file
     *
     * @param string $fileName
     * @param string $filePath
     */
    public function importConfig($fileName, $filePath, $storeIds, $scope)
    {
        $helper = $this->_helper;
        $backupFilePath = $helper->getFile($fileName, $filePath);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        try{

            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $system = $xmlObj->getNode('system');
            if($system){
                $model = $objectManager->create('Magento\Config\Model\ResourceModel\Config');
                foreach ($system->children() as $item){
                    $node = $item->asArray();
                    if(is_array($storeIds)){
                        foreach ($storeIds as $storeId) {
                            $oldConfig = $this->getValueStore( $node['path'], $scope, $storeId);
                            if($oldConfig != $node['value']){
                                $model->saveConfig($item->path, $node['value'], $scope, $storeId);
                                $num++;
                            }
                        }
                    } else {
                        $oldConfig = $this->getValueStore( $node['path'], $scope, $storeIds);
                        if($oldConfig != $node['value']){
                            $model->saveConfig($node['path'], $node['value'], $scope, $storeIds);
                            $num++;
                        }
                    }

                }
            }

            $themePath = $xmlObj->getNode('theme');
            $themeId = 0;
            $collection = $objectManager->create('Magento\Theme\Model\Theme')->getCollection();
            foreach ($collection as $item) {
                if($themePath == $item->getData('theme_path')){
                    $themeId = $item->getData('theme_id');
                    break;
                }
            }
            if($themeId){
                if(is_array($storeIds)){
                    foreach ($storeIds as $storeId) {
                        $model->saveConfig('design/theme/theme_id', $themeId, $scope, $storeId);
                        $num++;
                    }
                } else {
                    $model->saveConfig('design/theme/theme_id', $themeId, $scope, $storeIds);
                    $num++;
                }

            }
            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));

        } catch (\Exception $e) {
            $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }

    /**
     * get store by onfig
     * @param $path
     * @param $scope
     * @param $scopeId
     * @return null
     */
    public function getValueStore($path, $scope, $scopeId)
    {
        $storeCollection = $this->store->addFieldToFilter('path', ['like' => $path])
            ->addFieldToFilter('scope',$scope)
            ->addFieldToFilter('scope_id', $scopeId)->getFirstItem();
        $data = $storeCollection->getData();

        if(isset($data['value'])) {
            return $data['value'];
        }
        return null;
    }
}