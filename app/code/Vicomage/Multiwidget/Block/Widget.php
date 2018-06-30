<?php

namespace Vicomage\Multiwidget\Block;


class Widget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_categoryInstance;
    protected $_productType;
    protected $_productHelper;
    protected $_coreHelper;
    protected $_helper;
    protected $_tabs = array();
    protected $_catalogCategory;

    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Vicomage\Multiwidget\Model\Widget\ProductType $productType,
        \Vicomage\Multiwidget\Helper\Product $productHelper,
        \Vicomage\Core\Helper\Data $coreHelper,
        \Vicomage\Multiwidget\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->_categoryInstance = $categoryFactory->create();
        $this->_catalogCategory = $catalogCategory;
        $this->_productType = $productType;
        $this->_productHelper = $productHelper;
        $this->_coreHelper = $coreHelper;
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }


    protected function _construct()
    {


        $data = $this->getData();
        if(isset($data['widget_product_type'])){
            switch ($data['widget_product_type']) {
                case 'category':
                    $this->setTemplate($data['category_template']);
                    break;
                case 'product':
                    $this->setTemplate($data['product_template']);
                    break;
                default:
                    return false;
            }
        }
        if(isset($data['slide']) && $data['slide']){
            $reponsiveConfig = $this->_coreHelper->getConfigDevice();
            $responsive = [];
            foreach ($reponsiveConfig as $size => $screen) {
                $responsive[$size] = (int)$data[$screen];
            }
            $data['responsive'] = $responsive;
            $data['slides-To-Show'] = $data['visible'];
            $data['swipe-To-Slide'] = 'true';
            $data['vertical-Swiping'] = $data['vertical'];
        }
        if(isset($data['config_product'])){
            foreach (explode(',',$data['config_product']) as $config){
                $data[$config] = true;
            }
        }
        if(!isset($data['width-image'])){
            $data['width-image'] = 270;
        }
        if(!isset($data['height-image'])){
            $data['height-image'] = 344;
        }

        $this->addData($data);
        parent::_construct();
    }

    public function getAllTabs()
    {
        if (!$this->_tabs) {
            $data = $this->getData();
            if(isset($data['widget_product_type'])){
                switch ($data['widget_product_type']) {
                    case 'category':
                        $tabs = array();
                        $categoryIds = $this->getCategoryIds();
                        $types =  $this->_categoryInstance->getCollection()
                            ->addAttributeToFilter('entity_id', array('in' => $categoryIds))
                            ->addAttributeToSelect('name');
                        foreach ($types as $type) {
                            $tabs[$type->getEntityId()] = $type->getName();
                        }
                        if(!count($tabs)){
                            $types =  $this->_catalogCategory->getStoreCategories();
                            $maxTab = 5;
                            $i = 1;
                            foreach ($types as $type) {
                                $tabs[$type->getEntityId()] = $type->getName();
                                if($i == $maxTab) break;
                                $i++;
                            }
                        }
                        $this->_tabs = $tabs;
                        break;
                    case 'product':

                        $tabs = array();
                        $productSeleted = explode(',',$this->getProductTypeCollection());
                        $types = $this->_productType->toOptionArray();
                        //check tab if in config will set to array
                        foreach ($types as $type) {
                            if(in_array($type['value'], $productSeleted)) $tabs[$type['value']] = $type['label'];
                        }
                        $this->_tabs = $tabs;
                        break;
                    default:
                        return false;
                }
            }
        }
        return $this->_tabs;
    }


    public function getContentProduct($template)
    {
        $html = null;
        $tabs = ($this->getAjax()) ? array($this->getTabActivated() => 'Activated') : $this->getAllTabs();
        $data = $this->getData();
        if(isset($data['widget_product_type'])){
            switch ($data['widget_product_type']) {
                case 'category':

                    foreach ($tabs as $type => $name) {
                        $html .= $this->getLayout()->createBlock('Vicomage\Multiwidget\Block\Category\GridProduct')
                            ->setActivated($type)
                            ->setCfg($data)
                            ->setTemplate($template)
                            ->toHtml();
                    }
                    break;
                case 'product':

                    foreach ($tabs as $type => $name) {
                        $html .= $this->getLayout()->createBlock('Vicomage\Multiwidget\Block\Product\GridProduct')
                            ->setActivated($type)
                            ->setCfg($data)
                            ->setTemplate($template)
                            ->toHtml();
                    }
                    break;
                default:
                    return false;
            }

        }
        return $html;
    }

    /**
     * get tab active in config
     * @return mixed
     */
    public function getTabActivated()
    {
        $activated = null;
        if(isset($this->_tabs) && !empty($this->_tabs)) {
            $types = array_keys($this->_tabs);
            $activated = $types[0];
        }

        return $activated;
    }


    /**
     * function get ajax config
     * @return int|string
     */
    public function getAjaxCfg()
    {
        if(!$this->getAjax()) return 0;

        $ajax = array();
        foreach ($this->_coreHelper->getAjaxCfg() as $option) {
            if($option == 'conditions_encoded' && !empty($this->getData($option))){

                $ajax[$option] = $this->getData($option);
            }else if($option == 'conditions' && !empty($this->getData($option))){

                $ajax[$option] = $this->getData($option);
            }else {
                if($this->getData($option) !== null) {
                    $ajax[$option] = $this->getData($option);
                }
            }
        }
        return json_encode($ajax);
    }


    /**
     * get config
     * @return array
     */
    public function getFrontendCfg()
    {
        //check if config is slider will return slider option
        $dataConfig['slider'] = false;
        if ($this->getSlide()) {
            $dataConfig['slider'] = true;

            foreach ($this->_coreHelper->getSlideOptions() as $config => $defaultValue) {
                $value = $this->getData($config);
                if(!empty($value)) {
                    if (is_numeric($value)) {
                        $value = (int)$value;
                    } elseif ($value === 'true') {
                        $value = true;
                    } elseif ($value === 'false') {
                        $value = false;
                    }
                    $dataConfig[str_replace('-', '', $config)] = $value;
                }else{

                    if($defaultValue !== null) {
                        $dataConfig[str_replace('-', '', $config)] = $defaultValue;
                    }
                }
            }

            return json_encode($dataConfig);
        }else {

            foreach ($this->_coreHelper->getConfigJs() as $config) {
                $value = $this->getData($config);

                if (is_numeric($value)) {
                    $value = (int)$value;
                } elseif ($value === 'true') {
                    $value = true;
                } elseif ($value === 'false') {
                    $value = false;
                }
                $dataConfig[str_replace('-','',$config)] =  $value;
            }

            $this->addData(array('responsive' =>json_encode($this->getGridOptions())));
        }
        return json_encode($dataConfig);
    }


    /**
     * @return array
     */
    public function getGridOptions()
    {
        $options = array();
        $breakpoints = $this->_coreHelper->getConfigDevice();
        ksort($breakpoints);
        foreach ($breakpoints as $size => $screen) {
            $options[]= array($size => $this->getData($screen));
        }
        return $options;
    }


    /**
     * function get media url
     * @return mixed
     */
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * get css for list product when config no slider
     * @return string
     */
    public function getStyleNoSlider($groupClass){
        return $this->_coreHelper->getStyle($this->getData(),$groupClass);
    }

}
