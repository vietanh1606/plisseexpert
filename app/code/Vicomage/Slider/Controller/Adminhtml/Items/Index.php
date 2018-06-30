<?php
/**
 * Copyright © 2015 Vicomage. All rights reserved.
 */

namespace Vicomage\Slider\Controller\Adminhtml\Items;

class Index extends \Vicomage\Slider\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vicomage_Slider::slider');
        $resultPage->getConfig()->getTitle()->prepend(__('Slider Manager'));
        $resultPage->addBreadcrumb(__('Vicomage'), __('Vicomage'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}
