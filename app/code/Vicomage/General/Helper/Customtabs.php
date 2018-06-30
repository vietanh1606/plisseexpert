<?php
namespace Vicomage\General\Helper;

class Customtabs extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * Customtabs constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\Model\BlockFactory $blockFactory
    ) {

        $this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
        $this->_storeManager = $storeManager;

        parent::__construct($context);
    }

    /**
     * @param string $configPath
     * @return string
     */
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param array $a
     * @param string $subkey
     * @return array
     */
    public function subval_sort($a, $subkey)
    {
        foreach ($a as $k => $v) {
            $b[$k] = strtolower($v[$subkey]);
        }
        asort($b);
        foreach ($b as $key => $val) {
            $c[] = $a[$key];
        }
        return $c;
    }

    /**
     * @param string $tabCatIds
     * @param string $parentCatIds
     * @param string $tabProdSkus
     * @param string $prodSku
     * @return bool
     */
    public function checkShowingTab($tabCatIds, $parentCatIds, $tabProdSkus, $prodSku)
    {
        if (!$tabCatIds && !$tabProdSkus) {
            return true;
        }
        $tabCatIds = explode(",", $tabCatIds);
        $tabProdSkus = explode(",", $tabProdSkus);
        if (count($tabProdSkus) > 0 && count($tabCatIds) > 0) {
            if (in_array($prodSku, $tabProdSkus) || count(array_intersect($tabCatIds, $parentCatIds)) > 0) {
                return true;
            }
        }
        if (count($tabProdSkus) > 0 && in_array($prodSku, $tabProdSkus)) {
            return true;
        }
        if (count($tabCatIds) > 0 && count(array_intersect($tabCatIds, $parentCatIds)) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $content
     * @return string
     * @throws \Exception
     */
    public function getBlockContent($content = '')
    {
        if (!$this->_filterProvider) {
            return $content;
        }
        return $this->_filterProvider->getBlockFilter()->filter(trim($content));
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getCustomTabs($product)
    {
        $cmsTabs = $this->getConfig('vicomage_general_config/product/custom_cms_tabs');
        $attrTabs = $this->getConfig('vicomage_general_config/product/custom_attr_tabs');
        $_sku = $product->getSku();
        if ($cmsTabs) {
            $cmsTabs = unserialize($cmsTabs);
        }
        if ($attrTabs) {
            $attrTabs = unserialize($attrTabs);
        }

        $parents = [];
        if (count($cmsTabs) > 0 || count($attrTabs) > 0) {
            foreach ($product->getCategoryCollection() as $parentCat) {
                $parents[] = $parentCat->getId();
            }
        }
        $storeId = $this->_storeManager->getStore()->getId();
        $customTabs = [];
        if (count($cmsTabs) > 0) {
            foreach ($cmsTabs as $_item) {
                if ($this->checkShowingTab($_item['category_ids'], $parents, $_item['product_skus'], $_sku)) {
                    $blockId = $_item['staticblock_id'];
                    if (!$blockId) {
                        continue;
                    }
                    $block = $this->_blockFactory->create();
                    $block->setStoreId($storeId)->load($blockId);

                    if (!$block) {
                        continue;
                    }

                    $blockContent = $block->getContent();

                    if (!$blockContent) {
                        continue;
                    }

                    $content = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($blockContent);
                    $arr = [];
                    $arr['tab_title'] = $_item['tab_title'];
                    $arr['tab_content'] = $content;
                    $arr['sort_order'] = (!$_item['sort_order'] || !is_numeric($_item['sort_order'])) 
                        ? 0 : $_item['sort_order'];
                    $customTabs[] = $arr;
                }
            }
        }
        if (count($attrTabs) > 0) {
            foreach ($attrTabs as $_item) {
                if ($this->checkShowingTab($_item['category_ids'], $parents, $_item['product_skus'], $_sku)) {
                    $attrCode = $_item['attribute_code'];

                    $attribute = $product->getResource()->getAttribute($attrCode);
                    if (!$attribute) {
                        continue;
                    }
                    $attrValue = $attribute->getFrontend()->getValue($product);
                    if (!$attrValue) {
                        continue;
                    }

                    $content = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($attrValue);
                    $arr = [];
                    $arr['tab_title'] = $_item['tab_title'];
                    $arr['tab_content'] = $content;
                    $arr['sort_order'] = (!$_item['sort_order'] || !is_numeric($_item['sort_order']))
                        ? 0 : $_item['sort_order'];
                    $customTabs[] = $arr;
                }
            }
        }
        if (count($customTabs) > 0) {
            $customTabs = $this->subval_sort($customTabs, 'sort_order');
        }

        return $customTabs;
    }
}
