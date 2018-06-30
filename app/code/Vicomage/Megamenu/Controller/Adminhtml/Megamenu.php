<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Controller\Adminhtml;

abstract class Megamenu extends \Magento\Backend\App\Action
{
    /**
     * Init actions
     *
     * @return $this
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'Vicomage_Megamenu::megamenu_manage'
        )->_addBreadcrumb(
            __('Megamenu'),
            __('Megamenu')
        );
        return $this;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
//    protected function _isAllowed()
//    {
//        return $this->_authorization->isAllowed('Vicomage_Megamenu::megamenu');
//    }
}
