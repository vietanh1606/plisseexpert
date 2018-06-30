<?php

namespace Vicomage\Brand\Controller\Adminhtml\Brand;

class MassDelete extends \Vicomage\Brand\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $shopbrandIds = $this->getRequest()->getParam('shopbrand');
        if (!is_array($shopbrandIds) || empty($shopbrandIds)) {
            $this->messageManager->addError(__('Please select shopbrand(s).'));
        } else {
            $collection = $this->_shopbrandCollectionFactory->create()
                ->addFieldToFilter('shopbrand_id', ['in' => $shopbrandIds]);
            try {
                foreach ($collection as $item) {
                    $item->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($shopbrandIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
