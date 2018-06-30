<?php
namespace Vicomage\General\Catalog\Helper\Image;

class Plugin
{
    /**
     * Plugin constructor.
     * @param \Vicomage\General\Helper\Data $helper
     */
    public function __construct(
        \Vicomage\General\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Framework\DataObject $subject $subject
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return array
     */
    public function beforeInit($subject, $product, $imageId, $attributes = [])
    {
        if ($imageId == 'product_page_image_medium') {
            $attributes['width'] = $this->_helper->getConfig('vicomage_general_config/product/base_image_width');
            $attributes['height'] = $this->_helper->getConfig('vicomage_general_config/product/base_image_height');
        }
        if ($imageId == 'product_page_image_small') {
            $attributes['width'] = $this->_helper->getConfig('vicomage_general_config/product/moreview_image_width');
            $attributes['height'] = $this->_helper->getConfig('vicomage_general_config/product/moreview_image_height');
        }
        return [$product, $imageId, $attributes];
    }
}
