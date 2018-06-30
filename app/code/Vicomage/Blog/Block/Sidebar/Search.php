<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Block\Sidebar;

/**
 * Blog sidebar categories block
 */
class Search extends \Magento\Framework\View\Element\Template
{
	use Widget;

	/**
     * @var \Vicomage\Blog\Model\Url
     */
    protected $_url;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Vicomage\Blog\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Vicomage\Blog\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_url = $url;
    }

	/**
     * @var string
     */
    protected $_widgetKey = 'search';

	/**
	 * Retrieve query
	 * @return string
	 */
	public function getQuery()
	{
		return urldecode($this->getRequest()->getParam('q', ''));
	}

	/**
	 * Retrieve serch form action url
	 * @return string
	 */
	public function getFormUrl()
	{
		return $this->_url->getUrl('', \Vicomage\Blog\Model\Url::CONTROLLER_SEARCH);
	}

}
