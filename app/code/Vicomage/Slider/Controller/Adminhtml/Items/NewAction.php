<?php
/**
 * Copyright © 2016 Vicomage. All rights reserved.
 */

namespace Vicomage\Slider\Controller\Adminhtml\Items;

class NewAction extends \Vicomage\Slider\Controller\Adminhtml\Items
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
