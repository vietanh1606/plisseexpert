<?php
namespace Vicomage\Slider\Block\Adminhtml\Form\Renderer;
use Magento\Framework\App\Filesystem\DirectoryList;

class Customfield extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
       \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    protected $_element;
    protected $_template = 'Vicomage_Slider::renderer/form/customfield.phtml';

    public $val=0;

    public function getTitle()
    {
        return "Foo Bar Baz";
    }

    public function setId($id)
    {
        $this->val=$id;
    }

    public function getId()
    {
        return $this->val;
    }

    protected $_modelitemsFactory;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Image\AdapterFactory $imageFactory,

        \Vicomage\Slider\Model\Resource\Items\CollectionFactory  $modelNewsFactory
    ) {

        $this->_modelitemsFactory = $modelNewsFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_filesystem = $context->getFilesystem();

        $this->_storeManager = $context->getStoreManager();

        $this->_directory = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        $this->_imageFactory = $imageFactory;
        parent::__construct($context);
    }


    public function numberImage($id)
    {
        $newsModel = $this->_modelitemsFactory->create()->addFieldToFilter('id',$id);
        foreach ($newsModel as $slider) {
                    $option= [
                        'name' => $slider->getName(),
                        'slider_param' => $slider->getData('slider_params'),
                        'number' => $slider->getNumber(),
                        'effect' => $slider->getData("transition_effect"),
                    ];
        }
        return $option;
    }


    // function resize image on admin
    public function imageResize($image,$w,$h){
        $pathResize = 'vicomage/slider/resized/'.$w.'/'.$h.'/admin/';
        $absPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('vicomage/slider').$image;

        $imageResized = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($pathResize).$image;

        if(file_exists($absPath) && is_file($absPath)) {
            $imageResize = $this->_imageFactory->create();
            $imageResize->open($absPath);
            $imageResize->constrainOnly(TRUE);
            $imageResize->keepTransparency(TRUE);
            $imageResize->resize($w, $h);
            $imageResize->save($imageResized);
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $pathResize . $image;
        }
        return null;

    }


   public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
   {
       $this->_element = $element;
       $html = $this->toHtml();
       return $html;
   }
}
