<?php
namespace Vicomage\General\Block\Adminhtml\System\Config\Form\Field;

class Color extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;
    protected $develop;
    protected $_template = 'Vicomage_General::system/config/form/field/array.phtml';
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
    )
    {
        $this->_elementFactory  = $elementFactory;
        parent::__construct($context,$data);
    }

    protected function _construct(){
        $this->addColumn('title',
            [
                'label' =>  __('Title'),
                'style' =>  $this->configBoolDevelop() ? 'with:180px' : 'display:none',
                'class' =>  'vicomage_title_color'
            ]
        );

        $this->addColumn('element',
            [
                'label' =>  __('Element'),
                'style' =>  'with:180px',
                'class' =>  'vicomage_element_color',
                'develop' => $this->configDevelop()
            ]
        );

        $this->addColumn('color',
            [
                'label' =>  __('Color'),
                'style' =>  'with:210px',
                'class' =>  $this->getClass(),
            ]
        );

        $this->addColumn('bgcolor',
            [
                'label' =>  __('Background-Color'),
                'style' =>  'with:210px',
                'class' =>  $this->getClass(),
            ]
        );

        $this->addColumn('bdcolor',
            [
                'label' =>  __('Border-Color'),
                'style' =>  'with:210px',
                'class' =>  $this->getClass(),
            ]
        );

        $this->_addAfter = $this->configBoolDevelop();
        $this->_addButtonLabel = __('Add');
        parent::_construct();
    }


    /**
     * @return string
     */
    public function getClass(){
        return 'form-control color';
    }


    /**
     * @return string
     */
    public function configDevelop(){
        return ($this->_scopeConfig->getValue('vicomage_color_config/general_config/config_as_developer', \Magento\Store\Model\ScopeInterface::SCOPE_STORES, $this->getCurrentStore())) ? 'enable' : 'disable';
    }


    /**
     * @return mixed
     */
    public function configBoolDevelop(){
        return $this->_scopeConfig->getValue('vicomage_color_config/general_config/config_as_developer', \Magento\Store\Model\ScopeInterface::SCOPE_STORES, $this->getCurrentStore());
    }


    /**
     * @param string $name
     * @param array $params
     */
    public function addColumn($name, $params)
    {
        $this->_columns[$name] = [
            'label' => $this->_getParam($params, 'label', 'Column'),
            'size' => $this->_getParam($params, 'size', false),
            'style' => $this->_getParam($params, 'style'),
            'class' => $this->_getParam($params, 'class'),
            'develop' => $this->_getParam($params, 'develop'),
            'renderer' => false,
        ];
        if (!empty($params['renderer']) && $params['renderer'] instanceof \Magento\Framework\View\Element\AbstractBlock) {
            $this->_columns[$name]['renderer'] = $params['renderer'];
        }
    }



    /**
     * @param string $columnName
     * @return string
     * @throws \Exception
     */
    public function renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new \Exception('Wrong column name specified.');
        }
        $column = $this->_columns[$columnName];
        $inputName = $this->_getCellInputElementName($columnName);

        if ($column['renderer']) {
            return $column['renderer']->setInputName(
                $inputName
            )->setInputId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setColumnName(
                $columnName
            )->setColumn(
                $column
            )->toHtml();
        }

        if($column['class'] === 'vicomage_title_color' && $this->configBoolDevelop() == false) {
            return '<input type="text" id="' . $this->_getCellInputElementId(
                    '<%- _id %>',
                    $columnName
                ) .
                '"' .
                ' name="' .
                $inputName .
                '" value="<%- ' .
                $columnName .
                ' %>" ' .
                ($column['size'] ? 'size="' .
                    $column['size'] .
                    '"' : '') .
                ' class="' .
                (isset(
                    $column['class']
                ) ? $column['class'] : 'input-text') . '"' . (isset(
                    $column['style']
                ) ? ' style="' . $column['style'] . '"' : '') . '/><label><%- ' .
                $columnName .
                ' %></label>';
        }else{
            return '<input type="text" id="' . $this->_getCellInputElementId(
                    '<%- _id %>',
                    $columnName
                ) .
                '"' .
                ' name="' .
                $inputName .
                '" value="<%- ' .
                $columnName .
                ' %>" ' .
                ($column['size'] ? 'size="' .
                    $column['size'] .
                    '"' : '') .
                ' class="' .
                (isset(
                    $column['class']
                ) ? $column['class'] : 'input-text') . '"' . (isset(
                    $column['style']
                ) ? ' style="' . $column['style'] . '"' : '') . '/>';
        }
    }

    public function getCurrentStore()
    {
        $store = $this->getRequest()->getParam('store');
        if(isset($store)) {
            return $store;
        }
        return null;
    }
}
