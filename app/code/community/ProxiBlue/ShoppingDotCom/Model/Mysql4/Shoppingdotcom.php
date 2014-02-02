<?php
/**
 * Magento
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_ShoppingDotCom
 * @author     Lucas van Staden (support@proxiblue.com.au)
 * @copyright  Copyright (c) 2010 www.prociblue.com.au
 *
 */
class ProxiBlue_ShoppingDotCom_Model_Mysql4_ShoppingDotCom extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the addressfromcode_id refers to the key field in your database table.
        $this->_init('shoppingdotcom/shoppingdotcom', 'shoppingdotcom_id');
    }
}