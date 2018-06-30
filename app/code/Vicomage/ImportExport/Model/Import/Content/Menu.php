<?php

namespace Vicomage\ImportExport\Model\Import\Content;

class Menu extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Vicomage\ImportExport\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Cms\Model\Block
     */
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
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_helper = $helper;
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
    public function importMenu($overwrite = false, $fileName, $filePath, $storeIds)
    {
        $helper = $this->_helper;
        $backupFilePath = $helper->getFile($fileName, $filePath);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        try{
            if (!is_readable($backupFilePath)) throw new \Exception(__("Can't read data file: %1", $backupFilePath));
            $xmlObj = new \Magento\Framework\Simplexml\Config($backupFilePath);
            $num = 0;
            $block = $xmlObj->getNode('megamenu');
            if($block){
                foreach ($block->children() as $item){

                    //Check if Block already exists
                    $conflictingOldItems = [];
                    $collection = $objectManager->create('\Vicomage\Megamenu\Model\ResourceModel\Items\Collection');
                    $oldBlocks = $collection->addFieldToFilter('item_id', $item->item_id)->addFieldToFilter('store', $storeIds);

                    //If items can be overwritten
                    if ($overwrite){
                        if (count($oldBlocks) > 0){
                            $conflictingOldItems[] = $item->item_id;
                            foreach ($oldBlocks as $old) $old->delete();
                        }
                    } else {
                        if (count($oldBlocks) > 0){
                            $conflictingOldItems[] = $item->item_id;
                            continue;
                        }
                    }
                    $model = $objectManager->create('Vicomage\Megamenu\Model\Items');
                    $menuItem = $item->asArray();
                    if (isset($menuItem['item_id'])) unset($menuItem['item_id']);
                    $model->setData($menuItem)->save();
                    $num++;
                }
            }

            $this->messageManager->addSuccess(__('Import (%1) Item(s) in file "%2".', $num, $backupFilePath));

        } catch (\Exception $e) {
            $this->messageManager->addError(__('Can not import file "%1".<br/>"%2"', $backupFilePath, $e->getMessage()));
        }
    }
}