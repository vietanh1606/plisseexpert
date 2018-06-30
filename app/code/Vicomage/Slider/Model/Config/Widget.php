<?php 


namespace Vicomage\Slider\Model\Config;
class Widget implements \Magento\Framework\Option\ArrayInterface
{

		protected $_modelitemsFactory;
		public function __construct(
			
			\Vicomage\Slider\Model\Resource\Items\CollectionFactory  $modelNewsFactory
		) {
			
			$this->_modelitemsFactory = $modelNewsFactory;
		}


    /**
     * @return array
     */
    public function toOptionArray()
    {
		 return $this->slideShow();
    }

    /**
     * @return array
     */
    public function slideShow()
    {
        $newsModel = $this->_modelitemsFactory->create();
        $option = array();
        foreach ($newsModel as $slider) {
                    $option[]= [
                        'label' => $slider->getName(),
                        'value' => $slider->getIdentity(),
                    ];
        }
        return $option;
    }
}