<?php
namespace Vicomage\Testimonial\Block;

class Testimonial extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	/**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    public $_sysCfg;
    protected $_imageFactory;
    protected $_testimonialCollectionFactory;
    protected $_testimonials = null;
    protected $_coreHelper;
    protected $_attribute = null;

    /**
     * Testimonial constructor.
     * @param \Vicomage\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $testimonialCollectionFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param array $data
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Vicomage\Core\Helper\Data $coreHelper,
        \Vicomage\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $testimonialCollectionFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        array $data = [],
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_coreHelper = $coreHelper;
        $this->_imageFactory = $imageFactory;
        $this->_testimonialCollectionFactory = $testimonialCollectionFactory;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $data = $this->getData();
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
        $this->addData($data);

        parent::_construct();

    }

    /**
     * @return $this|null
     */
    public function getTestimonials()
    {
        if(!$this->_testimonials){
            $store = $this->_storeManager->getStore()->getStoreId();
            $testimonials = $this->_testimonialCollectionFactory->create()
                ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                ->addFieldToFilter('status', 1);
            $this->_testimonials = $testimonials;
        }
        return $this->_testimonials;
    }

    /**
     * @param $object
     * @return string
     */
    public function getImage($object)
    {
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $object->getImage();
        return $resizedURL;
    }

    /**
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

    /**
     * @param $testimonial
     * @return null|string
     */
    public function getAvatarUrl($testimonial){
         $baseUrl = $this ->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
         if(!empty($testimonial->getAvatar())){
            return $baseUrl . $testimonial->getAvatar();
         }
         return null;
    }

    /**
     * @param $groupClass
     * @return string
     */
    public function getStyle($groupClass){
        return $this->_coreHelper->getStyle($this->getData(),$groupClass);
    }
}

