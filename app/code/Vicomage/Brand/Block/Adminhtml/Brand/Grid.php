<?php

namespace Vicomage\Brand\Block\Adminhtml\Brand;

use Vicomage\Brand\Model\Status;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * shopbrand collection factory.
     *
     * @var \Vicomage\Brand\Model\ResourceModel\Shopbrand\CollectionFactory
     */
    protected $_shopbrandCollectionFactory;


    /**
     * construct.
     *
     * @param \Magento\Backend\Block\Template\Context                         $context
     * @param \Magento\Backend\Helper\Data                                    $backendHelper
     * @param \Vicomage\Brand\Model\ResourceModel\Shopbrand\CollectionFactory $shopbrandCollectionFactory
     * @param array                                                           $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Vicomage\Brand\Model\ResourceModel\Shopbrand\CollectionFactory $shopbrandCollectionFactory,
    
        array $data = []
    ) {
        $this->_shopbrandCollectionFactory = $shopbrandCollectionFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('shopbrandGrid');
        $this->setDefaultSort('shopbrand_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $store = $this->getRequest()->getParam('store');
        $collection = $this->_shopbrandCollectionFactory->create();
        $collection->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'type' => 'text',
                'index' => 'title',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
            ]
        );

        $this->addColumn(
            'urlkey',
            [
                'header' => __('URL key'),
                'type' => 'text',
                'index' => 'urlkey',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
            ]
        );

        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'class' => 'xxx',
                'width' => '50px',
                'filter' => false,
                'renderer' => 'Vicomage\Brand\Block\Adminhtml\Helper\Renderer\Grid\Image',
            ]
        );

        $this->addColumn(
            'order',
            [
                'header' => __('Order'),
                'type' => 'text',
                'index' => 'order',
                'header_css_class' => 'col-order',
                'column_css_class' => 'col-order',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => Status::getAvailableStatuses(),
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => ['base' => '*/*/edit'],
                        'field' => 'shopbrand_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * get brand vailable option
     *
     * @return array
     */

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('shopbrand');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('shopbrand/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $statuses = Status::getAvailableStatuses();

        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('shopbrand/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses,
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            ['shopbrand_id' => $row->getId()]
        );
    }
}
