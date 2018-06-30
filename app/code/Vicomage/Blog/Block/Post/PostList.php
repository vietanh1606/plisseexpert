<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Block\Post;

/**
 * Blog post list block
 */
class PostList extends \Vicomage\Blog\Block\Post\PostList\AbstractList
{
    /**
     * Block template file
     * @var string
     */
	protected $_defaultToolbarBlock = 'Vicomage\Blog\Block\Post\PostList\Toolbar';

    /**
     * Retrieve post html
     * @param  \Vicomage\Blog\Model\Post $post
     * @return string
     */
    public function getPostHtml($post)
    {
    	return $this->getChildBlock('blog.posts.list.item')->setPost($post)->toHtml();
    }

    /**
     * Retrieve Toolbar Block
     * @return \Vicomage\Blog\Block\Post\PostList\Toolbar
     */
    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();

        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, uniqid(microtime()));
        return $block;
    }

    /**
     * Retrieve Toolbar Html
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Before block to html
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getPostCollection();

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);
        $this->setChild('toolbar', $toolbar);

        return parent::_beforeToHtml();
    }

}
