<?php

namespace Vicomage\Brand\Controller\Adminhtml\Brand;

class MassStatus extends \Vicomage\Brand\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $shopbrandIds = $this->getRequest()->getParam('shopbrand');
        $status = $this->getRequest()->getParam('status');
        $storeViewId = $this->getRequest()->getParam('store');
        if (!is_array($shopbrandIds) || empty($shopbrandIds)) {
            $this->messageManager->addError(__('Please select Brand(s).'));
        } else {
            $collection = $this->_shopbrandCollectionFactory->create()
                // ->setStoreViewId($storeViewId)
                ->addFieldToFilter('shopbrand_id', ['in' => $shopbrandIds]);
            try {
                foreach ($collection as $item) {
                    $item->setStoreViewId($storeViewId)
                        ->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($shopbrandIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/', ['store' => $this->getRequest()->getParam('store')]);
    }
}
