<?php
/**
 * Copyright © 2016 Vicomage. All rights reserved.
 */

namespace Vicomage\Slider\Controller\Adminhtml\Items;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Vicomage\Slider\Controller\Adminhtml\Items
{

    /**
     * Execute Save Data to Table
     */
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_objectManager->create('Vicomage\Slider\Model\Items');
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );

                $data = $inputFilter->getUnescaped();

                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }else{
                    if(isset($data['identity'])){
                        $sliderResult = $model->getCollection()->addFieldToFilter('identity' , array('eq' => $data['identity']))->getData();
                        if(isset($sliderResult[0]['id'])){
                            $this->messageManager->addError(__('Id already exists!'));
                            $this->_redirect('vicomage_slider/*/new');
                            return;
                        }
                    }
                }
                $sliderParams = [];
                $i = 0;
                if(isset($data['slider_params']) && count($data['slider_params']['images'])){
                    foreach ($data['slider_params']['images'] as $key => $item) {
                        $i++;
                        if (!$item['file']) {
                            $item['file'] = $item['image'];
                        }
                        if ($item['removed']) {
                            $this->removeImage($item['file']);
                            unset($data[$key]);
                        } else {
                            $sliderParams[$i] = $item;
                        }
                    }
                }

                $data['number'] = count($sliderParams) ?  count($sliderParams) : 0;
                $data['slider_params'] = count($sliderParams) ? json_encode($sliderParams) : "";

                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();

                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('vicomage_slider/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('vicomage_slider/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('vicomage_slider/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('vicomage_slider/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('vicomage_slider/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('vicomage_slider/*/');
    }

    /**
     * Remove image if element removed = 1
     * @param $file
     */
    public function removeImage($file)
    {
        $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(DirectoryList::MEDIA);
        $mediaRootDir = $mediaDirectory->getAbsolutePath();
        $path = $this->mediaConfig->getMediaPath($file);
        if ($this->file->isExists($mediaRootDir.$path))  {
            $this->file->deleteFile($mediaRootDir.$path);
        }
    }
}
