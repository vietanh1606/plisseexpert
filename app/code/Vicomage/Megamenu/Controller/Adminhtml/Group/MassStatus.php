<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Controller\Adminhtml\Group;

use Magento\Backend\App\Action;

class MassStatus extends \Vicomage\Megamenu\Controller\Adminhtml\Megamenu
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $ids = $this->getRequest()->getPost('ids');
        if (!is_array($ids)) {
            $this->messageManager->addError(__('Please select group(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = $this->_objectManager->create('Vicomage\Megamenu\Model\Group')
                        ->load($id)
                        ->setStatus($this->getRequest()->getPost('status'))
                        ->save();
                }
                $this->messageManager->addSuccess(__('Total of %1 record(s) were successfully updated.', count($ids)));

            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
