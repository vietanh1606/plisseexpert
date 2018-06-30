<?php
namespace Vicomage\General\Block\System\Config\Form;
use Magento\Framework\Data\Form\Element\AbstractElement;

class header extends \Magento\Config\Block\System\Config\Form\Field
{

    const CHECK_TEMPLATE = 'system/config/color_header.phtml';
    /**
     * Set template to itself
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::CHECK_TEMPLATE);
        }
        return $this;
    }



    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}