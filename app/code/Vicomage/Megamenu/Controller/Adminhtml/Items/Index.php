<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Controller\Adminhtml\Items;

use Magento\Backend\App\Action;

class Index extends \Vicomage\Megamenu\Controller\Adminhtml\Megamenu
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Megamenu'));
        $this->_view->renderLayout();
    }
}
