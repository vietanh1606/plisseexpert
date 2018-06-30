<?php
namespace Vicomage\Megamenu\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $setup->startSetup();


        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            try {
                $column = [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'comment' => 'categorys',
                    'default' => ''
                ];
                $installer->getConnection()->addColumn($setup->getTable('vicomage_megamenu_group'), 'categorys', $column);
            } catch (\Exception $e) {

            }
        }


        $setup->endSetup();

    }
}