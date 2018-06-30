<?php

namespace Vicomage\Brand\Controller\Adminhtml\Brand;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportExcel extends \Vicomage\Brand\Controller\Adminhtml\Action
{
    public function execute()
    {
        $fileName = 'brands.xls';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Vicomage\Brand\Block\Adminhtml\Brand\Grid')->getExcel();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
