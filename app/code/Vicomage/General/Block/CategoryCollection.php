<?php
namespace Vicomage\General\Block;

class CategoryCollection extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @var \Magento\Catalog\Model\Indexer\Category\Flat\State
     */
    protected $categoryFlatConfig;

    /**
     * @var \Magento\Theme\Block\Html\Topmenu
     */
    protected $topMenu;

    /**
     * CategoryCollection constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState
     * @param \Magento\Theme\Block\Html\Topmenu $topMenu
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Theme\Block\Html\Topmenu $topMenu
    ) {

        $this->_categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->topMenu = $topMenu;
        parent::__construct($context);
    }

    /**
     * Return categories helper
     * 
     * @return \Magento\Catalog\Helper\Category
     */
    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }

    /**
     * Return categories helper
     * getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
     * example getHtml('level-top', 'submenu', 0)
     * 
     * @return string
     */
    public function getHtml()
    {
        return $this->topMenu->getHtml();
    }

    /**
     * Retrieve current store categories
     * 
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @return \Magento\Framework\Data\Tree\Node\Collection
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted, $asCollection, $toLoad);
    }

    /**
     * Retrieve child store categories
     * 
     * @param object $category
     * @return array
     */
    public function getChildCategories($category)
    {
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        return $subcategories;
    }

    /**
     * Retrieve child category html
     * 
     * @param object $category
     * @param string $iconOpenClass
     * @param string $iconCloseClass
     * @return string
     */
    public function getChildCategoryHtml(
        $category,
        $iconOpenClass = "porto-icon-plus-squared",
        $iconCloseClass = "porto-icon-minus-squared"
    ) {
        $html = '';
        if ($childrenCategories = $this->getChildCategories($category)) {
            $html .= '<ul>';
            $i = 0;
            foreach ($childrenCategories as $childrenCategory) {
                if (!$childrenCategory->getIsActive()) {
                    continue;
                }
                $i++;
                $html .= '<li><a href="' . $this->_categoryHelper->getCategoryUrl($childrenCategory) . '">'
                    . $childrenCategory->getName() . '</a>';
                $html .= $this->getChildCategoryHtml($childrenCategory, $iconOpenClass, $iconCloseClass);
                $html .= '</li>';
            }
            $html .= '</ul>';
            if ($i > 0) {
                $html .= '<a href="javascript:void(0)" class="expand-icon"><em class="' . $iconOpenClass . '"></em></a>';
            }
        }
        return $html;
    }

    /**
     * Retrieve category sidebar html
     *
     * @param string $iconOpenClass
     * @param string $iconCloseClass
     * @return string
     */
    public function getCategorySidebarHtml(
        $iconOpenClass = "porto-icon-plus-squared",
        $iconCloseClass = "porto-icon-minus-squared"
    ) {
        $html = '';
        $categories = $this->getStoreCategories(true, false, true);
        $html .= '<ul class="category-sidebar">';
        foreach ($categories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }
            $html .= '<li>';
            $html .= '<a href="' . $this->_categoryHelper->getCategoryUrl($category) . '">' . $category->getName() . '</a>';
            $html .= $this->getChildCategoryHtml($category, $iconOpenClass, $iconCloseClass);
            $html .= '</li>';
        }
        $html .= '</ul>';
        $html .= '<script type="text/javascript">
    jQuery(function($){
        $(".category-sidebar li > .expand-icon").click(function(){
            if($(this).parent().hasClass("opened")){
                $(this).parent().children("ul").slideUp();
                $(this).parent().removeClass("opened");
                $(this).children(".' . $iconCloseClass . '").removeClass("' . $iconCloseClass . '").addClass("' . $iconOpenClass . '");
            } else {
                $(this).parent().children("ul").slideDown();
                $(this).parent().addClass("opened");
                $(this).children(".' . $iconOpenClass . '").removeClass("' . $iconOpenClass . '").addClass("' . $iconCloseClass . '");
            }
        });
    });
</script>';
        return $html;
    }
}
