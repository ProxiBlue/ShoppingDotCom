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
require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';

class ProxiBlue_ShoppingDotCom_Override_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
		/**
     * Create product feed xml file
     */
    public function exportshoppingdotcomAction()
    {
    	$tempFolder = Mage::app()->getConfig()->getTempVarDir();
    	$handle = fopen($tempFolder."/shopping.com.xml", 'w');
    	$data = Mage::getModel('shoppingdotcom/shoppingdotcom')->exportshoppingdotcomAction();
    	fwrite($handle, $data);
		fflush($handle);
		fclose($handle);
		$fileContent = file_get_contents($tempFolder."/shopping.com.xml");
		$contentType = 'application/octet-stream';
		$this->getResponse()
			->setHttpResponseCode(200)
			->setHeader('Pragma', 'public', true)
			->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
			->setHeader('Content-type', $contentType, true)
			->setHeader('Content-Length', strlen($fileContent))
			->setHeader('Content-Disposition', 'attachment; filename=' . "shopping.com.xml")
			->setHeader('Last-Modified', date('r'))
			->setBody($fileContent);  
    }
    
    
}
