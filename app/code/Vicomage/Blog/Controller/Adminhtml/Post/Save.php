<?php
/**
 * Copyright © 2015 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Controller\Adminhtml\Post;

use Vicomage\Blog\Model\Post;
/**
 * Blog post save controller
 */
class Save extends \Vicomage\Blog\Controller\Adminhtml\Post
{
    /**
     * Before model save
     * @param  \Vicomage\Blog\Model\Post $model
     * @param  \Magento\Framework\App\Request\Http $request
     * @return void
     */
    protected function _beforeSave($model, $request)
    {
        /* Prepare publish date */
        $dateFilter = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\Filter\Date');
        $data = $model->getData();

        $inputFilter = new \Zend_Filter_Input(
            ['publish_time' => $dateFilter],
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        $model->setData($data);

        /* Prepare author */
        if (!$model->getAuthorId()) {
            $authSession = $this->_objectManager->get('Magento\Backend\Model\Auth\Session');
            $model->setAuthorId($authSession->getUser()->getId());
        }

        /* Prepare relative links */
        if ($links = $request->getPost('links')) {

            $jsHelper = $this->_objectManager->create('Magento\Backend\Helper\Js');

            $links = is_array($links) ? $links : [];
            $linkTypes = ['relatedposts', 'relatedproducts'];
            foreach ($linkTypes as $type) {

                if (isset($links[$type])) {
                    $links[$type] = $jsHelper->decodeGridSerializedInput($links[$type]);

                    $model->setData($type.'_links', $links[$type]);
                }
            }
        }

        /* Prepare image */
        $imageFieldArray = array('featured_img','thumbnail_img');
        foreach($imageFieldArray as $imageField) {
            $fileSystem = $this->_objectManager->create('Magento\Framework\Filesystem');
            $mediaDirectory = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

            if (isset($data[$imageField]) && isset($data[$imageField]['value'])) {
                if (isset($data[$imageField]['delete'])) {
                    unlink($mediaDirectory->getAbsolutePath() . $data[$imageField]['value']);
                    $model->setData($imageField, '');
                } else {
                    $model->unsetData($imageField);
                }
            }
            try {
                $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory');
                $uploader = $uploader->create(['fileId' => 'post[' . $imageField . ']']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath(Post::BASE_MEDIA_PATH)
                );
                $model->setData($imageField, Post::BASE_MEDIA_PATH . $result['file']);
            } catch (\Exception $e) {
                if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
                    throw new \Exception($e->getMessage());
                }
            }
        }
    }

}
