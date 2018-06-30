<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\General\Helper;

class Cssconfig extends \Magento\Framework\App\Helper\AbstractHelper
{
    CONST CSS_CONFIG_PATH = 'vicomage/css_config/';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var string
     */
    protected $generatedCssDir;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layoutManager;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * Cssconfig constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\LayoutInterface $layoutManager
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\LayoutInterface $layoutManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->_storeManager = $storeManager;
        $this->_layoutManager = $layoutManager;
        $this->_messageManager = $messageManager;
        $this->_coreRegistry = $coreRegistry;
        $this->_directoryList = $directoryList;
        $this->generatedCssDir = $this->_directoryList->getPath('pub') . '/media/' . self::CSS_CONFIG_PATH;

        parent::__construct($context);
    }

    /**
     * Get Base Media Url
     *
     * @return mixed
     */
    public function getBaseMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Get Css folder path
     *
     * @return string
     */
    public function getCssConfigDir()
    {
        return $this->generatedCssDir;
    }

    public function generateCss($type, $websiteId, $storeId)
    {
        if (!$websiteId && !$storeId) {
            $websites = $this->_storeManager->getWebsites(false, false);
            foreach ($websites as $id => $value) {
                $this->generateWebsiteCss($type, $id);
            }
        } else {
            if ($storeId) {
                $this->generateStoreCss($type, $storeId);
            } else {
                $this->generateWebsiteCss($type, $websiteId);
            }
        }
    }

    protected function generateWebsiteCss($type, $websiteId)
    {
        $website = $this->_storeManager->getWebsite($websiteId);
        foreach ($website->getStoreIds() as $storeId) {
            $this->generateStoreCss($type, $storeId);
        }
    }

    protected function generateStoreCss($type, $storeId)
    {
        $store = $this->_storeManager->getStore($storeId);
        if (!$store->isActive()) {
            return;
        }
        $storeCode = $store->getCode();
        $str1 = '_' . $storeCode;
        $str2 = $type . $str1 . '.css';
        $cssFilePath = $this->getCssConfigDir() . $str2;
        $template = 'css/' . $type . '.phtml';
        $this->_coreRegistry->register('cssgen_store', $storeCode);

        try {
            $block = $this->_layoutManager->createBlock(
                'Vicomage\General\Block\Adminhtml\System\Config\Color',
                '',
                [
                    'data' => ['store' => $store]
                ]
            )->setData('area', 'frontend')->setTemplate($template)->toHtml();
            if (!file_exists($this->getCssConfigDir())) {
                @mkdir($this->getCssConfigDir(), 0777);
            }
            if (file_exists($cssFilePath)) {
                unlink($cssFilePath);
            }
            $file = @fopen($cssFilePath, "w+");
            @flock($file, LOCK_EX);
            @fwrite($file, $block);
            @flock($file, LOCK_UN);
            @fclose($file);
            if (empty($block)) {
                throw new \Exception(__("Template file is empty or doesn't exist: " . $str2));
            }
        } catch (\Exception $e) {
            $this->_messageManager->addError(__('Failed generating CSS file: ' . $str2 . ' in ' . $this->getCssConfigDir()) . '<br/>Message: ' . $e->getMessage());
        }
        $this->_coreRegistry->unregister('cssgen_store');
    }
}
