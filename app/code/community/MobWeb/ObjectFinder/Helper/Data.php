<?php

class MobWeb_ObjectFinder_Helper_Data extends Mage_Core_Helper_Abstract
{
	private $searchResultLimitPerType = 10;

	public function getRedirectParameters($object)
	{
		if($object INSTANCEOF Mage_Sales_Model_Order) {
			$redirectParameters = array('adminhtml/sales_order/view', array('order_id' => $object->getId()));
		} else if($object INSTANCEOF Mage_Sales_Model_Order_Invoice) {
		    	$redirectParameters = array('adminhtml/sales_invoice/view', array('invoice_id' => $object->getId()));
		} else if($object INSTANCEOF Mage_Sales_Model_Order_Shipment) {
		    	$redirectParameters = array('adminhtml/sales_shipment/view', array('shipment_id' => $object->getId()));
		} else if($object INSTANCEOF Mage_Sales_Model_Order_Creditmemo) {
		    	$redirectParameters = array('adminhtml/sales_creditmemo/view', array('creditmemo_id' => $object->getId()));
		} else if($object INSTANCEOF Mage_Catalog_Model_Product) {
		    	$redirectParameters = array('adminhtml/catalog_product/edit', array('id' => $object->getId()));
		}

		return $redirectParameters;
	}

	public function getBillingAddress($object)
	{

		// Get the order object
		if($object INSTANCEOF Mage_Sales_Model_Order) {
			$order = $object;
		} else {
			$order = $object->getOrder();
		}

		// Get the billing address
		$address = $order->getBillingAddress();

		// Return the required fields from the order
		return sprintf('%s, %s %s', $address->getName(), $address->getPostcode(), $address->getCity());
	}

	public function getTypes()
	{
		return array(
			'sales/order' => 'order',
			'sales/order_invoice' => 'invoice',
			'sales/order_shipment' => 'shipment',
			'sales/order_creditmemo' => 'creditmemo',
			'catalog/product' => 'product'
		);
	}

	public function searchOrders($search)
	{
		// Get a collection of all entities that wildcard match the search query
		$resultCollection = Mage::getModel('sales/order')->getCollection();
		$resultCollection->addAttributeToFilter(
			'increment_id', array('like' => '%' . $search . '%')
		);
		$resultCollection->setPageSize($this->searchResultLimitPerType)->setCurPage(1);

		// Parse the collection into an array
		$results = array();
		foreach($resultCollection AS $result) {
			$results[] = $result;
		}

		return $results;
	}

	public function searchInvoices($search)
	{
		// Get a collection of all entities that wildcard match the search query
		$resultCollection = Mage::getModel('sales/order_invoice')->getCollection();
		$resultCollection->addAttributeToFilter(
			'increment_id', array('like' => '%' . $search . '%')
		);
		$resultCollection->setPageSize($this->searchResultLimitPerType)->setCurPage(1);

		// Parse the collection into an array
		$results = array();
		foreach($resultCollection AS $result) {
			$results[] = $result;
		}

		return $results;
	}

	public function searchShipments($search)
	{
		// Get a collection of all entities that wildcard match the search query
		$resultCollection = Mage::getModel('sales/order_shipment')->getCollection();
		$resultCollection->addAttributeToFilter(
			'increment_id', array('like' => '%' . $search . '%')
		);
		$resultCollection->setPageSize($this->searchResultLimitPerType)->setCurPage(1);

		// Parse the collection into an array
		$results = array();
		foreach($resultCollection AS $result) {
			$results[] = $result;
		}

		return $results;
	}

	public function searchCreditmemos($search)
	{
		// Get a collection of all entities that wildcard match the search query
		$resultCollection = Mage::getModel('sales/order_creditmemo')->getCollection();
		$resultCollection->addAttributeToFilter(
			'increment_id', array('like' => '%' . $search . '%')
		);
		$resultCollection->setPageSize($this->searchResultLimitPerType)->setCurPage(1);

		// Parse the collection into an array
		$results = array();
		foreach($resultCollection AS $result) {
			$results[] = $result;
		}

		return $results;
	}

	public function searchProducts($search)
	{
		// Get a collection of all entities that wildcard match the search query
		$resultCollection = Mage::getModel('catalog/product')->getCollection();
		$resultCollection->addAttributeToFilter(
			'sku', array('like' => '%' . $search . '%')
		);
		$resultCollection->addAttributeToSelect(array(
			'name'
		));
		$resultCollection->setPageSize($this->searchResultLimitPerType)->setCurPage(1);

		// Parse the collection into an array
		$results = array();
		foreach($resultCollection AS $result) {
			$results[] = $result;
		}

		return $results;
	}
}