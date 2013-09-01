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
 * Callback Controller
 * Reachable from outside to handle HTTP push notifications on status changes
 * for Bitcoin transactions.
 */
class cc_bitcoin_callback extends oxView
{
    /**
     * Response Codes
     */
    const STATUS_OK = '*ok*';
    const STATUS_ERROR = '*error*';

    /**
     * Final response
     *
     * @var string
     */
    protected $_response = null;

    /**
     * Module identifier
     *
     * @var string
     */
    protected $_sModuleId = 'cc_bitcoin';

    /**
     * Oxid configuration
     *
     * @var object
     */
    private $_oxConfig = null;

    /**
     * Active shop ID
     *
     * @var int
     */
    private $_sShopId = null;

    /**
     * Active module
     *
     * @var string
     */
    private $_sModule = null;

    /**
     * Template for callback response
     *
     * @var string
     */
    protected $_sThisTemplate = 'cc_bitcoin_callback_response.tpl';

    /**
     * Checks the send information and sets payment date for order.
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_oxConfig = oxRegistry::getConfig();
        $this->_sShopId = $this->_oxConfig->getShopId();
        $this->_sModule = oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;

        $iMinConfirms = $this->_oxConfig->getShopConfVar('ccMinConfirms', $this->_sShopId, $this->_sModule);
        $confirms = filter_var($_GET['confirmations'], FILTER_SANITIZE_NUMBER_INT);

        if ($confirms < $iMinConfirms) {
            $this->_response = self::STATUS_ERROR;
        } else {
            $sOrderNr = filter_var($_GET['order'], FILTER_SANITIZE_STRING);
            $sSecret = filter_var($_GET['secret'], FILTER_SANITIZE_STRING);
            $sSatoshi = filter_var($_GET['value'], FILTER_SANITIZE_NUMBER_INT);
            $dBitcoins = $sSatoshi / 100000000;

            $rs = oxDb::getDb()->Execute("SELECT OXID FROM oxorder WHERE OXORDERNR = '" . $sOrderNr . "'");
            $oOrder = oxNew("oxorder");
            $oOrder->load($rs->fields[0]);

            if ($dBitcoins == $oOrder->oxorder__ccbitcoinvalue->value && $sSecret == $oOrder->getBitcoinHash()) {
                $oOrder->oxorder__oxpaid->setValue(date("Y-m-d H:i:s", time()));
                $oOrder->save();
                $this->_response = self::STATUS_OK;
            } else {
                $this->_response = self::STATUS_ERROR;
            }
        }

        return $this->_sThisTemplate;
    }

    /**
     * Returns the response for the API.
     *
     * @return string
     */
    public function getResponse()
    {
        if (is_null($this->_response)) {
            return self::STATUS_ERROR;
        }

        return $this->_response;
    }
}
