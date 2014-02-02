<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   LizEarle
 * @package    LizEarle_BasketShipping
 * @author     Lucas van Staden (lvanstaden at lizearle.com / lucas at vanstaden.com.au)
 * @copyright  Copyright (c) 2008 Lizearle Beauty Inc. Ltd. (http://www.lizearle.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * 
 */

/**
 * Set attribute options for dropdown box in Special Shipping configuration in products area.
 *
 */
class ProxiBlue_ShoppingDotCom_Model_Product_Attribute_Source_Unit extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
  
  /**
   * Populate selection box in catalog product attributes for ShoppingDotCom categories selection
   *
   * @return array
   */
  public function getAllOptions(){
  	$shoppingDotComCollection = Mage::getModel('shoppingdotcom/shoppingdotcom')->getCollection();
    if (!$this->_options) {
  		$this->_options[]=array(
                          'value' => '-1',
                          'label' => ''
      		);
      	foreach ($shoppingDotComCollection as $shoppingDotComEntry){
    		$label = $shoppingDotComEntry->getShoppingdotcom_cat1()." / ".
    				 $shoppingDotComEntry->getShoppingdotcom_cat2();
  			if (strlen(trim($shoppingDotComEntry->getShoppingdotcom_cat3())) > 0){
      			$label .= " / ".$shoppingDotComEntry->getShoppingdotcom_cat3();
      		}		 
  			$this->_options[]=array(
                          'value' => $shoppingDotComEntry->getId(),
                          'label' => $label
      		);
      		
    	}
    }
    return $this->_options;
  }
  
  /**
   * Populate admin catalog grid filter selection
   *
   * @return array
   */
  public function getOptionArray(){
      $options = $this->getAllOptions();  
      foreach ($options as $key => $option){
        if (strlen(trim($option['label'])) > 0)
          $resultarray[$key] = $option['label'];
      }
      return $resultarray;
    }
}
?>