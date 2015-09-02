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
 *
 * CHANGES
 *
 * This file has been changed to represent PrintNode's Cloud Printing API rather than Google Cloud Print. These changes include:
 *
 * Changing of class name to represent PrintNode,
 * Changing of redirects to show success of prints to PrintNode.
 *
 */
require_once(Mage::getBaseDir('code') . DS . 'core' . DS . 'Mage' . DS . 'Adminhtml' . DS . 'controllers' . DS . 'Sales' . DS . 'Order' . DS . 'InvoiceController.php');

class PrintNode_CloudPrintAPI_Adminhtml_Sales_Order_InvoiceController extends Mage_Adminhtml_Sales_Order_InvoiceController
{
    /**
     * Print invoice action
     *
     * @return void
     */
    public function printAction()
    {
        if ($invoiceId = $this->getRequest()->getParam('invoice_id')) {
            if ($invoice = Mage::getModel('sales/order_invoice')->load($invoiceId)) {
                $configHelper = Mage::helper('pncloudprintapi/config');
                $storeId = $this->getRequest()->getParam('store') ?
                    $this->getRequest()->getParam('store') : Mage::app()->getStore()->getId();
                if ($configHelper->isInvoice($storeId)) {
                    /** We should use custom pdf model with times font. Different fonts not allowed in google */
                    $pdf = Mage::getModel('pncloudprintapi/sales_order_pdf_invoice')->getPdf(array($invoice));
                    $name = 'invoice' . Mage::getSingleton('core/date')->date('Y-m-d_H-i-s')
                        . '_' . $invoice->getIncrementId() . '.pdf';
                    $connector = Mage::helper('pncloudprintapi/connector');
                    $result = $connector->submit($pdf->render(), 'pdf_base64', $name);

                    if ($result) {
                        Mage::getSingleton('adminhtml/session')->addSuccess(
                            Mage::helper('pncloudprintapi')->__('Invoice successfully sent to PrintNode.')
                        );
                        $this->_redirect('*/*/view', array('invoice_id' => $invoiceId));
                        return;
                    }
                }
                $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice));
                $this->_prepareDownloadResponse('invoice' . Mage::getSingleton('core/date')->date('Y-m-d_H-i-s') .
                    '.pdf', $pdf->render(), 'application/pdf');
            }
        } else {
            $this->_forward('noRoute');
        }
    }
}
