<?php

namespace Vicomage\Brand\Controller\Adminhtml\Brand;

class Grid extends \Vicomage\Brand\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();

        return $resultLayout;
    }
}
