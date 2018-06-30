<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Slider\Block\Adminhtml\Helper\Form\Gallery;

use Magento\Framework\Data\Form\Element\Fieldset;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class NewVideo extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Anchor is product video
     */
    const PATH_ANCHOR_SLIDER_VIDEO = 'vicomage_slider_video-link';

    /**
     * @var \Magento\ProductVideo\Helper\Media
     */
    protected $mediaHelper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var string
     */
    protected $videoSelector = '#vicomage_slider_content_slider_params_content';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\ProductVideo\Helper\Media $mediaHelper
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\ProductVideo\Helper\Media $mediaHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->mediaHelper = $mediaHelper;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->jsonEncoder = $jsonEncoder;
        $this->setUseContainer(true);
    }

    /**
     * Form preparation
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'new_video_form',
                'class' => 'admin__scope-old',
                'enctype' => 'multipart/form-data',
            ]
        ]);
        $form->setUseContainer($this->getUseContainer());
        $form->addField('new_video_messages', 'note', []);
        $fieldset = $form->addFieldset('new_video_form_fieldset', []);
        $fieldset->addField(
            '',
            'hidden',
            [
                'name' => 'form_key',
                'value' => $this->getFormKey(),
            ]
        );
        $fieldset->addField(
            'item_id',
            'hidden',
            []
        );
        $fieldset->addField(
            'file_name',
            'hidden',
            []
        );
        $fieldset->addField(
            'video_provider',
            'hidden',
            [
                'name' => 'video_provider',
            ]
        );
        $fieldset->addField(
            'video_url',
            'text',
            [
                'class' => 'edited-data validate-url',
                'label' => __('Url'),
                'title' => __('Url'),
                'required' => true,
                'name' => 'video_url',
                'note' => $this->getNoteVideoUrl(),
            ]
        );
        $fieldset->addField(
            'video_title',
            'text',
            [
                'class' => 'edited-data',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
                'name' => 'video_title',
            ]
        );
        $fieldset->addField(
            'video_description',
            'textarea',
            [
                'class' => 'edited-data',
                'label' => __('Description'),
                'title' => __('Description'),
                'name' => 'video_description',
            ]
        );
        $fieldset->addField(
            'new_video_screenshot',
            'file',
            [
                'label' => __('Preview Image'),
                'title' => __('Preview Image'),
                'name' => 'image',
            ]
        );
        $fieldset->addField(
            'new_video_screenshot_preview',
            'button',
            [
                'class' => 'preview-image-hidden-input',
                'label' => '',
                'name' => '_preview',
            ]
        );
        $fieldset->addField(
            'new_video_get',
            'button',
            [
                'label' => '',
                'title' => 'Get Video Information',
                'name' => 'new_video_get',
                'value' => __('Get Video Information'),
                'class' => 'action-default'
            ]
        );
        $fieldset->addField(
            'new_video_disabled',
            'checkbox',
            [
                'class' => 'edited-data',
                'label' => __('Hide from Product Page'),
                'title' => __('Hide from Product Page'),
                'name' => 'disabled',
            ]
        );
        $this->setForm($form);
    }

    /**
     * Get html id
     *
     * @return mixed
     */
    public function getHtmlId()
    {
        if (null === $this->getData('id')) {
            $this->setData('id', $this->mathRandom->getUniqueHash('id_'));
        }
        return $this->getData('id');
    }

    /**
     * Get widget options
     *
     * @return string
     */
    public function getWidgetOptions()
    {
        return $this->jsonEncoder->encode(
            [
                'saveVideoUrl' => $this->getUrl('vicomage_slider/items_gallery/upload'),
                'saveRemoteVideoUrl' => $this->getUrl('vicomage_slider/items_gallery/retrieveImage'),
                'htmlId' => $this->getHtmlId(),
                'youTubeApiKey' => $this->mediaHelper->getYouTubeApiKey(),
                'videoSelector' => $this->videoSelector
            ]
        );
    }

    /**
     * Get note for video url
     *
     * @return \Magento\Framework\Phrase
     */
    protected function getNoteVideoUrl()
    {
        $result = __('YouTube and Vimeo supported.');
        if ($this->mediaHelper->getYouTubeApiKey() === null) {
            $result = __(
                'Vimeo supported.<br />'
                . 'To add YouTube video, please <a href="%1">enter YouTube API Key</a> first.',
                $this->getConfigApiKeyUrl()
            );
        }
        return $result;
    }

    /**
     * Get url for config params
     *
     * @return string
     */
    protected function getConfigApiKeyUrl()
    {
        return $this->urlBuilder->getUrl(
            'adminhtml/system_config/edit',
            [
                'section' => 'catalog',
                '_fragment' => self::PATH_ANCHOR_SLIDER_VIDEO
            ]
        );
    }
}
