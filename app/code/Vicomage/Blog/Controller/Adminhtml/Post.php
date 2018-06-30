<?php
/**
 * Copyright © 2015 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Controller\Adminhtml;

/**
 * Admin blog post edit controller
 */
class Post extends Actions
{
	/**
	 * Form session key
	 * @var string
	 */
    protected $_formSessionKey  = 'blog_post_form_data';

    /**
     * Allowed Key
     * @var string
     */
    protected $_allowedKey      = 'Vicomage_Blog::post';

    /**
     * Model class name
     * @var string
     */
    protected $_modelClass      = 'Vicomage\Blog\Model\Post';

    /**
     * Active menu key
     * @var string
     */
    protected $_activeMenu      = 'Vicomage_Blog::post';

    /**
     * Status field name
     * @var string
     */
    protected $_statusField     = 'is_active';

    /**
     * Save request params key
     * @var string
     */
    protected $_paramsHolder 	= 'post';
}
