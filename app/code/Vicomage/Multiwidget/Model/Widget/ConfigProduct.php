<?php
namespace Vicomage\Multiwidget\Model\Widget;

class ConfigProduct implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'cart', 'label' => __('Show Cart')],
            ['value' => 'compare', 'label' => __('Show Compare')],
            ['value' => 'wishlist', 'label' => __('Show Wishlist')],
            ['value' => 'review', 'label' => __('Show Review')],
            ['value' => 'quickview', 'label' => __('Show Quick View')],
        ];
    }
}
