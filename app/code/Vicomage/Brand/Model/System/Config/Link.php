<?php

namespace Vicomage\Brand\Model\System\Config;

class Link implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return array(
			array('value' => 1,				'label' => __('Custom Link')),
			array('value' => 2,				'label' => __('Quick Search Results')),
			array('value' => 3,				'label' => __('Advanced Search Results')),
		);
	}
}

