<?php
/**
 * PrintNode Cloud Print Plugin for Magento
 * Based upon Google Cloud Print Copyright 2014 Profit Soft (http://profitsoft.pro/)
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
class PrintNode_CloudPrintAPI_Model_Sales_Order_Pdf_Creditmemo extends Mage_Sales_Model_Order_Pdf_Creditmemo
{
    /**
     * Set font as regular
     *
     * @param Zend_Pdf_Page $object Page
     * @param int $size Size
     * @return Zend_Pdf_Resource_Font
     */
    protected function _setFontRegular($object, $size = 7)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * Set font as bold
     *
     * @param Zend_Pdf_Page $object Page
     * @param int $size Size
     * @return Zend_Pdf_Resource_Font
     */
    protected function _setFontBold($object, $size = 7)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * Set font as italic
     *
     * @param Zend_Pdf_Page $object Page
     * @param int $size Size
     * @return Zend_Pdf_Resource_Font
     */
    protected function _setFontItalic($object, $size = 7)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }
}