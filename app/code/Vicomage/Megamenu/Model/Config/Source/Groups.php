<?php
namespace Vicomage\Megamenu\Model\Config\Source;

class Groups implements \Magento\Framework\Option\ArrayInterface
{

    protected $groupCollectionFactory;

    public function __construct(
        \Vicomage\Megamenu\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
    ) {
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $groupCollection = $this->groupCollectionFactory->create();
        $groupCollection->addFieldToFilter('status', array('eq' => 1));
        $groupArray[] = ['value' => '', 'label' => __('Select a group')];
        foreach ($groupCollection as $group) {
            $groupArray[] = ['value' => $group->getId(), 'label' => $group->getTitle()];
        }
        return $groupArray;
    }
}
