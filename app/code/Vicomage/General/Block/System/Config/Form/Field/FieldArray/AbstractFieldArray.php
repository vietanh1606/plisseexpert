<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Vicomage\General\Block\System\Config\Form\Field\FieldArray;

/**
 * Backend system config array field renderer
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class AbstractFieldArray extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * Add column method
     *
     * @param string $name
     * @param array $params
     */
    public function addColumn($name, $params)
    {
        $this->_columns[$name] = [
            'label' => $this->_getParam($params, 'label', 'Column'),
            'size' => $this->_getParam($params, 'size', false),
            'style' => $this->_getParam($params, 'style'),
            'class' => $this->_getParam($params, 'class'),
            'renderer' => false,
        ];
        if (!empty($params['renderer']) &&
            $params['renderer'] instanceof \Magento\Framework\View\Element\AbstractBlock) {
            $this->_columns[$name]['renderer'] = $params['renderer'];
        }
    }

}