<?php

namespace Vicomage\ImportExport\Model\Import\Content;

class Page extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Vicomage\ImportExport\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_pageModel;
    protected $messageManager;

    /**
     * Page constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Vicomage\ImportExport\Helper\Data $helper
     * @param \Magento\Cms\Model\Page $pageModel
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Vicomage\ImportExport\Helper\Data $helper,
        \Magento\Cms\Model\Page $pageModel,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_helper = $helper;
        $this->_pageModel = $pageModel;
        $this->messageManager = $messageManager;
    }

    /**
     * Import static pages from xml file
     *
     * @param bool $overwrite
     * @param string $fileName
     * @param string $filePath
     * @param array|int $storeIds
     */
    public function importPage($overwrite = false, $fileName, $filePath, $storeIds)
    {
        $helper = $this->_helper;
        $backupFilePath = $helper->getFile($fileName, $filePath);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $page = $xmlObj->getNode('page');
            if($page){
                foreach ($page->children() as $item){
                    //Check if Block already exists
                    $collection = $objectManager->create('\Magento\Cms\Model\ResourceModel\Page\Collection');
                    $oldPages = $collection->addFieldToFilter('identifier', $item->identifier)->addStoreFilter($storeIds);

                    //If items can be overwritten
                    if ($overwrite){
                        if (count($oldPages) > 0){
                            $conflictingOldItems[] = $item->identifier;
                            foreach ($oldPages as $old) $old->delete();
                        }
                    }else {
                        if (count($oldPages) > 0){
                            $conflictingOldItems[] = $item->identifier;
                            continue;
                        }
                    }
                    $model = $objectManager->create('Magento\Cms\Model\Page');
                    $model->setData($item->asArray())->setStores($storeIds)->save();
                    $num++;
                }
            }

            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));

        } catch (\Exception $e) {
            $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }
}