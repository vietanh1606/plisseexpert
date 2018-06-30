<?php

namespace Vicomage\General\Block\Adminhtml\System\Config;

class Color extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */

    /**
     * @var \Magento\Directory\Block\Data
     */
    protected $_directoryBlock;

    /**
     * @var \Vicomage\General\Helper\Data
     */
    protected $helper;

    protected $serialize;

    protected $store = null;

    /**
     * Color constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Vicomage\General\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Directory\Block\Data $directoryBlock
     * @param array $data
     */
    public function __construct(
        \Vicomage\General\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Block\Data $directoryBlock,
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->_directoryBlock = $directoryBlock;
        $this->serialize = $serialize;
        $this->store = $this->getData('store');
    }

    /**
     * @return mixed
     */
    public function getBaseColor()
    {
        $color = $this->helper->getBaseColor($this->store);
        if ($color) {
            return $this->serialize->unserialize($color);
        }
        return false;
    }


    /**
     * @return mixed
     */
    public function getHeaderColor()
    {
        $color = $this->helper->getHeaderColor($this->store);
        if ($color) {
            return $this->serialize->unserialize($color);
        }
        return false;
    }
    
    /**
     * @return mixed
     */
    public function getContentColor()
    {
        $color = $this->helper->getContentColor($this->store);
        if ($color) {
            return $this->serialize->unserialize($color);
        }
        return false;
    }
    

    /**
     * @return mixed
     */
    public function getFooterColor()
    {
        $color = $this->helper->getFooterColor($this->store);
        if ($color) {
            return $this->serialize->unserialize($color);
        }
        return false;
    }
    
    public function getCssConfigUrl()
    {
        $storeManage = $this->_storeManager;
        $currentStore = $storeManage->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $cssUrl = $mediaUrl . 'vicomage/css_config/design_' . $currentStore->getCode() .'.css';
        return $cssUrl;
    }
}