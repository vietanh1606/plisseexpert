<?php
namespace Vicomage\Blog\Block\Widget;

/**
 * Blog recent posts widget
 */
class Recent extends \Vicomage\Blog\Block\Post\PostList\AbstractList implements \Magento\Widget\Block\BlockInterface
{

    /**
     * @var \Vicomage\Blog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Vicomage\Blog\Model\Category
     */
    protected $_category;
    protected $_coreHelper;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Vicomage\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \Vicomage\Blog\Model\Url $url
     * @param \Vicomage\Blog\Model\CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        \Vicomage\Core\Helper\Data $coreHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Vicomage\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Vicomage\Blog\Model\Url $url,
        \Vicomage\Blog\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->_coreHelper = $coreHelper;
        parent::__construct($context, $coreRegistry, $filterProvider, $postCollectionFactory, $url, $data);
        $this->_categoryFactory = $categoryFactory;
    }

    /**
     * @return $this
     */
    public function _construct()
    {
        $size = $this->getData('number_of_posts');
        if (!$size) {
            $size = (int) $this->_scopeConfig->getValue(
                'vicomage_mfblog/sidebar/recent_posts/posts_per_page',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }

        $this->setPageSize($size);
        $data = $this->getData();
        unset($data['type']);
        if($data['view_as'] == 'slide'){

            if(isset($data['vertical'])) {
                $data['vertical-Swiping'] = $data['vertical'];
            }
            $reponsiveConfig = $this->_coreHelper->getConfigDevice();
            $responsive = [];
            foreach ($reponsiveConfig as $size => $screen) {
                $responsive[$size] = $data[$screen];
            }
            $data['responsive'] = $responsive;
            $data['slides-To-Show'] = $data['visible'];
            $data['swipe-To-Slide'] = 'true';
        }
        $this->addData($data);

        return parent::_construct();
    }

    /**
     * Set blog template
     *
     * @return this
     */
    public function _toHtml()
    {
        $this->setTemplate(
            $this->getData('template') ?: 'widget/recent.phtml'
        );

        return parent::_toHtml();
    }

    /**
     * Retrieve block title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title') ?: __('Recent Blog Posts');
    }

    /**
     * Prepare posts collection
     *
     * @return void
     */
    protected function _preparePostCollection()
    {
        parent::_preparePostCollection();
        if ($category = $this->getCategory()) {
            $categories = $category->getChildrenIds();
            $categories[] = $category->getId();
            $this->_postCollection->addCategoryFilter($categories);
        }
    }

    /**
     * Retrieve category instance
     *
     * @return \Vicomage\Blog\Model\Category
     */
    public function getCategory()
    {
        if ($this->_category === null) {
            if ($categoryId = $this->getData('category_id')) {
                $category = $this->_categoryFactory->create();
                $category->load($categoryId);

                $storeId = $this->_storeManager->getStore()->getId();
                if ($category->isVisibleOnStore($storeId)) {
                    $category->setStoreId($storeId);
                    return $this->_category = $category;
                }
            }

            $this->_category = false;
        }

        return $this->_category;
    }

    /**
     * Retrieve post short content
     * @param  \Vicomage\Blog\Model\Post $post
     *
     * @return string
     */
    public function getShorContent($post)
    {
        $content = $post->getData('short_content');
        return $this->_filterProvider->getPageFilter()->filter($content);
    }


    /**
     * create url media
     * @param $imagePath
     * @return string
     */
    public function getMediaUrl($imagePath){
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl.$imagePath;
    }

    public function getFrontendCfg()
    {
        //check if config is slider will return slider option
        $dataConfig['slider'] = false;
        if ($this->getViewAs() == 'slide') {
            $dataConfig['slider'] = true;
            foreach ($this->_coreHelper->getSlideOptions() as $config => $defaultValue) {
                $value = $this->getData($config);
                if(!empty($value)) {
                    if (is_numeric($value)) {
                        $value = (int)$value;
                    } elseif ($value === 'true') {
                        $value = true;
                    } elseif ($value === 'false') {
                        $value = false;
                    }
                    $dataConfig[str_replace('-', '', $config)] = $value;
                }else{
                    $dataConfig[str_replace('-', '', $config)] = $defaultValue;
                }
            }

            return json_encode($dataConfig);
        }else {
            $this->addData(array('responsive' =>json_encode($this->getGridOptions())));
        }
        return json_encode($dataConfig);
    }
	
	public function getGridOptions()
    {
        $options = array();
        $breakpoints = $this->_coreHelper->getConfigDevice(); ksort($breakpoints);
        foreach ($breakpoints as $size => $screen) {
            $options[]= array($size-1 => $this->getData($screen));
        }
        return $options;
    }
	
}

