<?php

namespace Vicomage\ImportExport\Model\Export\Source;

use Vicomage\ImportExport\Helper\Data as Data;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Packagemodules
{
    /**
     * @var Data
     */
    protected $_exportData;

    /**
     * @var ScopeConfigInterface
     */
    protected $_configScopeConfigInterface;

    public function __construct(Data $helperData, 
        ScopeConfigInterface $configScopeConfigInterface)
    {
        $this->_exportData = $helperData;
        $this->_configScopeConfigInterface = $configScopeConfigInterface;

    }

	protected $_options;

	public function toOptionArray($package = NULL)
	{
		if (!$this->_options)
		{
			$this->_options = [];
			$this->_options[] = ['value' => '', 'label' => __('-- Please Select --')]; //First option is empty

            $h = $this->_exportData;
            $modules = $h->getPackageModules();
            if ($modules)
            {
                $moduleNames = $h->getModuleNames();
                foreach ($modules as $mod)
                {
                    $this->_options[] = ['value' => $mod, 'label' => $moduleNames[$mod]];
                }
            }
		}
		return $this->_options;
	}
}
