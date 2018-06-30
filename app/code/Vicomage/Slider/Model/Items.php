<?php
/**
 * Copyright © 2016 Vicomage. All rights reserved.
 */

namespace Vicomage\Slider\Model;

class Items extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Vicomage\Slider\Model\Resource\Items');
    }
	

}
