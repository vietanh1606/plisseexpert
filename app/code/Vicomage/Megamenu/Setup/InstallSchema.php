<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Vicomage\Megamenu\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();


        $table = $installer->getConnection()->newTable(
            $installer->getTable('vicomage_megamenu_group')
        )->addColumn('group_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true])
            ->addColumn('title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''])
            ->addColumn('items',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''])
            ->addColumn('menu_type',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'default' => '1'])
            ->addColumn('status',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'default' => '1']);
        $installer->getConnection()->createTable($table);


        /**
         * Create table 'megamenu_item'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('vicomage_megamenu_item')
        )->addColumn(
            'item_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Item Id'
        )->addColumn(
            'label',
            Table::TYPE_TEXT,
            255,
            [],
            'Label'
        )->addColumn(
            'menu_type',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => 1],
            'Menu types'
        )->addColumn(
            'url',
            Table::TYPE_TEXT,
            255,
            [],
            'Item url'
        )->addColumn(
            'menu_ef',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => false],
            'menu_ef'
        )->addColumn(
            'static_width',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'static_width'
        )->addColumn(
            'category_label',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'category_label'
        )->addColumn(
            'categorys',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'categorys'
        )->addColumn(
            'fake_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'fake_name'
        )->addColumn(
            'subcategory_columns',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false,'default' => 0],
            'subcategory_columns'
        )->addColumn(
            'main_content',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false,'default' => ''],
            'main_content'
        )->addColumn(
            'position',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => 0],
            'Position'
        )->addColumn(
            'columns',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => 0],
            'Columns'
        )->addColumn(
            'custom_class',
            Table::TYPE_TEXT,
            255,
            [],
            'Custom class'
        )->addColumn(
            'html_label',
            Table::TYPE_TEXT,
            255,
            [],
            'Html label'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => 1],
            'Status'
        )->addColumn(
            'store',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => 0],
            'Store Id'
        )->addColumn(
            'category_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => 0],
            'Category Id'
        )->addColumn(
            'top_content',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Top content'
        )->addColumn(
            'bottom_content',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Bottom content'
        )->addColumn(
            'left_content',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Left content'
        )->addColumn(
            'left_col',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => true],
            'columns of left'
        )->addColumn(
            'right_content',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Right content'
        )->addColumn(
            'right_col',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => true],
            'columns of right'
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();

    }
}
