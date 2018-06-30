<?php

namespace Vicomage\Megamenu\Setup;

use Vicomage\Megamenu\Model\Group;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;
    private $groupModel;

    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        CategorySetupFactory $categorySetupFactory,
        Group $groupModel
    )
    {
        $this->groupModel = $groupModel;
        $this->categorySetupFactory = $categorySetupFactory;
    }
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $categoryIds = $this->getCategory();
        $data = [
            'title' => 'Main Menu',
            'categorys' => $categoryIds,
            'status' => 1,
            'menu_type' => 3,
        ];
        $this->groupModel->setData($data);
        $this->groupModel->save();
    }

    /**
     * get all category level 1
     * @return string
     */
    public function getCategory()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();

        $categoryId = $store->getRootCategoryId();
        $categorys = $objectManager->create('Magento\Catalog\Model\Category')->load($categoryId)->getChildrenCategories();

        $categoryIds = [];
        foreach($categorys as $category){
            $categoryIds[] = $category->getId();
        }
        return implode(',',$categoryIds);
    }
}
