<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Catalog product form gallery content
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 *
 * @method \Magento\Framework\Data\Form\Element\AbstractElement getElement()
 */
namespace Vicomage\Slider\Block\Adminhtml\Helper\Form\Gallery;

use Magento\Backend\Block\Media\Uploader;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;

class Content extends \Magento\Backend\Block\Widget
{
    /**
     * @var string
     */
    protected $_template = 'Vicomage_Slider::helper/gallery.phtml';

    /**
     * @var \Magento\Catalog\Model\Product\Media\Config
     */
    protected $_mediaConfig;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    private $imageHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Catalog\Model\Product\Media\Config $mediaConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Vicomage\Slider\Model\Media\Config $mediaConfig,
        array $data = []
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        $this->_mediaConfig = $mediaConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->addChild('uploader', 'Magento\Backend\Block\Media\Uploader');
        $this->addChild('new-video', 'Vicomage\Slider\Block\Adminhtml\Helper\Form\Gallery\NewVideo')
            ->setTemplate('Vicomage_Slider::helper/form.phtml');
        $this->getUploader()->getConfig()->setUrl(
            $this->_urlBuilder->addSessionParam()->getUrl('vicomage_slider/items_gallery/upload')
        )->setFileField(
            'image'
        )->setFilters(
            [
                'images' => [
                    'label' => __('Images (.gif, .jpg, .png)'),
                    'files' => ['*.gif', '*.jpg', '*.jpeg', '*.png'],
                ],
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * Retrieve uploader block
     *
     * @return Uploader
     */
    public function getUploader()
    {
        return $this->getChildBlock('uploader');
    }

    /**
     * Retrieve uploader block html
     *
     * @return string
     */
    public function getUploaderHtml()
    {
        return $this->getChildHtml('uploader');
    }

    /**
     * @return string
     */
    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }

    /**
     * @return string
     */
    public function getAddImagesButton()
    {
        return $this->getButtonHtml(
            __('Add New Images'),
            $this->getJsObjectName() . '.showUploader()',
            'add',
            $this->getHtmlId() . '_add_images_button'
        );
    }

    /**
     * @return string
     */
    public function getImagesJson()
    {
        $value = $this->getValue();
        if ($value) {
            $value = json_decode($value,true);
            $mediaDir = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $images = $this->sortImagesByPosition($value);
            foreach ($images as &$image) {
                    $image['url'] = $this->_mediaConfig->getMediaUrl($image['image'] ? $image['image'] : $image['file']);
                try {
                    $fileHandler = $mediaDir->stat($this->_mediaConfig->getMediaPath(isset($image['image']) ? $image['image'] : $image['file']));
                    $image['size'] = $fileHandler['size'];
                } catch (FileSystemException $e) {
                    $image['size'] = 0;
                    $this->_logger->warning($e);
                }
            }
            return $this->_jsonEncoder->encode($images);
        }
        return '[]';
    }

    /**
     * Sort images array by position key
     *
     * @param array $images
     * @return array
     */
    private function sortImagesByPosition($images)
    {
        if (is_array($images)) {
            usort($images, function ($imageA, $imageB) {
                return ($imageA['position'] < $imageB['position']) ? -1 : 1;
            });
        }
        return $images;
    }

    /**
     * @return string
     */
    public function getImagesValuesJson()
    {
        $values = [];
        foreach ($this->getMediaAttributes() as $attribute) {
            /* @var $attribute \Magento\Eav\Model\Entity\Attribute */
            $values[$attribute->getAttributeCode()] = $this->getElement()->getDataObject()->getData(
                $attribute->getAttributeCode()
            );
        }
        return $this->_jsonEncoder->encode($values);
    }

    /**
     * Get image types data
     *
     * @return array
     */

    /**
     * Retrieve default state allowance
     *
     * @return bool
     */
    public function hasUseDefault()
    {
        foreach ($this->getMediaAttributes() as $attribute) {
            if ($this->getElement()->canDisplayUseDefault($attribute)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve media attributes
     *
     * @return array
     */
    public function getMediaAttributes()
    {
        return $this->getElement()->getDataObject()->getMediaAttributes();
    }

    /**
     * Retrieve JSON data
     *
     * @return string
     */
    public function getImageTypesJson()
    {
        return $this->_jsonEncoder->encode($this->getImageTypes());
    }
}
