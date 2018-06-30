<?php

namespace Vicomage\Brand\Block\Widget;


class Brand extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

    public $_sysCfg;
    protected $_imageFactory;
    protected $_brandCollectionFactory;
    protected $_brands = array();
    protected $_attribute = array();
    protected $_coreHelper;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository $_productAttributeRepository
     */
    protected $_productAttributeRepository;

    public function __construct(
        \Vicomage\Core\Helper\Data $coreHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        \Vicomage\Brand\Model\ResourceModel\Shopbrand\CollectionFactory $brandCollectionFactory,
        array $data = []
    ) {
        $this->_coreHelper = $coreHelper;
        $this->_imageFactory = $imageFactory;
        $this->_brandCollectionFactory = $brandCollectionFactory;
        $this->_productAttributeRepository = $productAttributeRepository;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {

        $this->_sysCfg = (object) $this->_scopeConfig->getValue('vicomage_brand', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $dataConfig = $this->_sysCfg->general;
        $data = array_merge($dataConfig,$this->getData());
		if(isset($data['slide']) && $data['slide']){

            $data['vertical-Swiping'] = $data['vertical'];
            $reponsiveConfig = $this->_coreHelper->getConfigDevice();
            $responsive = [];
            foreach ($reponsiveConfig as $size => $screen) {
                $responsive[$size] = $data[$screen];
            }

            $data['slides-To-Show'] = $data['visible'];
            $data['swipe-To-Slide'] = 'true';
			$data['responsive'] = $responsive;
		}

        $this->addData($data);

        parent::_construct();

    }


    public function getBrands()
    {
        if(!$this->_brands){
            $store = $this->_storeManager->getStore()->getStoreId();
            $brands = $this->_brandCollectionFactory->create()
                        ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                        ->addFieldToFilter('status', 1);
            $this->_brands = $brands;
        }
        return $this->_brands;
    }


    public function getUrlBrand($brand)
    {
        $typeLink = $this->getData('link');
        $baseUrl  = $this->_storeManager->getStore()->getBaseUrl();
        $attrCode = $this->getData('attributeCode');
        $link = '#';
        if($typeLink == 1){
            $link = $brand->getUrlkey() ? $baseUrl . $brand->getUrlkey() : '#';
        }else{
            $attr = $this->getAttribute();
            if ($attr->usesSource()) {
                $option  = $attr->getSource()->getOptionText($brand->getOptionId());
                if($typeLink == '2') {

                    $link = $baseUrl . 'catalogsearch/result/?q=' . $option;
                }elseif($typeLink == '3') {

                    $link = $baseUrl . 'catalogsearch/advanced/result/?' . $attrCode . urlencode('[]') . '=' . $option;
                }
            }
        }
        return $link;
    }


    public function getAttribute()
    {
        if (!$this->_attribute) {
            $attr = $this->getData('attributeCode');
            $this->_attribute = $this->_productAttributeRepository->get($attr);
        }
        return $this->_attribute;
    }


    public function getImage($object)
    {

        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $object->getImage();
        return $resizedURL;
    }


    /**
     * get config slider data config
     * @return string
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
        $breakpoints = $this->_coreHelper->getConfigDevice(); ksort($breakpoints);
        foreach ($breakpoints as $size => $screen) {
            $options[]= array($size-1 => $this->getData($screen));
        }
        return $options;
    }

}
