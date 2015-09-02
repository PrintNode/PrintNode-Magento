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
class PrintNode_CloudPrintAPI_Model_System_Config_Source_Printers
{
    /**
     * Get Printers from GCP
     *
     * @return array
     */

    private $statesArray = array("offline","disappeared","deleted");

    private function _inArray($value, $array)
    {
        $newArray = array_map("strtolower",$array);
        foreach($newArray as $arrayVal){
            if ($value == $arrayVal) { return true; }
        }
        return false;
    }

    private function _getStateArray(){
        return $this->statesArray;
    }

    private function _getPrinters()
    {
        $connector = Mage::helper('pncloudprintapi/connector');
        $printers = $connector->search();
        usort($printers,function ($a,$b){
            $statesArray = $this->_getStateArray();
            $ad = strtotime($a->computer->createTimestamp);
            $bd = strtotime($b->computer->createTimestamp);
            if($a->computer->state == "connected" && $b->computer->state != "connected"){
                return -1;
            }elseif ($b->computer->state == "connected" && $a->computer->state != "connected"){
                return 1;
            }
            if($a->default && !($b->default)){
                if($ad == $bd) { return -1; }
            }elseif ($b->default && !($a->default)){
                if($ad == $bd) { return 1;  }
            }
            if($this->_inArray($a->state,$statesArray) && !($this->_inArray($b->state,$statesArray))){
                if($ad == $bd) { return 1; }
            }elseif ($this->_inArray($b->state,$statesArray) && !($this->_inArray($a->state,$statesArray))){
                if($ad == $bd) { return -1; }
            }
            if($ad == $bd){
                return strtotime($a->createTimestamp) < strtotime($b->createTimestamp) ? 1 : -1;
            }
            return $ad < $bd ? 1 : -1;
        });
        return (!empty($printers)) ? $printers : array();
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $statesArray = $this->_getStateArray();
        $printers = $this->_getPrinters();
        $resultPrinters = array();
        if (!empty($printers)) {
            foreach ($printers as $printer) {
                if (! $resultPrinters[$printer->computer->id]){
                    $state;
                    $printer->computer->state == "connected" ? $state = $printer->computer->state : $state = "";
                    $resultPrinters[$printer->computer->id] = array(
                        'value' => array(),
                        'label' => $printer->computer->name . ' ' . $state
                    );
                }
                $state;
                $this->_inArray($printer->state,$statesArray) ? $state = $printer->state : $state = "";
                $printer->default ? $state = "default" : $state = $state;
                $resultPrinters[$printer->computer->id]['value'][] = array(
                    'value' => $printer->id,
                    'label' => $printer->name . ' ' . $state
                );
            }
        }

        return $resultPrinters;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $printers = $printers = $this->_getPrinters();
        $resultPrinters = array();
        if (!empty($printers)) {
            foreach ($printers as $printer) {
                $resultPrinters[] = array(
                    $printer->id => (!empty($printer->defaultDisplayName)) ?
                    $printer->defaultDisplayName : $printer->name
                );
            }
        }
        return $resultPrinters;
    }
}