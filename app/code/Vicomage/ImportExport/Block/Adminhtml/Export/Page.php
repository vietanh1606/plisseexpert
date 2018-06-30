<?php

namespace Vicomage\ImportExport\Block\Adminhtml\Export;

use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

class Page extends \Magento\Cms\Block\Adminhtml\Page\Grid
{

    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->removeColumn('edit');
        $this->_exportTypes = [];
    }

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('entity_id');
		$this->getMassactionBlock()->setFormFieldName('exportIds');

        $themesCollections = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Theme\Model\Theme\Collection');
		$themesCollections->addConstraint(Collection::CONSTRAINT_AREA, Area::AREA_FRONTEND);
		$themes = [];
		foreach ($themesCollections as $key => $value) {
			$themes[$value->getData('theme_path')] = $value->getData('theme_title');
		}
		$this->getMassactionBlock()->addItem('export', array(
			'label'    => __('Export'),
			'url'      => $this->getUrl('*/*/page'),
			'additional' => array(
				'visibility' => array(
					'name' => 'theme_path',
					'type' => 'select',
					'class' => 'required-entry',
					'label' => __('Theme'),
					'values' => $themes //$stores
				)
			),
			'confirm'  => __('Are you sure?')
		));
		return $this;
	}

}
