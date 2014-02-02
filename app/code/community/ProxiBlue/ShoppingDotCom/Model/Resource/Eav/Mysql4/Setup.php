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
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * 
 */
class ProxiBlue_ShoppingDotCom_Model_Resource_Eav_Mysql4_Setup extends Mage_Eav_Model_Entity_Setup
{
    
    /**
     * Add attribute to an entity type
     *
     * If attribute is system will add to all existing attribute sets
     *
     * @param string|integer $entityTypeId
     * @param string $code
     * @param array $attr
     * @return Mage_Eav_Model_Entity_Setup
     */
    public function addAttribute($entityTypeId, $code, array $attr)
    {
        $entityTypeId = $this->getEntityTypeId($entityTypeId);
        $data = array(
            'entity_type_id'            => $entityTypeId,
            'attribute_code'            => $code,
            'backend_model'             => $this->_getValue($attr, 'backend', ''),
            'backend_type'              => $this->_getValue($attr, 'type', 'varchar'),
            'backend_table'             => $this->_getValue($attr, 'table', ''),
            'frontend_model'            => $this->_getValue($attr, 'frontend', ''),
            'frontend_input'            => $this->_getValue($attr, 'input', 'text'),
            'frontend_input_renderer'   => $this->_getValue($attr, 'input_renderer', ''),
            'frontend_label'            => $this->_getValue($attr, 'label', ''),
            'source_model'              => $this->_getValue($attr, 'source', ''),
            'is_global'                 => $this->_getValue($attr, 'global', 1),
            'is_visible'                => $this->_getValue($attr, 'visible', 1),
            'is_required'               => $this->_getValue($attr, 'required', 1),
            'is_user_defined'           => $this->_getValue($attr, 'user_defined', 0),
            'default_value'             => $this->_getValue($attr, 'default', ''),
            'is_searchable'             => $this->_getValue($attr, 'searchable', 0),
            'is_filterable'             => $this->_getValue($attr, 'filterable', 0),
            'is_comparable'             => $this->_getValue($attr, 'comparable', 0),
            'is_visible_on_front'       => $this->_getValue($attr, 'visible_on_front', 0),
            'is_html_allowed_on_front'  => $this->_getValue($attr, 'is_html_allowed_on_front', 0),
            'is_visible_in_advanced_search'
                                        => $this->_getValue($attr, 'visible_in_advanced_search', 0),
            'is_used_for_price_rules'   => $this->_getValue($attr, 'used_for_price_rules', 1),
            'is_filterable_in_search'   => $this->_getValue($attr, 'filterable_in_search', 0),
            'used_in_product_listing'   => $this->_getValue($attr, 'used_in_product_listing', 0),
            'used_for_sort_by'          => $this->_getValue($attr, 'used_for_sort_by', 0),
            'is_unique'                 => $this->_getValue($attr, 'unique', 0),
            'apply_to'                  => $this->_getValue($attr, 'apply_to', ''),
            'is_configurable'           => $this->_getValue($attr, 'is_configurable', 1),
            'note'                      => $this->_getValue($attr, 'note', ''),
            'position'                  => $this->_getValue($attr, 'position', 0),
        );

        $sortOrder = isset($attr['sort_order']) ? $attr['sort_order'] : null;

        if ($id = $this->getAttribute($entityTypeId, $code, 'attribute_id')) {
            $this->updateAttribute($entityTypeId, $id, $data, null, $sortOrder);
        } else {
            $this->_insertAttribute($data);
        }

        if (!empty($attr['group'])) {
               $sets = $this->_conn->fetchAll('select * from '.$this->getTable('eav/attribute_set').' where entity_type_id=?', $entityTypeId);
            foreach ($sets as $set) {
                if (!empty($attr['attribute_set'])) {
                    if ($attr['attribute_set'] == $set['attribute_set_name']) {
                        $this->addAttributeGroup($entityTypeId, $set['attribute_set_id'], $attr['group']);
                        $this->addAttributeToSet($entityTypeId, $set['attribute_set_id'], $attr['group'], $code, $sortOrder);
                    }
                } else {
                    $this->addAttributeGroup($entityTypeId, $set['attribute_set_id'], $attr['group']);
                    $this->addAttributeToSet($entityTypeId, $set['attribute_set_id'], $attr['group'], $code, $sortOrder);
                }
            }
        }

        if (empty($attr['is_user_defined'])) {
               $sets = $this->_conn->fetchAll('select * from '.$this->getTable('eav/attribute_set').' where entity_type_id=?', $entityTypeId);
            foreach ($sets as $set) {
                if (!empty($attr['attribute_set'])) {
                    if ($attr['attribute_set'] == $set['attribute_set_name']) {
                        $this->addAttributeToSet($entityTypeId, $set['attribute_set_id'], $attr['group'], $code, $sortOrder);
                    }
                } else {
                    $this->addAttributeToSet($entityTypeId, $set['attribute_set_id'], $this->_generalGroupName, $code, $sortOrder);
                }
            }
        }


        if (isset($attr['option']) && is_array($attr['option'])) {
            $option = $attr['option'];
            $option['attribute_id'] = $this->getAttributeId($entityTypeId, $code);
            $this->addAttributeOption($option);
        }

        return $this;
    }
	/**
     * @return array
     */
    public function getDefaultEntities()
    {
        return array(
            'catalog_product' => array(
               'entity_model'      => 'catalog/product',
                'attribute_model'   => 'catalog/resource_eav_attribute',
                'table'             => 'catalog/product',
                'additional_attribute_table' => 'catalog/eav_attribute',
                'entity_attribute_collection' => 'catalog/product_attribute_collection',
                'attributes'        => array(
                    'pb_shopping_dot_com' => array(
                        'attribute_set'     => 'Default',
                    	'group'             => 'ShoppingDotCom',
                        'label'             => 'ShoppingDotCom Category Map',
                        'type'              => 'int',
                        'input'             => 'select',
                        'default'           => '0',
                        'class'             => '',
                        'backend'           => '',
                        'frontend'          => '',
                        'source'            => 'shoppingdotcom/product_attribute_source_unit',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false
                    ),
                    'pb_exclude_from_feed' => array(
                        'attribute_set'     => 'Default',
                    	'group'             => 'ShoppingDotCom',
                        'label'             => 'Skip product in feed',
                        'type'              => 'int',
                        'input'             => 'select',
                        'default'           => '0',
                        'class'             => '',
                        'backend'           => '',
                        'frontend'          => '',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false
                    ),
                    'pb_include_mpn' => array(
                        'attribute_set'     => 'Default',
                    	'group'             => 'Field Mappings',
                        'label'             => 'Include MPN Field',
                        'type'              => 'int',
                        'input'             => 'select',
                        'default'           => '0',
                        'class'             => '',
                        'backend'           => '',
                        'frontend'          => '',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false
                    ),
                    /*'pb_mpn_field_map' => array(
                        'attribute_set'     => 'Default',
                    	'group'             => 'Field Mappings',
                        'label'             => 'MPN maps to',
                        'type'              => 'int',
                        'input'             => 'select',
                        'default'           => '0',
                        'class'             => '',
                        'backend'           => '',
                        'frontend'          => '',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false
                    ),*/
               )
           ),
           'catalog_category' => array(
               'entity_model'      => 'catalog/category',
                'attribute_model'   => 'catalog/resource_eav_attribute',
                'table'             => 'catalog/category',
                'additional_attribute_table' => 'catalog/eav_attribute',
                'entity_attribute_collection' => 'catalog/category_attribute_collection',
                'attributes'        => array(
                    'pb_shopping_dot_com_category_global' => array(
                        'attribute_set'     => 'Default',
                    	'group'             => 'ShoppingDotCom',
                        'label'             => 'ShoppingDotCom Category Map',
                        'type'              => 'int',
                        'input'             => 'select',
                        'default'           => '0',
                        'class'             => '',
                        'backend'           => '',
                        'frontend'          => '',
                        'source'            => 'shoppingdotcom/product_attribute_source_unit',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false
           			),
           		)
           	)		
      );
    }   
}