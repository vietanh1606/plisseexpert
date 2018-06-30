<?php
namespace Vicomage\Quickview\Block;

/**
 * Quickview Initialize block
 */
class Initialize extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Vicomage\QuickView\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Vicomage\Quickview\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Vicomage\Quickview\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
                                array $data = [])
    {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Returns config
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'baseUrl' => $this->getBaseUrl(),
            'showMiniCart' => $this->_helper->getScrollAndOpenMiniCart(),
            'showShoppingCheckoutButtons' => $this->_helper->getShoppingCheckoutButtons()
        ];
    }

    /**
     * Return base url.
     *
     * @codeCoverageIgnore
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }


    public function getConfigView(){
        $data =  array(
            'enableProductListing'  =>  $this->_scopeConfig->getValue('vicomage_quickview/general/enable_product_listing', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'scrollToTop'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/scroll_to_top', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'enableShoppingCheckoutProductButtons'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/enable_shopping_checkout_product_buttons', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'enableZoom'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/enable_zoom', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'zoomFullscreenzoom'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/zoom_fullscreenzoom', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'zoomTop'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/zoom_top', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'zoomLeft'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/zoom_left', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'zoomWidth'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/zoom_width', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'zoomHeight'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/zoom_height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'zoomEventtype'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/zoom_eventtype', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'autoSize'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/auto_size', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'popupWidth'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/popup_width', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'popupHeight'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/popup_height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'displayOverlay'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/display_overlay', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'openEffect'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/open_effect', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'closeEffect'    =>  $this->_scopeConfig->getValue('vicomage_quickview/general/close_effect', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
        );

        $dataResult = [];
        foreach ($data as $key => $value) {

            if (is_numeric($value)) {

                $value = (int)$value;
            } elseif ($value === 'true') {

                $value = true;
            } elseif ($value === 'false') {

                $value = false;
            }
            $dataResult[$key] =  $value;
        }

        return json_encode($dataResult);

    }
}
