<?php
/**
 * PrintNode Cloud Print Plugin for Magento
 * Based upon Google Cloud Print Copyright 2014 Profit Soft (http://profitsoft.pro/) see NOTICE for changes.
 */

/**
 *
 * PrintNode Remote Printing Plugin for Magento
 *
 * NOTICE OF LICENSE
 *
 * Thanks to Profit Soft for the original code, which you can see at https://github.com/Den4ik/Magento-Google-Cloud-Print.
 *
 * Original code is Copyright 2014 Profit Soft (http://profit-soft.pro/)
 * PrintNode additional changes are Copyright 2015 PrintNode (https://printnode.com/)
 *
 * Licensed under the Apache License, Version 2.0 (the “License”);
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an “AS IS” BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @package   Google Cloud Print
 * @author    Denis Kopylov <dv.kopylov@profit-soft.pro>
 * @copyright Copyright (c) 2014 Profit Soft (http://profit-soft.pro/)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0 (Apache-2.0)
 */
class PrintNode_CloudPrintAPI_Helper_Connector extends Mage_Core_Helper_Abstract
{
    /**
     * Interface url. http or https calculate by store protocol
     * @var string
     */
    private $_apiUrl = 'https://api.printnode.com';

    /**
     * Google Cloud Print Client
     * @var null|Zend_Gdata_HttpClient
     */
    private $_apiClient = null;

    /**
     * Google Cloud Print client setter
     *
     * @param null|\Zend_Gdata_HttpClient $gcpClient Client
     */
    public function setApiClient($gcpClient)
    {
        $this->_apiClient = $gcpClient;
    }

    /**
     * Setter for property
     *
     * @param string $gcpInterfaceUrl String
     */
    public function setApiUrl($gcpInterfaceUrl)
    {
        $this->_apiUrl = $gcpInterfaceUrl;
    }

    /**
     * Getter for property
     *
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->_apiUrl;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        /*$httpPrefix = 'https';
        if (Mage::app()->getStore()->isAdminUrlSecure()) {
            $httpPrefix .= 's';
        }
        $this->setApiUrl($httpPrefix . $this->getApiUrl());*/

    }

    /**
     * Get client object
     *
     * @return Zend_Gdata_HttpClient
     */
    public function getClient()
    {
        if (is_null($this->_apiClient)) {
            $configHelper = Mage::helper('pncloudprintapi/config');
            $login = $configHelper->getAccountLogin();
            if (empty($login)) return false;
			$client = new Zend_Http_Client($this->getApiUrl() ,array('adapter' => 'Zend_Http_Client_Adapter_Curl'));
        	$client->setAuth($login,'');
            $this->setApiClient($client);
        }
        return $this->_apiClient;
    }

    /**
     * Submit document to printer
     *
     * @param string $document Document
     * @param string $type Type
     * @param string $title Title
     * @return bool
     */
    public function submit($document, $type = 'pdf_base64', $title = 'Print Job')
    {
        $configHelper = Mage::helper('pncloudprintapi/config');
        $storeId = Mage::app()->getRequest()->getParam('store') ?
            Mage::app()->getRequest()->getParam('store') : Mage::app()->getStore()->getId();
        $client = $this->getClient();
        if (!$client) return false;
		$client->setUri($this->getApiUrl() . '/printjobs');
		$client->setParameterPost('printer', $configHelper->getPrinterId($storeId));
        $client->setParameterPost('title', $title);
        $client->setParameterPost('content', base64_encode($document));
        $client->setParameterPost('contentType', $type);
        $client->setParameterPost('source',"Magento");
        $response = $client->request(Zend_Http_Client::POST);
        return $response->isSuccessful();
    }

    /**
     * Return Printers list
     *
     * @return array
     */
    public function search()
		{
        $client = $this->getClient();
        if (!$client) return false;
        $client->setUri($this->getApiUrl() . '/printers');
        $response = $client->request(Zend_Http_Client::GET);
        $printerResponse = json_decode($response->getBody());
        if ($printerResponse) {
            return $printerResponse;
        }
        return false;
    }
}
