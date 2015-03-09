<?php

class MobWeb_ObjectFinder_Block_Objectfinder extends Mage_Core_Block_Template
{
	public $overview;

	private function _getShipments($date = '')
	{
		$helper = Mage::helper('objectfinder');
		$date = $date ? $date : date($helper::DATE_FORMAT);
		return Mage::helper('objectfinder')->getShipmentsByDate($date);
	}

	public function getOverview($date = '')
	{
		$log = '';
		$overview = array();

		// Get the shipments for the specified day
		$date = $date ? $date : date($helper::DATE_FORMAT);
		$shipments = $this->_getShipments($date);

		$log .= sprintf('Found %d shipments for %s <br />', $shipments->count(), $date);

		foreach($shipments AS $shipment) {
			$log .= sprintf('Processing shipment %d <br />', $shipment->getIncrementId());

			// Get the required shipping address' details
			$shippingAddress = $shipment->getShippingAddress();
			$shippingAddressName = $shippingAddress->getData('firstname') . ' ' . $shippingAddress->getData('lastname');
			$shippingAddressCountry = $shippingAddress->getData('country_id');

			// Get the shipped products' details
			//TODO: Get other attributes from the products that can later
			// be used to group/sort the products in the matrix grid, according
			// to the specifications of the customer
			$products = array();
			foreach($shipment->getItemsCollection() AS $product) {
				for($i=0; $i< $product->getQty(); $i++) {
					$products[] = $product->getSku();
				}
			}

			// Save everything in an array
			$overview[$shipment->getIncrementId()] = array(
				'increment_id' => $shipment->getIncrementId(),
				'created_at' => $shipment->getCreatedAt(),
				'name' => $shippingAddressName,
				'country' => $shippingAddressCountry,
				'products' => $products
			);
		}

		$this->overview = $overview;

		return $this->overview;
	}

	public function getCsv($date = '')
	{
		// Load the overview
		$date = $date ? $date : date($helper::DATE_FORMAT);
		if(!$overview = $this->overview) {
			$overview = $this->getOverview($date);
		}

		// Structure the overview so that it can be used to generate
		// a CSV file
		$data = array();
		foreach($overview AS $shipment) {
			$products = implode(', ', $shipment['products']);
			$data[] = array(
				$shipment['created_at'],
				$shipment['increment_id'],
				$products,
				$shipment['name'],
				$shipment['country']
			);
		}

		// Write the data to a CSV file
		$baseDir = Mage::getBaseDir();
		$file = sprintf('/media/shipment_logs/shipments-%s.csv', $date);
		$csv = new Varien_File_Csv();
		$csv->saveData($baseDir . $file, $data);

		return $file;
	}
}