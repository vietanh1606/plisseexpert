<?php

namespace Vicomage\Brand\Controller\Adminhtml\Brand;

class Delete extends \Vicomage\Brand\Controller\Adminhtml\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('shopbrand_id');
        try {
            $item = $this->_shopbrandFactory->create()->setId($id);
            $item->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
