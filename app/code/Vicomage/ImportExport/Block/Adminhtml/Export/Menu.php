<?php

namespace Vicomage\ImportExport\Block\Adminhtml\Export;

use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

class Menu extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Vicomage\Megamenu\Model\Items
     */
    protected $_itemsModel;

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Cms\Model\Page $cmsPage
     * @param \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $collectionFactory
     * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Vicomage\Megamenu\Model\Items $itemsModel,
        \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory $collectionFactory,
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
        $this->setId('megaMenuGrid');
        $this->setDefaultSort('item_id');
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
            'item_id',
            [
                'header' => __('Id'),
                'index' => 'item_id',
                'type'  => 'number'
            ]
        );


        $this->addColumn(
            'fake_name',
            [
                'header' => __('Label'),
                'index' => 'fake_name',
            ]
        );


        $this->addColumn(
            'menu_type',
            [
                'header' => __('Menu Type'),
                'index' => 'menu_type',
                'type' => 'options',
                'options' => ['1' => __('Category'), '2' => __('Static Link')],
            ]
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn(
                'store',
                [
                    'header' => __('Store View'),
                    'index' => 'store',
                    'type' => 'store',
                    'store_all' => true,
                    'store_view' => true,
                    'sortable' => false,
                    'filter_condition_callback' => [$this, '_filterStoreCondition']
                ]
            );
        }

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
        return $this->getUrl('megamenu/items/edit', ['item_id' => $row->getId()]);
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
			'url'      => $this->getUrl('*/*/menu'),
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
