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
class ProxiBlue_ShoppingDotCom_Block_Catalog_Product extends Mage_Adminhtml_Block_Catalog_Product
{

    protected function _prepareLayout()
    {
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add Product'),
            'onclick' => "setLocation('{$this->getUrl('*/*/new')}')",
            'class'   => 'add'
        ));
		
        $this->_addButton('export_shoppingdotcom', array(
            'label'   => Mage::helper('catalog')->__('Export ShoppingDotCom'),
            'onclick' => "setLocation('{$this->getUrl('*/*/exportshoppingdotcom')}')",
            'class'   => 'add'
        ));
        
        $this->setChild('grid', $this->getLayout()->createBlock('adminhtml/catalog_product_grid', 'product.grid'));
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}

