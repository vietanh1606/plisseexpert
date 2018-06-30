<?php

namespace Vicomage\ImportExport\Controller\Adminhtml\Export;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Vicomage\ImportExport\Controller\Adminhtml\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if($this->getRequest()->getParam('theme_path')) $this->ExportXml();

        return $resultRedirect->setPath('*/*/system');
    }

    public function ExportXml()
    {
        $fileName = 'system.xml';
        $theme_path = $this->getRequest()->getParam('theme_path');
        $moduleSelected = $this->getRequest()->getParam('modules');
        $store = $this->_storeManager->getStore($this->getRequest()->getParam('store'));
        $modules = $this->_objectManager->create('\Vicomage\ImportExport\Helper\Data')->getPackageModules();
        $moduleDir =  $this->_objectManager->create('\Magento\Framework\Module\Dir');
        $dir = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
        $filePath = sprintf(self::CMS, strtolower($theme_path)) .$fileName;
        try{

                $xml = '<?xml version="1.0" encoding="UTF-8"?>';
                $xml .= '<root>';
                $xml .= '<system>';
                $num = 0;
                foreach ($modules as $module) {
                    if(!in_array($module,$moduleSelected)){
                        continue;
                    }
                    //check export color and general
                    $checkGeneralExport = false;
                    if ($module === 'Vicomage_General') {
                        $checkGeneralExport = true;
                    }

                    $checkColorExport = false;
                    if ($module === 'Vicomage_Color') {
                        $module = 'Vicomage_General';
                        $checkColorExport = true;
                    }


                    $etc = $moduleDir->getDir($module, 'etc');
                    $systemXml = $etc. DIRECTORY_SEPARATOR . 'adminhtml' . DIRECTORY_SEPARATOR . 'system.xml';
                    if(file_exists($systemXml)){

                        $sysXmlObj = new \Magento\Framework\Simplexml\Config($systemXml);
                        $sections = $sysXmlObj->getNode('system')->children();

                        foreach ($sections as $tmp) {
                            if($tmp->getName() != 'section') continue;
                            $keylv1 = $tmp->getAttribute('id');

                            //check export color
                            if($checkColorExport && $keylv1 !== 'vicomage_color_config'){
                                continue;
                            }
                            //check export general
                            if($checkGeneralExport && $keylv1 !== 'vicomage_general_config'){
                                continue;
                            }

                            $collection = $this->_scopeConfig->getValue($keylv1, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
                            foreach ($collection as $keylv2 => $valuelv2) {
                                if(is_array($valuelv2)){
                                    foreach ($valuelv2 as $keylv3 => $valuelv3) {
                                        if(is_array($valuelv3)){
                                            foreach ($valuelv3 as $keylv4 => $valuelv4) {
                                                $xml .= '<config>';
                                                $xml .= '<path>' . $keylv1 .'/'. $keylv2 .'/'. $keylv3 .'/'. $keylv4 .'</path>';
                                                $xml .= '<value><![CDATA[' . $valuelv4 . ']]></value>'; //$xml .= '<value>' . $valuelv3 . '</value>';
                                                $xml .= '</config>';
                                                $num++;
                                            }
                                        }else{
                                            $xml .= '<config>';
                                            $xml .= '<path>' . $keylv1 .'/'. $keylv2 .'/'. $keylv3 . '</path>';
                                            $xml .= '<value><![CDATA[' . $valuelv3 . ']]></value>'; //$xml .= '<value>' . $valuelv3 . '</value>';
                                            $xml .= '</config>';
                                            $num++;
                                        }
                                    }

                                }
                            }
                        }

                    }
                }
                $extraCfg = array(
                    'web/default/front',
                    'web/default/cms_home_page',
                    'web/default/cms_no_route',
                );

                foreach ($extraCfg as $cfg) {
                    $vl = $this->_scopeConfig->getValue($cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
                    $xml .= '<config>';
                    $xml .= '<path>' . $cfg . '</path>';
                    $xml .= '<value>' . $vl . '</value>';
                    $xml .= '</config>';
                    $num++;
                }

                $xml .= '</system>';

                $themeId = $this->_scopeConfig->getValue('design/theme/theme_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
                $theme = $this->_objectManager->create('Magento\Theme\Model\Theme')->load($themeId);
                $xml .= '<theme>' . $theme->getData('theme_path') . '</theme>';
                $xml .= '</root>';

                $dir->writeFile($filePath, '$xml');
                $backupFilePath = $dir->getAbsolutePath($filePath);
                $doc =  new \DOMDocument('1.0', 'UTF-8');
                $doc->loadXML($xml);
                $doc->formatOutput = true;
                $doc->save($backupFilePath);

                $this->messageManager->addSuccess(__('Export (%1) Item(s):', $num));
                $this->messageManager->addSuccess(__('Successfully exported to file "%1"',$backupFilePath));
        } catch (\Exception $e) {
                $this->messageManager->addError(__('Can not save export file "%1".<br/>"%2"', $filePath, $e->getMessage()));
        }
    }
}
