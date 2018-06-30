<?php

namespace Vicomage\ImportExport\Block\Adminhtml\Export;

use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

class Slider extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Vicomage\Slider\Model\Resource\Items\Collection
     */
    protected $_collectionFactory;

    /**
     * @var \Vicomage\Megamenu\Model\Items|\Vicomage\Slider\Model\Items
     */
    protected $_itemsModel;

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * Slider constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Vicomage\Megamenu\Model\Items $itemsModel
     * @param \Vicomage\Slider\Model\Resource\Items\Collection $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Vicomage\Slider\Model\Items $itemsModel,
        \Vicomage\Slider\Model\Resource\Items\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_itemsModel = $itemsModel;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {

        $collection = $this->_collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'id',
            [
                'header' => __('Id'),
                'index' => 'id',
                'type'  => 'number'
            ]
        );


        $this->addColumn(
            'name',
            [
                'header' => __('Title'),
                'index' => 'name',
            ]
        );


        $this->addColumn(
            'identity',
            [
                'header' => __('Identity'),
                'index' => 'identity',
            ]
        );

		$this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * After load collection
     *
     * @return void
     */
    protected function _afterLoadCollection()
    {

        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Filter store condition
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @param \Magento\Framework\DataObject $column
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _filterStoreCondition($collection, \Magento\Framework\DataObject $column)
    {
        if (!($value = $column->getFilter()->getValue())) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('vicomage_slider/items/edit', ['id' => $row->getId()]);
    }

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('exportIds');

        $themesCollections = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Theme\Model\Theme\Collection');
		$themesCollections->addConstraint(Collection::CONSTRAINT_AREA, Area::AREA_FRONTEND);
		$themes = [];
		foreach ($themesCollections as $key => $value) {
			$themes[$value->getData('theme_path')] = $value->getData('theme_title');
		}
		$this->getMassactionBlock()->addItem('export', array(
			'label'    => __('Export'),
			'url'      => $this->getUrl('*/*/slider'),
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
