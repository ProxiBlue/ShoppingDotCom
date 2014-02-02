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

class ProxiBlue_ShoppingDotCom_FeedController extends Mage_Core_Controller_Front_Action {
	
	public function displayAction(){
		$this->staticAction();
	}
	
	public function staticAction(){
    	$this->pushFeed(file_get_contents(Mage::app()->getConfig()->getTempVarDir()."/shopping.com.xml"));
    }
    
    public function liveAction() {
    	$this->pushFeed(Mage::getModel('shoppingdotcom/shoppingdotcom')->exportshoppingdotcomAction());
    }
    
    private function pushFeed($data){
    	$contentType = 'text/xml';
    	$this->getResponse()
			->setHttpResponseCode(200)
			->setHeader('Pragma', 'public', true)
			->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
			->setHeader('Content-type', $contentType, true)
			->setHeader('Content-Length', strlen($data))
			->setHeader('Last-Modified', date('r'))
			->setBody($data);  
    }
}
