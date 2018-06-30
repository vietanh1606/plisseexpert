<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */
namespace Vicomage\Blog\Block\Post\View;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog post view rich snippets
 */
class Richsnippets extends \Vicomage\Blog\Block\Post\AbstractPost
{
    /**
     * @param  array
     */
    protected $_options;

    /**
     * Retrieve snipet params
     *
     * @return array
     */
    public function getOptions()
    {
        if ($this->_options === null) {
            $post = $this->getPost();
            $this->_options = array(
                '@context' => 'http://schema.org',
                '@type' => 'BlogPosting',
                '@id' => $post->getPostUrl(),
                'headline' => $post->getTitle(),
                'description' => $post->getMetaDescription(),
                'datePublished' => $post->getPublishDate('c'),
            );
        }

        return $this->_options;
    }

    /**
     * Render html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        return '<script type="application/ld+json">'
            . json_encode($this->getOptions())
            . '</script>';
    }
}
