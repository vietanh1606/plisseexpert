<?php

namespace Vicomage\SearchBox\Controller\Index;

class Ajax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\ViewInterface
     */
    protected $_view;

    /**
     * Ajax constructor.
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->_view = $context->getView();
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $block = $this->_view->getLayout()->createBlock('Vicomage\SearchBox\Block\SearchBox');
        header('content-type: text/javascript');
        echo '{"htm":' . json_encode($block->toHtml()) . '}';
        die();
    }
}