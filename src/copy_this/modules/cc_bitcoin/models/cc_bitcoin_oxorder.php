<?php
/**
 * CommerceCoding Bitcoin Payment for OXID eShop
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 of the License
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses/
 *
 * @copyright   Copyright (c) 2013 CommerceCoding (http://www.commerce-coding.de)
 * @author      Alexander Diebler
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

/**
 * Extends order object to save the Bitcoin value if Bitcoin was choosen as
 * payment method.
 */
class cc_bitcoin_oxorder extends cc_bitcoin_oxorder_parent
{
    /**
     * Module identifier
     *
     * @var string
     */
    protected $_sModuleId = 'cc_bitcoin';

    /**
     * Extends order finalization for Bitcoin payments.
     *
     * @param oxBasket $oBasket Shopping basket object
     * @param object $oUser Current user object
     * @param bool $blRecalculatingOrder Order recalculation
     *
     * @return integer
     */
    public function finalizeOrder(oxBasket $oBasket, $oUser, $blRecalculatingOrder = false)
    {
        $iParent = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

        if ($this->oxorder__oxpaymenttype->value == 'oxidbitcoin') {

            $oCur = $this->getConfig()->getActShopCurrencyObject();
            $oxConfig = $this->getConfig();
            $sShopId = $oxConfig->getShopId();
            $sModule = oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
            $dExRate = $oxConfig->getShopConfVar('ccBitcoin' . $oCur->name, $sShopId, $sModule);

            $this->oxorder__ccbitcoinvalue = new oxField(round($this->oxorder__oxtotalordersum->value / $dExRate, 8));

            $bAutomatic = $oxConfig->getShopConfVar('ccAutomatic', $sShopId, $sModule);
            if ($bAutomatic) {
                $sAddress = $oxConfig->getShopConfVar('ccAddress', $sShopId, $sModule);
                $bShared = $oxConfig->getShopConfVar('ccShared', $sShopId, $sModule);
                $this->oxorder__ccbitcoinaddress = new oxField($this->_getPaymentAddress($sAddress, $bShared));

                $oEmail = oxNew('oxemail');
                $oEmail->sendBitcoinAddressToUser($this);
            }

            $this->save();
        }

        return $iParent;
    }

    /**
     * Requests a new forwarding address for the order payment.
     *
     * @param string $sAddress
     * @param boolean $bShared
     * @return string
     */
    protected function _getPaymentAddress($sAddress, $bShared)
    {
        $sUrl = 'https://blockchain.info/api/receive?method=create';
        $sUrl .= '&cl=' . 'cc_bitcoin_callback';
        $sUrl .= '&secret=' . $this->getBitcoinHash();
        $sUrl .= '&address=' . $sAddress;
        $sUrl .= '&order=' . $this->oxorder__oxordernr->value;
        $sUrl .= $bShared ? '&shared=true' : '&shared=false';
        $sUrl .= '&callback=' . urldecode($this->getConfig()->getShopUrl() . 'index.php');

        $sJson = file_get_contents($sUrl);
        $oJson = json_decode($sJson);

        return $oJson->input_address;
    }

    /**
     * Creates the hash for the Blockchain API.
     *
     * @return string
     */
    public function getBitcoinHash()
    {
        $string = $this->getId() . ';' . $this->oxorder__oxpaymentid->value;
        return hash('sha512', $string);
    }
}