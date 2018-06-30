<?php

namespace Vicomage\ImportExport\Controller\Adminhtml\Export;

class System extends \Vicomage\ImportExport\Controller\Adminhtml\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        return $resultPage;
    }
}
