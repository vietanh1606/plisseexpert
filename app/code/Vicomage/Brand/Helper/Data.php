<?php

namespace Vicomage\Brand\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const SECTIONS      = 'shopbrand';   // module name
    const GROUPS        = 'general';        // setup general

    public function getConfig($cfg=null)
    {
        return $this->scopeConfig->getValue(
            $cfg,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getGeneralCfg($cfg=null) 
    {
        $config = $this->scopeConfig->getValue(
            self::SECTIONS.'/'.self::GROUPS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if(isset($config[$cfg])) return $config[$cfg];
        return $config;
    }


}
