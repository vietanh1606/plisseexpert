<?php

namespace Vicomage\Blog\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_coreHelper;

    public function __construct(
        \Vicomage\Core\Helper\Data $coreHelper,
        Context $context)
    {
        $this->_coreHelper = $coreHelper;
        parent::__construct($context);
    }

}
