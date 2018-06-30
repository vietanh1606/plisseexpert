<?php
namespace Vicomage\QuickView\Model\Config\Source;

class ListEffects implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value'=>'elastic', 'label'=>__('Elastic')],
			['value'=>'fade', 'label'=>__('Fade')],
			['value'=>'none', 'label'=>__('None')]
		];
	}
}