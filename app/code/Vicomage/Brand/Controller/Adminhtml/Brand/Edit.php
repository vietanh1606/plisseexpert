<?php

namespace Vicomage\Brand\Controller\Adminhtml\Brand;

class Edit extends \Vicomage\Brand\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('shopbrand_id');
        $storeViewId = $this->getRequest()->getParam('store');
        $model = $this->_shopbrandFactory->create();

        if ($id) {
            $model->setStoreViewId($storeViewId)->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Brand no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('shopbrand', $model);

        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}
