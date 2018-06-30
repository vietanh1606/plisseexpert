<?php
/**
 * Copyright © 2015 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Block\Sidebar;

/**
 * Blog sidebar categories block
 */
class Recent extends \Vicomage\Blog\Block\Post\PostList\AbstractList
{
    use Widget;

    /**
     * @var string
     */
    protected $_widgetKey = 'recent_posts';

    /**
     * @return $this
     */
    public function _construct()
    {
        $this->setPageSize(
            (int) $this->_scopeConfig->getValue(
                'vicomage_mfblog/sidebar/'.$this->_widgetKey.'/posts_per_page',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
        return parent::_construct();
    }

    /**
     * Retrieve block identities
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Cms\Model\Block::CACHE_TAG . '_blog_recent_posts_widget'  ];
    }

}
