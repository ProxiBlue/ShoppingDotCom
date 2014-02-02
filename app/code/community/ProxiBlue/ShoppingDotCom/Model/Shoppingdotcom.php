<?php

class ProxiBlue_ShoppingDotCom_Model_ShoppingDotCom extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('shoppingdotcom/shoppingdotcom');
    }
    
	/**
     * Create product feed xml file
     */
    public function exportshoppingdotcomAction()
    {
 
    	$data = array();
    	$productCollection = Mage::getModel('catalog/product')->getCollection()
										->addAttributeToFilter('status', 1) // we only want enabled products
										->addAttributeToFilter('visibility', 4) //and which is visible in the catalog search
										->addAttributeToSelect('*')
										->load(); 
		
		$node =	"<?xml version=\"1.0\"?>\n<Products>\n";
		foreach($productCollection as $product){
			if ($product->getPbExcludeFromFeed() == 1){
				continue;
			}
			/**
			<MPN></MPN>	
			<UPC></UPC>	
			<EAN></EAN>	
			<ISBN></ISBN>
			<Stock_Description></Stock_Description>	
			<Product_Bullet_Point_1></Product_Bullet_Point_1>	
			<Product_Bullet_Point_2></Product_Bullet_Point_2>	
			<Product_Bullet_Point_3></Product_Bullet_Point_3>	
			<Product_Bullet_Point_4></Product_Bullet_Point_4>	
			<Product_Bullet_Point_5></Product_Bullet_Point_5>
			<Alternative_Image_URL_1></Alternative_Image_URL_1>	
			<Alternative_Image_URL_2></Alternative_Image_URL_2>	
			<Alternative_Image_URL_3></Alternative_Image_URL_3>	
			<Alternative_Image_URL_4></Alternative_Image_URL_4>	
			<Alternative_Image_URL_5></Alternative_Image_URL_5>		
			<Style></Style>
			<Condition></Condition>	
			<Gender></Gender>	
			<Department></Department>	
			<Age_Range></Age_Range>
			<Material></Material>	
			<Format></Format>	
			<Team></Team>	
			<League></League>	
			<Fan_Gear_Type></Fan_Gear_Type>	
			<Software_Platform></Software_Platform>	
			<Software_Type></Software_Type>	
			<Watch_Display_Type></Watch_Display_Type>	
			<Cell_Phone_Type></Cell_Phone_Type>	
			<Cell_Phone_Service_Provider></Cell_Phone_Service_Provider>	
			<Cell_Phone_Plan_Type></Cell_Phone_Plan_Type>	
			<Usage_Profile></Usage_Profile>	
			<Size></Size>	
			<Size_Unit_of_Measure></Size_Unit_of_Measure>	
			<Product_Length></Product_Length>	
			<Length_Unit_of_Measure></Length_Unit_of_Measure>	
			<Product_Width></Product_Width >	
			<Width_Unit_of_Measure></Width_Unit_of_Measure>	
			<Product_Height></Product_Height>	
			<Height_Unit_of_Measure></Height_Unit_of_Measure>	
			<Product_Weight></Product_Weight>	
			<Weight_Unit_of_Measure></Weight_Unit_of_Measure>	
			<Unit_Price></Unit_Price>	
			<Top_Seller_Rank></Top_Seller_Rank>	
			<Product_Launch_Date></Product_Launch_Date>	
			<Shipping_Rate></Shipping_Rate>	
			<Shipping_Weight></Shipping_Weight>	
			<Zip_Code></Zip_Code>	
			<Estimated_Ship_Date></Estimated_Ship_Date>	
			<Sale_Price></Sale_Price>	
			<Sale_Start_DateTime></Sale_Start_DateTime>	
			<Sale_Expiration_DateTime></Sale_Expiration_DateTime>	
			<Coupon_Code></Coupon_Code>	
			<Coupon_Code_Description></Coupon_Code_Description>	
			<Merchandising_Type></Merchandising_Type>	
			<Related_Products></Related_Products>	
			<Color>".$product->getColor()."</Color>
			 */
			$node .="\t<Product>		
			<Merchant_SKU>".$product->getSku()."</Merchant_SKU>		
			<Manufacturer>".$product->getResource()->getAttribute('manufacturer')->getFrontend()->getValue($product)."</Manufacturer>	
			<Product_Name><![CDATA[".$product->getName()."]]></Product_Name>	
			<Product_URL>".$product->getProductUrl()."</Product_URL> 	
			<Price>".Mage::helper('core')->formatPrice($product->getPrice(), false)."</Price> 	
			";	
			$categoryData = '';
			$catOveride = '';
				foreach($product->getCategoryIds() as $_categoryId){
					$category = Mage::getModel('catalog/category')->load($_categoryId);
					if (strlen(trim($category->getPbShoppingDotComCategoryGlobal())) > 0){
						$catOveride = $category->getPbShoppingDotComCategoryGlobal();
					}
					$categoryData.=$category->getName().', ';
		    	}
		    // product level overrides category level
		    if (strlen(trim($product->getPbShoppingDotCom())) > 0){	
		    	$catOveride = $product->getPbShoppingDotCom();
		    }	
			if (strlen(trim($catOveride)) > 0){
				$addSubCatEnd = False;
				$shoppingDotComCategory = Mage::getModel('shoppingdotcom/shoppingdotcom')->load($catOveride);
				$productType = "<Product_Type><![CDATA[";
				if (strlen(trim($shoppingDotComCategory->getShoppingdotcomCat1())) > 0){
					$node .= "\t\t<Category_ID><![CDATA[".$shoppingDotComCategory->getShoppingdotcomId()."]]></Category_ID>\n";
					$node .= "\t\t<Category_Name><![CDATA[".$shoppingDotComCategory->getShoppingdotcomCat1()."]]></Category_Name>\n";
					$productType .= $shoppingDotComCategory->getShoppingdotcomCat1();
					
				}
				if (strlen(trim($shoppingDotComCategory->getShoppingdotcomCat2())) > 0){
					$node .= "\t\t<Sub-category_Name><![CDATA[".$shoppingDotComCategory->getShoppingdotcomCat2();
					$productType .= ", ".$shoppingDotComCategory->getShoppingdotcomCat2();
					$addSubCatEnd = True;
				}
				if (strlen(trim($shoppingDotComCategory->getShoppingdotcomCat3())) > 0){
					$node .= ", ".$shoppingDotComCategory->getShoppingdotcomCat3();
					$productType .= ", ".$shoppingDotComCategory->getShoppingdotcomCat3();
					$addSubCatEnd = True;
				}
				if ($addSubCatEnd){
					$node .= "]]></Sub-category_Name>\n";
				} else {
					$node .= "\t\t<Sub-category_Name/>\n";
				}
				$productType .= "]]></Product_Type>\n";
			} else {
				$node .= "\t\t<Category_Name><![CDATA[".rtrim($categoryData,", ")."]]></Category_Name>\n";
				$productType = "\t\t<Product_Type></Product_Type>\n";
			}
			$node .= $productType;
			$node .= "\t\t<Parent_SKU></Parent_SKU>	
			<Parent_Name></Parent_Name>	
			<Product_Description><![CDATA[".$product->getDescription()."]]></Product_Description>	
				
			<Image_URL>".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$product->getImage()."</Image_URL>	
			<Stock_Availability>Yes</Stock_Availability>	
			<Bundle>No</Bundle>	
	</Product>		
";			
			
		}
		$node .= "</Products>";
		
		return $node;
    }
    
}