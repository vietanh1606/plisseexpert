<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Data\Tree\Node;

/**
 * Blog observer
 */
class PageBlockHtmlTopmenuBethtmlBeforeObserver implements ObserverInterface
{
    /**
     * Show top menu item config path
     */
    const XML_PATH_TOP_MENU_SHOW_ITEM = 'vicomage_mfblog/top_menu/show_item';

    /**
     * Top menu item text config path
     */
    const XML_PATH_TOP_MENU_ITEM_TEXT = 'vicomage_mfblog/top_menu/item_text';

    /**
     * @var \Vicomage\Blog\Model\Url
     */
    protected $_url;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Vicomage\Blog\Model\Url $url
     */
    public function __construct(
        \Vicomage\Blog\Model\Url $url,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_url = $url;
    }

    /**
     * Page block html topmenu gethtml before
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_scopeConfig->isSetFlag(static::XML_PATH_TOP_MENU_SHOW_ITEM, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            return;
        }

        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getMenu();
        $block = $observer->getBlock();

        $tree = $menu->getTree();
        $data = [
            'name'      => $this->_scopeConfig->getValue(static::XML_PATH_TOP_MENU_ITEM_TEXT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            'id'        => 'Vicomage-blog',
            'url'       => $this->_url->getBaseUrl(),
            'is_active' => ($block->getRequest()->getModuleName() == 'blog'),
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);
    }
}
