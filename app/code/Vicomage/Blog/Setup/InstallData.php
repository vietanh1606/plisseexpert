<?php
/**
 * Copyright © 2015 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Setup;

use Vicomage\Blog\Model\Post;
use Vicomage\Blog\Model\PostFactory;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Post factory
     *
     * @var \Vicomage\Blog\Model\PostFactory
     */
    private $_postFactory;

    /**
     * Init
     *
     * @param \Vicomage\Blog\Model\PostFactory $postFactory
     */
    public function __construct(\Vicomage\Blog\Model\PostFactory $postFactory)
    {
        $this->_postFactory = $postFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $data = [
            'title' => 'Hello world!',
            'meta_keywords' => 'magento 2 blog',
            'meta_description' => 'Magento 2 blog default post.',
            'identifier' => 'hello-world',
            'content_heading' => 'Hello world!',
            'content' => '<p>Welcome to <a title="Vicomage - solutions for Magento 2" href="http://Vicomage.com/" target="_blank">Vicomage</a> blog extension for Magento&reg; 2. This is your first post. Edit or delete it, then start blogging!</p>
<p><!-- pagebreak --></p>
<p>Please also read&nbsp;<a title="Magento 2 Blog online documentation" href="http://Vicomage.com/docs/magento-2-blog/" target="_blank">Online documentation</a>&nbsp;and&nbsp;<a href="http://Vicomage.com/blog/add-read-more-tag-to-blog-post-content/" target="_blank">How to add "read more" tag to post content</a></p>
<p>Follow Vicomage on:</p>
<p><a title="Blog Extension for Magento 2 code" href="https://github.com/Vicomage/module-blog" target="_blank">GitHub</a>&nbsp;|&nbsp;<a href="https://twitter.com/magento2fan" target="_blank">Twitter</a>&nbsp;|&nbsp;<a href="https://www.facebook.com/Vicomage/" target="_blank">Facebook</a>&nbsp;|&nbsp;<a href="https://plus.google.com/+Vicomage_Magento_2/posts/" target="_blank">Google +</a></p>',
            'store_ids' => [0]
        ];

        $this->_postFactory->create()->setData($data)->save();
    }

}
