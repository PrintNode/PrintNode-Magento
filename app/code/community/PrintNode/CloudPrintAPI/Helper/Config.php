<?php
/**
 * PrintNode Cloud Print Plugin for Magento
 * Based upon Google Cloud Print Copyright 2014 Profit Soft (http://profitsoft.pro/) see NOTICE for changes.
 */

/**
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
class PrintNode_CloudPrintAPI_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     *
     */
    const XML_API_LOGIN = 'pncloudprintapi/general/account_login';

    /**
     *
     */
    const XML_API_PRINTER_ID = 'pncloudprintapi/general/printer_id';

    /**
     *
     */
    const XML_PRINT_INVOICE = 'pncloudprintapi/print_replace/print_invoice';

    /**
     *
     */
    const XML_PRINT_CREDITMEMO = 'pncloudprintapi/print_replace/print_creditmemo';

    /**
     *
     */
    const XML_PRINT_SHIPMENT = 'pncloudprintapi/print_replace/print_shipment';

    /**
     * Is print invoice
     *
     * @param null $storeId Store id
     * @return bool
     */
    public function isInvoice($storeId = null)
    {
        if (!is_null($storeId)) {
            return (bool)Mage::getStoreConfig(self::XML_PRINT_INVOICE, $storeId);
        } else {
            return (bool)Mage::getStoreConfig(self::XML_PRINT_INVOICE);
        }
    }

    /**
     * Is print creditmemo
     *
     * @param null $storeId Store id
     * @return bool
     */
    public function isCreditmemo($storeId = null)
    {
        if (!is_null($storeId)) {
            return (bool)Mage::getStoreConfig(self::XML_PRINT_CREDITMEMO, $storeId);
        } else {
            return (bool)Mage::getStoreConfig(self::XML_PRINT_CREDITMEMO);
        }
    }

    /**
     * Is print shipment
     *
     * @param null $storeId Store id
     * @return bool
     */
    public function isShipment($storeId = null)
    {
        if (!is_null($storeId)) {
            return (bool)Mage::getStoreConfig(self::XML_PRINT_SHIPMENT, $storeId);
        } else {
            return (bool)Mage::getStoreConfig(self::XML_PRINT_SHIPMENT);
        }
    }

    /**
     * Get google API login
     *
     * @param null $storeId Store id
     * @return bool
     */
    public function getAccountLogin($storeId = null)
    {
        if (!is_null($storeId)) {
            return Mage::getStoreConfig(self::XML_API_LOGIN, $storeId);
        } else {
            return Mage::getStoreConfig(self::XML_API_LOGIN);
        }
    }

    /**
     * Get google printer id
     *
     * @param null $storeId Store id
     * @return bool
     */
    public function getPrinterId($storeId = null)
    {
        if (!is_null($storeId)) {
            return Mage::getStoreConfig(self::XML_API_PRINTER_ID, $storeId);
        } else {
            return Mage::getStoreConfig(self::XML_API_PRINTER_ID);
        }
    }
}
