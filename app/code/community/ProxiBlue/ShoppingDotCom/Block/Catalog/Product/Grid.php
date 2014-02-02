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
Class ProxiBlue_ShoppingDotCom_Block_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid {

	protected function _prepareCollection(){
    $store = $this->_getStore();
    $collection = Mage::getModel('catalog/product')->getCollection();

    $collection
    ->addAttributeToSelect('sku')
    ->addAttributeToSelect('name')
    ->addAttributeToSelect('attribute_set_id')
    ->addAttributeToSelect('type_id')
    ->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');          
    $collection->addAttributeToSelect('pb_shopping_dot_com');

    if ($store->getId()) {
      $collection->addStoreFilter($store);
      $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
      $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
      $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
      $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
    } else {
      $collection->addAttributeToSelect('price');
      $collection->addAttributeToSelect('status');
      $collection->addAttributeToSelect('visibility');
    }

    $this->setCollection($collection);

    Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    $this->getCollection()->addWebsiteNamesToResult();

    $collection->addAttributeToSelect('visibility');

    return $this;
  }

  protected function _prepareColumns()
  {
    parent::_prepareColumns();
      $this->addColumn('ShoppingDotCom',
      array(
                'header'=> Mage::helper('catalog')->__('ShoppingDotCom'),
                'width' => '80px',
                'index' => 'pb_shopping_dot_com',
                'type'  => 'options',
                'options' => Mage::getModel('shoppingdotcom/product_attribute_source_unit')->getOptionArray(),
      ));
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
  }
}

?>