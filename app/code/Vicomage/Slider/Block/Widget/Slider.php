<?php 
namespace Vicomage\Slider\Block\Widget;

use Magento\Framework\App\Filesystem\DirectoryList;

class Slider extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	
	
	protected $_modelitemsFactory;
	protected $_storeManager;
	protected $_filesystem;
	protected $_directory;
	protected $_imageFactory;
	protected $_coreRegistry;
	protected $jsonEncoder;
	protected $mediaHelper;

    /**
     * Slider constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param \Vicomage\Slider\Model\Resource\Items\CollectionFactory $modelNewsFactory
     * @param array $data
     */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\Image\AdapterFactory $imageFactory,		
        \Vicomage\Slider\Model\Resource\Items\CollectionFactory  $modelNewsFactory,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\ProductVideo\Helper\Media $media,
        array $data = []
    ) {
		
        $this->_modelitemsFactory = $modelNewsFactory;
		$this->_filesystem = $context->getFilesystem();
		$this->_storeManager = $context->getStoreManager();
		$this->_directory = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
		$this->_imageFactory = $imageFactory;			
		$this->_coreRegistry = $coreRegistry;
		$this->jsonEncoder = $jsonEncoder;
		$this->mediaHelper = $media;
        parent::__construct(
            $context,
            $data
        );
    }


    /**
     * @param $name
     * @param $w
     * @param $h
     * @return string
     */
    public function imageResize($name,$w,$h){

        $image=$name;

        $absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('vicomage/slider').$image;

        $imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('vicomage/slider/resized').$image;

        if(file_exists($absPath) && is_file($absPath)) {
            $imageResize = $this->_imageFactory->create();

            $imageResize->open($absPath);

            $imageResize->constrainOnly(TRUE);

            $imageResize->keepTransparency(TRUE);

            if ($w != "" && $h != "") {
                $imageResize->resize($w, $h);
            } else {
                $imageResize = $imageResize;
            }
            $dest = $imageResized;

            $imageResize->save($dest);

            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'vicomage/slider/resized' . $image;
        }
        return null;
    }


    /**
     * @param $id
     * @return array|string
     */
	public function getSlider($id)
	{
		$newsModel = $this->_modelitemsFactory->create()->addFieldToFilter('identity',$id);
		$option = array();

		foreach ($newsModel as $slider) {
            $option	= [
                'status' => $slider->getData('status'),
                'number' => $slider->getNumber(),
                'slider' => $slider->getData("slider_params"),
                'id'	 => $slider->getIdentity(),
                'w'		 => $slider->getwidth(),
                'h' 	 => $slider->getHeight(),
                'auto_play' 	 => $slider->getData('auto_play'),
                'loop' 	 => $slider->getData('loop'),
                'autoplay_speed' 	 => $slider->getData('autoplay_speed'),
                'navigation' 	 => $slider->getData('navigation'),
                'pagercontrol' 	 => $slider->getData('pagercontrol'),
                'lazyload' 	 => $slider->getData('lazyload'),
                'speed' 	 => $slider->getData('speed'),
                'stop_on_hover' 	 => $slider->getData('stop_on_hover'),
            ];
		}
		 return $option;
	}
	
	public function _toHtml()
    {
		$this->setTemplate('Vicomage_Slider::widget/slider_widget.phtml');
		return parent::_toHtml();
    }
}