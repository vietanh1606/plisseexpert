<?php

namespace Vicomage\Multiwidget\Controller;

abstract class Ajax extends \Magento\Framework\App\Action\Action
{
    /**
     * productfilter factory.
     *
     * @var \Vicomage\Productfilter\Model\ProductfilterFactory
     */
    protected $_productfilterFactory;

    protected $_resultPageFactory;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context                                $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }
}
