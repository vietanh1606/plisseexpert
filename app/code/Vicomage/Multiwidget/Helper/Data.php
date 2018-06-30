<?php
namespace Vicomage\Multiwidget\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_coreHelper;

    public function __construct(
        \Vicomage\Core\Helper\Data $coreHelper,
        Context $context
    )
    {
        $this->_coreHelper = $coreHelper;
        parent::__construct($context);
    }

    /**
     * @param $_product
     * @return string|void
     */
    public function getTimer($_product){
        $toDate = $_product->getSpecialToDate();
        if(!$toDate) return;

        if($_product->getPrice() < $_product->getSpecialPrice()) return;

        if($_product->getSpecialPrice() == 0 || $_product->getSpecialPrice() == "") return;

        $timer = strtotime($toDate) - strtotime("now");
        return '<div class="vicommage-count-down"><span class="countdown" data-timer="' .$timer. '"></span></div>';
    }
}
