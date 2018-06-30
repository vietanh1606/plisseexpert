<?php
namespace Vicomage\Testimonial\Controller\Adminhtml\Testimonial;


class Index extends \Vicomage\Testimonial\Controller\Adminhtml\Testimonial
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Testimonial'));
        $this->_view->renderLayout();
    }
}
