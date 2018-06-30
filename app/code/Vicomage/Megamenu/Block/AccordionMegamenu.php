<?php

namespace Vicomage\Megamenu\Block;

class AccordionMegamenu extends \Vicomage\Megamenu\Block\Megamenu
{

    /**
     * get config for top menu
     * @return array
     *
     */
    public function getConfig()
    {

        $config = array(
            'enabled' => $this->_scopeConfig->getValue('vicomage_megamenu_setting/accordion_menu/enabled',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'group' => $this->_scopeConfig->getValue('vicomage_megamenu_setting/accordion_menu/group',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE),

        );
        return $config;
    }


    /**
     * get html mega menu
     * @param $item
     * @return null|string
     */
    public function getMegamenuHtml($item)
    {

        $menuType = 'dropdown';
        $currentClass = null;
        $enable = true;
        $category = false;
        //check item category if disable will can not display
        if ($item->getMenuType() == 1) {
            $category = $this->getCategoryById($item->getCategoryId());
            if ($category == false) {
                $enable = false;
            }
        }
        if ($enable) {
            if ($item->getMenuType() == 2) {
                $customUrl = ($item->getUrl()) ? $item->getUrl() : '#';
                $parentClass = null;

            } else {
                $currentClass = $this->checkCurrentCategory($item->getCategoryId());
                $parentClass = ($this->hasSubcategory($item->getCategoryId())) ? 'parent' : null;
                if ($category) {
                    $categoryUrl = $category->getUrl();
                } else {
                    $categoryUrl = null;
                }
                $customUrl = ($item->getUrl()) ? $item->getUrl() : $categoryUrl;
            }
            $html = null;
            $html .= '<li class="' . $currentClass . ' ui-menu-item level0 ' . $parentClass . ' ' . $menuType . ' ' . $item->getCustomClass() . '">';
            $html .= '<a href="' . $customUrl . '" class="level-top"><span>' . __($item->getFakeName());

            if ($item->getCategoryLabel() && $item->getCategoryLabel() != null) {
                $classLabel = strtolower(str_replace(' ', '', $item->getCategoryLabel()));
                $html .= '<span class="cat-label cat-label-' . $classLabel . '" >' . $item->getCategoryLabel() . '</span >';
            }

            $html .= '</span>';

            $html .= '</a>';

            if ($item->getMenuType() == 1) {
                if ($this->hasSubcategory($item->getCategoryId())) {
                    $html .= '<div class="open-children-toggle"></div>';
                }
                //get html when type item is category
                $html .= $this->getHtmlMenuTypeCategory($item, $menuType);
            }

            $html .= '</li>';

            return $html;
        }
        return null;
    }


    /**
     * get Html of type category
     * @param $item
     * @param $menuType
     * @return null|string
     */
    public function getHtmlMenuTypeCategory($item, $menuType)
    {
        $html = null;
        if ($this->hasSubcategory($item->getCategoryId())) {
            $html .= '<ul class="subchildmenu submenu">';
            $html .= $this->getCategoryDropdown($item->getCategoryId());
            $html .= '</ul>';
        }

        return $html;
    }


    /**
     * get category of item
     * @param $categoryId
     * @return null|string
     */
    public function getCategoryDropdown($categoryId)
    {
        $childCategorys = $this->getChildrenCategoryById($categoryId);

        $html = null;
        $level = 1;
        if ($childCategorys) {
            foreach (explode(',', $childCategorys) as $childCategory) {
                if ($childCategory) {
                    $category = $this->getCategoryById($childCategory);
                    if ($category) {
                        $parentClass = ($this->hasSubcategory($category->getId())) ? 'parent' : null;
                        $html .= '<li class="' . $this->checkCurrentCategory($category->getId()) . ' ui-menu-item level' . $level . ' ' . $parentClass . '">';
                        $html .= '<a class="level-top" href="' . $category->getUrl() . '"><span>' . __($category->getName()) . '</span></a>';
                        $html .= $this->getSubCategoryDropdown($category->getId(), ($level + 1));
                        $html .= '</li>';
                    }
                }
            }
        }
        return $html;
    }


    /**
     * get sub categorys of type dropdown
     * @param $categoryId
     * @param $level
     * @return null|string
     */
    public function getSubCategoryDropdown($categoryId, $level)
    {
        $childCategorys = $this->getChildrenCategoryById($categoryId);
        $html = null;

        if ($childCategorys != null) {

            $html .= '<div class="open-children-toggle"></div>';
            $html .= '<ul class="subchildmenu submenu">';
            foreach (explode(',', $childCategorys) as $childCategory) {
                if ($childCategory) {
                    $category = $this->getCategoryById($childCategory);
                    if ($category) {
                        $parentClass = ($this->hasSubcategory($category->getId())) ? 'parent' : null;
                        $html .= '<li class="' . $this->checkCurrentCategory($category->getId()) . ' ui-menu-item level' . $level . ' ' . $parentClass . '"><a class="level-top" href="' . $category->getUrl() . '"><span>' . __($category->getName()) . '</span></a>';
                        $html .= $this->getSubCategoryDropdown($category->getId(), ($level + 1));
                        $html .= '</li>';
                    }
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }


    /**
     * @param $categoryId
     * @return null|string
     */
    public function getCategoryStaticWidth($categoryId)
    {
        $childCategorys = $this->getChildrenCategoryById($categoryId);
        $html = null;
        $level = 1;
        if ($childCategorys) {
            foreach (explode(',', $childCategorys) as $categoryId) {
                if ($categoryId) {
                    $category = $this->getCategoryById($categoryId);
                    if ($category) {
                        $parentClass = ($this->hasSubcategory($categoryId)) ? 'parent' : null;
                        $html .= '<li class="' . $this->checkCurrentCategory($category->getId()) . ' ui-menu-item level' . $level . ' ' . $parentClass . '">';
                        $html .= '<a class="level-top" href="' . $category->getUrl() . '">' . __($category->getName()) . '</a>';
                        $html .= $this->getSubCategoryStaticWidth($categoryId, ($level + 1));
                        $html .= '</li>';
                    }
                }
            }
        }
        return $html;
    }

    /**
     * @param $categoryId
     * @param $level
     * @return null|string
     */
    public function getSubCategoryStaticWidth($categoryId, $level)
    {
        $childCategorys = $this->getChildrenCategoryById($categoryId);
        $html = null;
        if ($childCategorys != null) {
            $html .= '<div class="open-children-toggle"></div>';
            $html .= '<ul class="subchildmenu submenu">';
            foreach (explode(',', $childCategorys) as $categoryId) {
                if ($categoryId) {
                    $category = $this->getCategoryById($categoryId);
                    if ($category) {
                        $parentClass = ($this->hasSubcategory($categoryId)) ? 'parent' : null;
                        $html .= '<li class="' . $this->checkCurrentCategory($category->getId()) . ' ui-menu-item level' . $level . ' ' . $parentClass . '"><a href="' . $category->getUrl() . '"><span>' . __($category->getName()) . '</span></a>';
                        $html .= $this->getSubCategoryStaticWidth($categoryId, ($level + 1));
                        $html .= '</li>';
                    }
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }


    /**
     * @param $categoryId
     * @return null|string
     */
    public function getCategoryFullWidth($categoryId)
    {
        $childCategorys = $this->getChildrenCategoryById($categoryId);
        $html = null;
        $level = 1;
        if ($childCategorys) {
            foreach (explode(',', $childCategorys) as $categoryId) {
                if ($categoryId) {
                    $category = $this->getCategoryById($categoryId);
                    if ($category) {
                        $parentClass = ($this->hasSubcategory($categoryId)) ? 'parent' : null;
                        $html .= '<li class="' . $this->checkCurrentCategory($category->getId()) . ' ui-menu-item level' . $level . ' ' . $parentClass . ' ">';
                        $html .= '<a class="level-top" href="' . $category->getUrl() . '">' . __($category->getName()) . '</a>';
                        $html .= $this->getSubCategoryFullWidth($categoryId, ($level + 1));
                        $html .= '</li>';
                    }
                }
            }
        }

        return $html;
    }

    /**
     * @param $categoryId
     * @param $level
     * @return null|string
     */
    public function getSubCategoryFullWidth($categoryId, $level)
    {
        $childCategorys = $this->getChildrenCategoryById($categoryId);
        $html = null;
        if ($childCategorys != null) {
            $html .= '<div class="open-children-toggle"></div>';
            $html .= '<ul class="subchildmenu submenu">';
            foreach (explode(',', $childCategorys) as $categoryId) {
                $category = $this->getCategoryById($categoryId);
                if ($category) {
                    $parentClass = ($this->hasSubcategory($categoryId)) ? 'parent' : null;
                    $html .= '<li class="' . $this->checkCurrentCategory($category->getId()) . ' ui-menu-item level' . $level . ' ' . $parentClass . '"><a href="' . $category->getUrl() . '"><span>' . __($category->getName()) . '</span></a>';
                    $html .= $this->getSubCategoryStaticWidth($categoryId, ($level + 1));
                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }


    /**
     * function get category html
     * @return null|string
     */
    public function getCategorys()
    {
        $groupData = $this->getGroupById();
        $html = null;
        if ($groupData) {
            foreach (explode(',', $groupData['categorys']) as $categoryId) {
                if ($categoryId) {
                    $itemCategorys = $this->itemCollectionFactory->create()
                        ->addFieldToFilter('category_id', array('eq' => $categoryId))
                        ->addFieldToFilter('status', array('eq' => 1))
                        ->addFieldToFilter('menu_type', array('eq' => 1));
                    if ($itemCategorys->getData()) {
                        foreach ($itemCategorys as $item) {
                            $html .= $this->getMegamenuHtml($item);
                            continue;
                        }
                    } else {
                        $category = $this->getCategoryById($categoryId);
                        if ($category) {
                            $parentClass = ($this->hasSubcategory($category->getId())) ? 'parent' : null;
                            $html .= '<li class="' . $this->checkCurrentCategory($category->getId()) . ' ui-menu-item level0 ' . $this->getMenuTypeGroup($groupData['menu_type']) . ' ' . $parentClass . ' ">';
                            $html .= '<a href="' . $category->getUrl() . '" class="level-top"><span>' . __($category->getName()) . '</span></a>';
                            if ($this->hasSubcategory($category->getId())) {
                                $html .= '<div class="open-children-toggle"></div>';
                            }
                            //get html of category
                            $html .= $this->getHtmlCategory($category,
                                $this->getMenuTypeGroup($groupData['menu_type']));
                            $html .= '</li>';
                        }
                    }
                }
            }
        }
        return $html;
    }


    /**
     * get Html of category
     * @param $item
     * @param $menuType
     * @return null|string
     */
    public function getHtmlCategory($category, $menuType)
    {
        $html = null;

        if ($this->hasSubcategory($category->getId())) {
            $html .= '<ul class="subchildmenu submenu">';
            $html .= $this->getCategoryDropdown($category->getId());
            $html .= '</ul>';
        }
        return $html;
    }

}
