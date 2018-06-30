<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\General\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveColorSettings implements ObserverInterface
{
    protected $_messageManager;
    protected $_cssGenerator;

    /**
     * SaveColorSettings constructor.
     * @param \Vicomage\General\Helper\Cssconfig $cssConfig
     */
    public function __construct(
        \Vicomage\General\Helper\Cssconfig $cssConfig
    ) {
        $this->_cssGenerator = $cssConfig;
    }

    /**
     * Log out user and redirect to new admin custom url
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_cssGenerator->generateCss('design', $observer->getData("website"), $observer->getData("store"));
    }
}
