<?php

namespace Vicomage\Testimonial\Controller\Adminhtml\Testimonial;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Vicomage\Testimonial\Controller\Adminhtml\Testimonial
{

    public function __construct(
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {
            $model = $this->_objectManager->create('Vicomage\Testimonial\Model\Testimonial');
            $storeViewId = $this->getRequest()->getParam('store');

            if ($id = $this->getRequest()->getParam('id')) {
                $model->load($id);
            }

            if (isset($_FILES['avatar']) && isset($_FILES['avatar']['name']) && strlen($_FILES['avatar']['name'])) {
                /*
                 * Save image upload
                 */
                try {
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'avatar']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();

                    $uploader->addValidateCallback('testimonial_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath('vicomage/testimonial')
                    );

                    $data['avatar'] = 'vicomage/testimonial'.$result['file'];

                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($data['avatar']) && isset($data['avatar']['value'])) {
                    if (isset($data['avatar']['delete'])) {
                        $data['avatar'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['avatar']['value'])) {
                        $data['avatar'] = $data['avatar']['value'];
                    } else {
                        $data['avatar'] = null;
                    }
                }
            }
            /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate */

            if(isset($data['stores'])) $data['stores'] = implode(',', $data['stores']);

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Testimonial menu has been saved.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back') === 'edit') {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'id' => $model->getId(),
                            '_current' => true,
                            'store' => $storeViewId,
                            'current_testimonial_id' => $this->getRequest()->getParam('current_testimonial_id'),
                            'saveandclose' => $this->getRequest()->getParam('saveandclose'),
                        ]
                    );
                } elseif ($this->getRequest()->getParam('back') === 'new') {
                    return $resultRedirect->setPath(
                        '*/*/new',
                        ['_current' => TRUE]
                    );
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the testimonial.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $this->getRequest()->getParam('id')]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
