<?php
/**
 * Copyright © 2016 Vicomage. All rights reserved.
 */

namespace Vicomage\Slider\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('vicomage_slider_items'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn('name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 100, array(
				), 'Slider name')
			->addColumn('identity', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 100, array(
				), 'Identity name')
			->addColumn('slider_params', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '4M', array(
				), 'Slider params')
			->addColumn('width', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,50, array(
				), 'Width')
			->addColumn('height', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,50, array(
				), 'Height')
			->addColumn('number', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,50, array(
				), 'Number Image')				
			->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, array(
					'nullable'  => false,
					'default'   => '0',
					), 'Is Slide Active'
            )->addColumn(
                'navigation',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default'  =>  '0' ],
                'Navigation'
            )->addColumn(
                'pagercontrol',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default'  =>  '0' ],
                'pagercontrol'
            )->addColumn(
                'lazyload',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default'  =>  '0' ],
                'lazyload'
            )->addColumn(
			    'speed',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50, array(
				), 'speed'
            )->addColumn(
                'navigation',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                    ['nullable' => true,'default' => 0],
                    'navigation'
            )->addColumn(
                'pagercontrol',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => true,'default' => 0],
                'pagercontrol'
            )->addColumn(
                'lazyload',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' =>  true,'default' => 0],
                'lazyload'
            )->addColumn(
                'speed',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' =>  true,'default' => ''],
                'speed'
            )->addColumn(
                'autoplay_speed',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' =>  true,'default' => ''],
                'autoplay_speed'
            )->addColumn(
                'stop_on_hover',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable'  =>  true,'default' => ''],
                'stop_on_hover'
            )->addColumn(
                'auto_play',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default' => '0' ],
                'Auto Play'
            )->addColumn(
                'autoplay_speed',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    50,
                    array(
				), 'autoplay_speed'
            )->addColumn(
                'stop_on_hover',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default'  =>  '0' ],
                'stop_on_hover'
            )->addColumn(
                'loop',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default'  =>  '0' ],
                'Loop'
            )->addIndex(
                $setup->getIdxName('vicomage_slider_items', array('identity'), \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE),
                array('identity'),
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
