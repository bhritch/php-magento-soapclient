<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
 *  @Description : This is an API wrapper class library that connects to Magento
 *  @Author      : Ritchwel Binaohan 
 */

class Magento 
{
    protected $_url = "<magento_url>/api/soap/?wsdl";
    protected $_key = "<key>";
    protected $_secret = "<secret>";
    protected $_options = array(
                            'cache_wsdl' => WSDL_CACHE_NONE
                        );
    protected $_client;
    protected $_session;

    /* Magento Methods */
    protected $_product_list = "product.list";
    protected $_product_info = "product.info";
    protected $_product_stock_list = "product_stock.list";

    protected $_catatalog_product_create_image = "catalog_product_attribute_media.create";
    protected $_catalog_product_create = "catalog_product.create";
    protected $_catalog_product_update = "catalog_product.update";

    /*
     * Initialized to Magento
     *
     */
    public function __construct() 
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $this->_client = new SoapClient($this->_url, $this->_options);
            $this->_session = $this->_client->login($this->_key, $this->_secret);
        } catch ( SoapFault $error ) {
            return $error->getMessage();
        }
    }

    /* @function: product_info
     * @description : This will show product list 
     *
     */
    public function product_list()
    {
        return $this->call($this->_product_list);
    }

    /* @function: product_info
     * @params  : array({sku}) || array('{key} => {$value}')
     * @description : This will show product info 
     */
    public function product_info($params)
    {
        return $this->call($this->_product_info, $params);
    }

    /* @function: product_stock_list
     * @params  : array({sku}) || array('{key} => {$value}')
     * @description : This will show product stock info 
     */
    public function product_stock_list($params)
    {
        return $this->call($this->_product_stock_list, $params);
    }

    /* @function: create_product
     * @params  : array({data})
     * @description : This will create product in magento product catalog
     */
    public function create_product($params)
    {   
        return $this->call($this->_catalog_product_create, $params);
    }

    /* @function: update_product
     * @params  : array('{product_sku}', array({data}))
     * @description : This will update product info in magento product catalog
     */
    public function update_product($params)
    {   
        return $this->call($this->_catalog_product_update, $params);
    }

    /* @function: create_product_image
     * @params  : array({data})
     * @description : this will add product image from magento
     */
    public function create_product_image($params)
    {
        return $this->call($this->_catatalog_product_create_image, $params);
    }

    /* @function: call
     * @params  : $method = protected_method define above
                  $params = this data will be use as filter on product catalog 
     * @description : This will execute the method 
     */
    private function call($method = "", $params = array())
    {
        try {
            return $this->_client->call($this->_session, $method, $params);
        } catch (SoapFault $error) {
		    return $error->getMessage();
        }
    }

}