<?php
namespace Vicomage\General\Model\Design\Backend;

use Magento\Config\Model\Config\Backend\Serialized\ArraySerialized;

class Color extends ArraySerialized
{
    protected $serialize;

    /**
     * Initialize dependencies
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        array $data = []
    ) {
        $this->serialize = $serialize;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();

        //This check for magento 2.2 when customer upgrade magento version from 2.1
        if (!is_array($value)) {
            if (@unserialize($value) !== false) {
                $value = unserialize($value);
                $value = json_encode($value);
            }
            $this->setValue(empty($value) ? false : $this->serialize->unserialize($value));
        }
    }

    /**
     * Validate value
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * if there is no field value, search value is empty or regular expression is not valid
     */
    public function beforeSave()
    {

        // For value validations
        $exceptions = $this->getValue();
        foreach ($exceptions as $rowKey => $row) {
            if ($rowKey === '__empty') {
                continue;
            }

            // Validate that all values have come
            foreach (['title', 'element'] as $fieldName) {
                if (!isset($row[$fieldName])) {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('Exception does not contain field \'%1\'', $fieldName)
                    );
                }
            }

            // Empty string (match all) is not supported, because it means setting a default theme. Remove such entries.
            if (!strlen($row['element'])) {
                unset($exceptions[$rowKey]);
                continue;
            }

        }
        $this->setValue($exceptions);

        return parent::beforeSave();
    }

}
