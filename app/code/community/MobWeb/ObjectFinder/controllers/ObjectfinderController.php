<?php

class MobWeb_ObjectFinder_ObjectfinderController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function postAction()
    {
        $helper = Mage::helper('objectfinder');
        $data = $this->getRequest()->getPost();

        // If no search data is available, throw an exception
        if (empty($data['search']) && $data['search'] !== '0') {
            die(var_dump($data['search']));
            Mage::throwException($this->__('Invalid form data.'));
        }

        // Get the posted search data
        $search = $data['search'];
        $type = $data['type'];

        // Search through all the entities and collect the results
        $results = array();

        // If a type has been specified, search for only that specific type, otherwise
        // search for all types
        if($type) {
            $types = array($type => $type);
        } else {
            $types = $helper->getTypes();
        }

        // Loop through the types, find all entities
        $results = array();
        foreach($types AS $model => $type) {

            switch($model) {

                case 'sales/order':
                    $results = array_merge($results, $helper->searchOrders($search));
                break;

                case 'sales/order_invoice':
                    $results = array_merge($results, $helper->searchInvoices($search));
                break;

                case 'sales/order_shipment':
                    $results = array_merge($results, $helper->searchShipments($search));
                break;

                case 'sales/order_creditmemo':
                    $results = array_merge($results, $helper->searchCreditmemos($search));
                break;

                case 'sales/catalog_product':
                    $results = array_merge($results, $helper->searchProducts($search));
                break;

                case 'catalog/product':
                    $results = array_merge($results, $helper->searchProducts($search));
                break;
            }
        }

        // Check how many results there are
        if(count($results) === 1) {

            // If just one object has been found get the redirect URL
            $redirectParameters = Mage::helper('objectfinder')->getRedirectParameters($results[0]);
        } else if(count($results) === 0) {

            // If no object has been found, let the user know
            $message = $this->__('No objects match your search for «%s»', $search);
            Mage::getSingleton('adminhtml/session')->addError($message);

            // Save the search query so that it can be refined
            Mage::getSingleton('adminhtml/session')->setData('mobweb_objectfinder_search', $search);
        } else {

            // If more than one object has been found, prepare the results in an array to be listed in a
            // grid
            $resultsArray = array();
            foreach($results AS $result) {
                $resultData = array();

                // Try to get the type in a human-readable format
                $resultData['type'] = get_class($result);
                if($result INSTANCEOF Mage_Sales_Model_Order) {
                        $resultData['type'] = 'Order';
                } else if($result INSTANCEOF Mage_Sales_Model_Order_Invoice) {
                        $resultData['type'] = 'Invoice';
                } else if($result INSTANCEOF Mage_Sales_Model_Order_Invoice) {
                        $resultData['type'] = 'Invoice';
                } else if($result INSTANCEOF Mage_Sales_Model_Order_Shipment) {
                        $resultData['type'] = 'Shipment';
                } else if($result INSTANCEOF Mage_Sales_Model_Order_Creditmemo) {
                        $resultData['type'] = 'Credit Memo';
                } else if($result INSTANCEOF Mage_Catalog_Model_Product) {
                        $resultData['type'] = 'Product';
                }

                // Get the ID or SKU for the current object
                $idOrSku = $result->getIncrementId() ? $result->getIncrementId() : $result->getSku();
                $resultData['id'] = $idOrSku;

                // Get the product name or billing address for the current object
                $resultData['name'] = $result->getName() ? $result->getName() : Mage::helper('objectfinder')->getBillingAddress($result);

                // Get the creation timestamp of the current object
                $resultData['created_at'] = $result->getData('created_at');

                // Get the redirect parameters for the current object
                $resultRedirectParameters = Mage::helper('objectfinder')->getRedirectParameters($result);
                $resultData['url'] = sprintf('<a href="%s">%s</a>', Mage::helper('adminhtml')->getUrl($resultRedirectParameters[0], $resultRedirectParameters[1]), $this->__('View'));

                $resultsArray[] = $resultData;
            }

            // Serialize the results in the session
            Mage::getSingleton('adminhtml/session')->setData('mobweb_objectfinder_results', serialize($resultsArray));

            // Prepare a message
            $message = $this->__('Multiple objects have been found for your search for «%s»', $search);
            Mage::getSingleton('adminhtml/session')->addSuccess($message);

            // Save the search query so that it can be refined
            Mage::getSingleton('adminhtml/session')->setData('mobweb_objectfinder_search', $search);
        }

        if(isset($redirectParameters)) {
            $this->_redirect($redirectParameters[0], $redirectParameters[1]);
        } else {
            $this->_redirect('*/*');
        }
    }
} 