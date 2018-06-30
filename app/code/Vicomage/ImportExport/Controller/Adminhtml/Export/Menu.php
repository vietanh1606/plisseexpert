<?php

namespace Vicomage\ImportExport\Controller\Adminhtml\Export;

use Magento\Framework\App\Filesystem\DirectoryList;

class Menu extends \Vicomage\ImportExport\Controller\Adminhtml\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    public function execute()
    {
        if($this->getRequest()->getParam('theme_path')) $this->ExportXml();
        $resultPage = $this->_resultPageFactory->create();
        return $resultPage;
    }

    public function ExportXml()
    {
        $fileName = 'megamenu.xml';
        $theme_path = $this->getRequest()->getParam('theme_path');
        $exportIds = $this->getRequest()->getParam('exportIds');
        $dir = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
        $filePath = sprintf(self::CMS, strtolower($theme_path)) .$fileName;
        try{
            $collection = $this->_objectManager->create('\Vicomage\Megamenu\Model\ResourceModel\Items\Collection');
            $collection->addFieldToFilter('item_id',array('in'=>$exportIds));
            $options = array('item_id','label', 'menu_type', 'url', 'position','columns','custom_class','html_label','status','store','category_id','top_content','bottom_content','left_content','left_col','right_content','right_col','menu_ef','static_width','category_label','fake_name','subcategory_columns','main_content');
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<root>';
            $xml.= '<megamenu>';
            $num = 0;
            foreach ($collection as $menu) {
                $xml.= '<item>';
                foreach ($options as $opt) {
                    $xml.= '<'.$opt.'><![CDATA['.$menu->getData($opt).']]></'.$opt.'>';
                }
                $xml.= '</item>';
                $num++;
            }
            $xml.= '</megamenu>';
            $xml.= '</root>';
            // $this->_sendUploadResponse($fileName, $xml);
            $dir->writeFile($filePath, '$xml');
            $backupFilePath = $dir->getAbsolutePath($filePath);
            // @mkdir($filePath, 0644, true);
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
