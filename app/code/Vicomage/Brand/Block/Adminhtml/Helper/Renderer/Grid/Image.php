<?php
namespace Vicomage\Brand\Block\Adminhtml\Helper\Renderer\Grid;

class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;


    protected $_shopbrandFactory;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Context              $context
     * @param \Magento\Store\Model\StoreManagerInterface  $storeManager
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Vicomage\Brand\Model\ShopbrandFactory $shopbrandFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_shopbrandFactory  = $shopbrandFactory;
    }

    /**
     * Render action.
     *
     * @param \Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $storeViewId = $this->getRequest()->getParam('store');
        $brand = $this->_shopbrandFactory->create()->setStoreViewId($storeViewId)->load($row->getId());
        $srcImage = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $brand->getImage();

        return '<image width="150" height="50" src ="'.$srcImage.'" alt="'.$brand->getImage().'" >';
    }
}
