<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Model;

/**
 * Post management model
 */
class PostManagement extends AbstractManagement
{
    /**
     * @var \Vicomage\Blog\Model\PostFactory
     */
    protected $_itemFactory;

    /**
     * Initialize dependencies.
     *
     * @param \Vicomage\Blog\Model\PostFactory $postFactory
     */
    public function __construct(
        \Vicomage\Blog\Model\PostFactory $postFactory
    ) {
        $this->_itemFactory = $postFactory;
    }

}
