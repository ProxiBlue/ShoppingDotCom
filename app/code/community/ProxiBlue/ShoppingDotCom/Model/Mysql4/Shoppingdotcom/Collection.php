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
class ProxiBlue_ShoppingDotCom_Model_Mysql4_ShoppingDotCom_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('shoppingdotcom/shoppingdotcom');
    }
    
	/**
	 * Add filter by option id(s).
	 *
	 * @param mixed $optionIds
	 * @return LizEarle_AddressFromCode_Model_Mysql4_AddressFromCode_Collection
	 */
	public function addIdFilter($optionIds)
	{
		$condition = '';
		if (is_array($optionIds)) {
			if (empty($optionIds)) {
				$condition = '';
			} else {
				$condition = array('in' => $optionIds);
			}
		} elseif (is_numeric($optionIds)) {
			$condition = $optionIds;
		} elseif (is_string($optionIds)) {
			$ids = explode(',', $optionIds);
			if (empty($ids)) {
				$condition = $optionIds;
			} else {
				$condition = array('in' => $ids);
			}
		}
		$this->addFieldToFilter('shoppingdotcom_id', $condition);
		return $this;
	}
	
	/**
	 * Add filter for store id.
	 *
	 * @param string StoreID
	 * @return LizEarle_AddressFromCode_Model_Mysql4_AddressFromCode_Collection
	 */
/*
	public function addStoreFilter($storeid)
	{
		if (is_string($storeid)) {
			$condition = array('in' => $storeid);
			$this->addFieldToFilter('websites', $condition);
		}
		return $this;
	}*/
}