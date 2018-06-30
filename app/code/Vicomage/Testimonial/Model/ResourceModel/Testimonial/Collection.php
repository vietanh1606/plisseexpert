<?php
namespace Vicomage\Testimonial\Model\ResourceModel\Testimonial;

/**
 * Testimonial resource model collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Init resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Vicomage\Testimonial\Model\Testimonial', 'Vicomage\Testimonial\Model\ResourceModel\Testimonial');
    }
}
