<?php

namespace Vicomage\Quickview\Plugin;

class BlockProductList
{
    const XML_PATH_QUICKVIEW_ENABLED = 'vicomage_quickview/general/enable_product_listing';
    const XML_PATH_QUICKVIEW_BUTTONSTYLE = 'vicomage_quickview/general/button_style';
    
    
    /**
     * @var \Magento\Framework\UrlInterface 
     */
    protected $urlInterface;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface 
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        ) {
        $this->urlInterface = $urlInterface;
        $this->scopeConfig = $scopeConfig;
    }

    public function aroundGetProductDetailsHtml(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product
        )
    {
        $result = $proceed($product);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
           /*  $buttonStyle =  'vicomage_quickview_button_' . $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONSTYLE,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $productUrl = $this->urlInterface->getUrl('vicomage_quickview/catalog_product/view', array('id' => $product->getId()));
            return $result . '<a class="vicomage-quickview '.$buttonStyle.'" data-quickview-url=' . $productUrl . ' href="javascript:void(0);"><span>' . __("Quickview") . '</span></a>'; */
        }
        
        return $result;
    }
}
