<?php
namespace Vicomage\Core\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{


    /**
     * create config device array
     * @return array
     */
    public function getConfigDevice()
    {
        return array(
            1201=>'visible',
            1200=>'desktop',
            992=>'notebook',
            769=>'tablet',
            641=>'landscape',
            481=>'portrait',
            361=>'mobile',
            1=>'mobile'
        );
    }


    /**
     * return slider config array
     * @return array
     */
    public function getSlideOptions()
    {
        return array(
            'autoplay' => false,
            'arrows' => true,
            'autoplayspeed' => 3000,
            'dots' => false,
            'infinite' => false,
            'padding' => 20,
            'vertical' => false,
            'vertical-Swiping' => false,
            'responsive' => null,
            'rows' => 1,
            'slides-To-Show' => 4,
            'swipe-To-Slide' => true,
            'speed' => 300,
            'width-image' => 300,
            'height-image' => 300,
            'quickview' => null,
        );
    }


    /**
     * this config will add data to ajax to set when get product by ajax
     * @return array
     */
    public function getAjaxCfg(){
        return array(
            'limit',
            'speed',
            'timer',
            'timer_type',
            'cart',
            'compare',
            'wishlist',
            'review',
            'types',
            'quickview',
            'width-image',
            'height-image',
            'conditions_encoded',
            'product_category_collection'
        );
    }


    /**
     * config when slider don't using
     * @return array
     */
    public function getConfigJs(){
        return array(
            'quickview',
        );
    }


    public function getPrcents()
    {
        return array(
            1 => '100%',
            2 => '50%',
            3 => '33.333333333%',
            4 => '25%',
            5 => '20%',
            6 => '16.666666666%',
            7 => '14.285714285%',
            8 => '12.5%'
        );
    }


    /**
     * create css for grid no slider
     * @param $listCfg
     * @return string
     */
    public function getStyle($listCfg,$groupClass){
        $devices = $this->getConfigDevice();ksort($devices);
        $prcents = $this->getPrcents();
        $styles = '';
        $max = count($devices);
        $i   = $tmp= 1;
        foreach ($devices as $key => $value) {
            $tmpKey = ( $i == 1 || $i == $max) ? $value : current($devices);
            if($i >1){
                $styles .= ' @media (min-width: '. $tmp .'px) and (max-width: ' . ($key-1) . 'px) {.'.$groupClass.' .products-grid .products .product-item{ padding: 0 15px;float: left;width: '.$prcents[$listCfg[$value]] .'} .'.$groupClass.' .products-grid .products .product-item:nth-child(' .$listCfg[$value]. 'n+1){clear: left;}}';
                next($devices);
            }
            if( $i == $max ) $styles .= ' @media (min-width: ' . $key . 'px) {.'.$groupClass.' .products-grid .products .product-item{ padding: 0 15px;float: left;width: '.$prcents[$listCfg[$value]] .'} .'.$groupClass.' .products-grid .products .product-item:nth-child(' .$listCfg[$value]. 'n+1){clear: left;}}';
            $tmp = $key;
            $i++;
        }

        return $styles;
    }
}
