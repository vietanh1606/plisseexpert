<?php

namespace Vicomage\ImportExport\Model\Import\Content;

class Block extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Vicomage\ImportExport\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Cms\Model\Block
     */
    protected $_blockModel;
    protected $messageManager;
    /**
     * Block constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Vicomage\ImportExport\Helper\Data $helper
     * @param \Magento\Cms\Model\Block $blockModel
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Vicomage\ImportExport\Helper\Data $helper,
        \Magento\Cms\Model\Block $blockModel,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_helper = $helper;
        $this->_blockModel = $blockModel;
        $this->messageManager = $messageManager;
    }

    /**
     * Import block from xml file
     *
     * @param bool $overwrite
     * @param string $fileName
     * @param string $filePath
     * @param array|int $storeIds
     */
    public function importBlock($overwrite = false, $fileName, $filePath, $storeIds)
    {
        $helper = $this->_helper;
        $backupFilePath = $helper->getFile($fileName, $filePath);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $block = $xmlObj->getNode('block');
            if($block){
                foreach ($block->children() as $item){
                    //Check if Block already exists
                    $collection = $objectManager->create('\Magento\Cms\Model\ResourceModel\Block\Collection');
                    $oldBlocks = $collection->addFieldToFilter('identifier', $item->identifier)->addStoreFilter($storeIds);

                    //If items can be overwritten
                    if ($overwrite){
                        if (count($oldBlocks) > 0){
                            $conflictingOldItems[] = $item->identifier;
                            foreach ($oldBlocks as $old) $old->delete();
                        }
                    }else {
                        if (count($oldBlocks) > 0){
                            $conflictingOldItems[] = $item->identifier;
                            continue;
                        }
                    }
                    $model = $objectManager->create('Magento\Cms\Model\Block');
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