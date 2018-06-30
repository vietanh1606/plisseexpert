<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\General\Helper;

use Magento\Framework\Registry;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{



    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var Registry
     */
    private $_registry;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_productModel;


    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Catalog\Model\Product $productModel
     * @param Registry $registry
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Catalog\Model\Product $productModel,
        Registry $registry
    ) {
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
        $this->_filterProvider = $filterProvider;
        $this->_registry = $registry;
        $this->_productModel = $productModel;
        parent::__construct($context);
    }

    /**
     * Get base store url
     *
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Get store configuration
     *
     * @param string $configPath
     * @return mixed
     */
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
	
	 public function getModel($model) {
        return $this->_objectManager->create($model);
    }
	
    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getCurrentStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * @param string $content
     * @return string
     * @throws \Exception
     */
    public function filterContent($content)
    {
        return $this->_filterProvider->getPageFilter()->filter($content);
    }

    /**
     * @param \Magento\Catalog\Model\Category $currentCategory
     * @return mixed
     */
    public function getCategoryProductIds($currentCategory)
    {
        $categoryProducts = $currentCategory->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_saleable', 1, 'left')
            ->addAttributeToSort('position', 'asc');
        $catProdIds = $categoryProducts->getAllIds();

        return $catProdIds;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function getPrevProduct($product)
    {
        $currentCategory = $product->getCategory();
        if (!$currentCategory) {
            foreach ($product->getCategoryCollection() as $parentCat) {
                $currentCategory = $parentCat;
            }
        }
        if (!$currentCategory) {
            return false;
        }
        $catProdIds = $this->getCategoryProductIds($currentCategory);
        $_pos = array_search($product->getId(), $catProdIds);
        if (isset($catProdIds[$_pos - 1])) {
            $prevProduct = $this->getModel('Magento\Catalog\Model\Product')->load($catProdIds[$_pos - 1]);
            return $prevProduct;
        }
        return false;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function getNextProduct($product)
    {
        $currentCategory = $product->getCategory();
        if (!$currentCategory) {
            foreach ($product->getCategoryCollection() as $parentCat) {
                $currentCategory = $parentCat;
            }
        }
        if (!$currentCategory) {
            return false;
        }
        $catProdIds = $this->getCategoryProductIds($currentCategory);
        $_pos = array_search($product->getId(), $catProdIds);
        if (isset($catProdIds[$_pos + 1])) {
            $nextProduct = $this->getModel('Magento\Catalog\Model\Product')->load($catProdIds[$_pos + 1]);
            return $nextProduct;
        }
        return false;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getProductLabel($product)
    {
        $html = '';
        $productLabelConfig = $this->getConfig('vicomage_general_config/product_label');
		$class = '';
        // Sale label
        if ($productLabelConfig['sale_label']) {
            $orgprice = $product->getPrice();
            $specialprice = $product->getSpecialPrice();
            if (!$specialprice) {
                $specialprice = $orgprice;
            }
            if ($specialprice < $orgprice) {
				$class = 'new-sale';
				if ($productLabelConfig['sale_label_percent']) {
					$savePercent = 100 - round(($specialprice / $orgprice) * 100);
					$html .= '<div class="product-label sale-label"><span>' . '-' . $savePercent . '%' . '</span></div>';
				} else {
					$html .= '<div class="product-label sale-label"><span>' . $productLabelConfig['sale_label_text'] . '</span></div>';
				}
            }
        }

        // New label
        if ($productLabelConfig['new_label']) {
            $now = date("Y-m-d");
            $newsFrom = substr($product->getData('news_from_date'), 0, 10);
            $newsTo = substr($product->getData('news_to_date'), 0, 10);
            if ($newsTo != '' || $newsFrom != '') {
                if (($newsTo != '' && $newsFrom != '' && $now >= $newsFrom && $now <= $newsTo)
                    || ($newsTo == '' && $now >= $newsFrom)
                    || ($newsFrom == '' && $now <= $newsTo)
                ) {
                    $html .= '<div class="product-label new-label ' . $class . '"><span>' . $productLabelConfig['new_label_text'] . '</span></div>';
                }
            }
        }
        return $html;
    }


    /**
     * @param $store null|\Magento\Store\Api\Data\StoreInterface
     *
     * @return string
     */
    public function getBaseColor($store = null)
    {
        $enable = $this->scopeConfig->getValue('vicomage_color_config/config_color_base/enable_base_color',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($enable) {
            return $this->scopeConfig->getValue('vicomage_color_config/config_color_base/base_color',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store);
        }
        return false;
    }


    /**
     * @param $store null|\Magento\Store\Api\Data\StoreInterface
     *
     * @return string
     */
    public function getHeaderColor($store = null)
    {
        $enable = $this->scopeConfig->getValue('vicomage_color_config/config_color_header/enable_header_color',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($enable) {
            return $this->scopeConfig->getValue('vicomage_color_config/config_color_header/header_color',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store);
        }
        return false;
    }


    /**
     * @param $store null|\Magento\Store\Api\Data\StoreInterface
     *
     * @return mixed
     */
    public function getContentColor($store = null)
    {
        $enable = $this->scopeConfig->getValue('vicomage_color_config/config_color_content/enable_content_color',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($enable) {
            return $this->scopeConfig->getValue('vicomage_color_config/config_color_content/content_color',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store);
        }
        return false;
    }


    /**
     * @param $store null|\Magento\Store\Api\Data\StoreInterface
     *
     * @return string
     */
    public function getFooterColor($store = null)
    {
        $enable = $this->scopeConfig->getValue('vicomage_color_config/config_color_footer/enable_footer_color',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($enable) {
            return $this->scopeConfig->getValue('vicomage_color_config/config_color_footer/footer_color',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store);
        }
        return false;
    }

}

