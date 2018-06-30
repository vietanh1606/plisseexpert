<?php

namespace Vicomage\Brand\Controller\Adminhtml\Brand;

class NewAction extends \Vicomage\Brand\Controller\Adminhtml\Action
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
