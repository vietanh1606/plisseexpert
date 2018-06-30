<?php

namespace Vicomage\Brand\Model\System\Config;

class Brand implements \Magento\Framework\Option\ArrayInterface
{

    protected $_scopeConfig;
    protected $_options = array();

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository $_productAttributeRepository
     */
    protected $_productAttributeRepository;
    protected $shopbrand;
    protected $request;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        \Vicomage\Brand\Model\Shopbrand $shopbrand
    )
    {
        $this->request = $request;
        $this->shopbrand = $shopbrand;
        $this->_productAttributeRepository = $productAttributeRepository;
        $this->_scopeConfig= (object) $scopeConfig->getValue(
            'shopbrand',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if(!$this->_options){
            $options = array();
            $cfg = $this->_scopeConfig->general;
            if(isset($cfg['attributeCode'])){
                $brands = $this->_productAttributeRepository->get($cfg['attributeCode'])->getOptions();
                $allAttrBrand = $this->getAllAttrId();
                foreach ($brands as $brand) {
                    if(in_array($brand->getValue(),$allAttrBrand) == false) {
                        $options[$brand->getValue()] = $brand->getLabel();
                    }
                }
            }
            $this->_options = $options;
        }
        return $this->_options;
    }

    /**
     * get all attribute id
     * @return array
     */
    public function getAllAttrId(){
        $brandCollection = $this->shopbrand->getCollection();
        $brandCollection->addFieldToSelect('option_id');
        $attrId = array();
        $currentAttr = $this->getCurrentAttrBrand();
        foreach($brandCollection as $attr){
            if($attr->getData('option_id') && $currentAttr != $attr->getData('option_id')) {
                $attrId[$attr->getData('option_id')] = $attr->getData('option_id');
            }
        }
        return $attrId;
    }


    /**
     * get attribute id of current brand
     * @return null
     */
    public function getCurrentAttrBrand(){
        $brandId = $this->request->getParam('shopbrand_id');
        if($brandId) {
            $brandCollection = $this->shopbrand->getCollection();
            $brandCollection->addFieldToSelect('option_id')
                ->addFieldToFilter('shopbrand_id', array('eq' => $brandId));
            $result = $brandCollection->getData();
            if(isset($result)) {
                return $result[0]['option_id'];
            }
        }
        return null;
    }

}
