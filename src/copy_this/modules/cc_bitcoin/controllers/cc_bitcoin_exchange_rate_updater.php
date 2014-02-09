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
 * Exchange rate update controller.
 */
class cc_bitcoin_exchange_rate_updater
{
    /**
     * Module identifier
     *
     * @var string
     */
    protected $_sModuleId = 'cc_bitcoin';

    /**
     * Supported currencies
     *
     * @var array
     */
    protected $_aCurrencies = array('EUR', 'USD', 'GBP', 'CHF');

    /**
     * Supported currencies' exchange rates
     *
     * @var array
     */
    protected $_aRates = array();

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
     * Constructor checks the set source and call the required method.
     *
     * @return null
     */
    public function __construct()
    {
        $this->_oxConfig = oxRegistry::getConfig();
        $this->_sShopId = $this->_oxConfig->getShopId();
        $this->_sModule = oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;

        $iSource = $this->_oxConfig->getShopConfVar('ccExchangeSource', $this->_sShopId, $this->_sModule);

        if ($iSource == 0) {
            return;
        }

        switch ($iSource) {
            case 1:
                $this->_updateViaBlockchain();
                break;
            case 2:
                $this->_updateViaBitstamp();
                break;
            case 3:
                $this->_updateViaCoinbase();
                break;
            default:
                break;
        }
    }

    /**
     * Update via http://blockchain.info
     */
    protected function _updateViaBlockchain()
    {
        $sJson = file_get_contents("http://blockchain.info/ticker");
        $aJson = json_decode($sJson, true);

        foreach ($this->_aCurrencies as $sCurrency) {
            $this->_oxConfig->saveShopConfVar('str', 'ccBitcoin' . $sCurrency, $aJson[$sCurrency]['15m'], $this->_sShopId, $this->_sModule);
        }
    }

    /**
     * Update via http://bitstamp.net
     */
    protected function _updateViaBitstamp()
    {
        $this->_getExchangeRates();

        // failover for exchange rate server error
        if(in_array(null, $this->_aRates)) {
            $this->_updateViaBlockchain();
            return;
        }

        $sJson = file_get_contents('https://www.bitstamp.net/api/ticker/');
        $oJson = json_decode($sJson);
        $sRate = $oJson->last;

        foreach ($this->_aCurrencies as $sCurrency) {
            $this->_oxConfig->saveShopConfVar('str', 'ccBitcoin' . $sCurrency, $sRate * $this->_aRates[$sCurrency], $this->_sShopId, $this->_sModule);
        }
    }

    /**
     * Update via http://coinbase.com
     */
    protected function _updateViaCoinbase()
    {
        $this->_getExchangeRates();

        // failover for exchange rate server error
        if(in_array(null, $this->_aRates)) {
            $this->_updateViaBlockchain();
            return;
        }

        $sJson = file_get_contents('https://coinbase.com/api/v1/currencies/exchange_rates');
        $oJson = json_decode($sJson);
        $sRate = $oJson->btc_to_usd;

        foreach ($this->_aCurrencies as $sCurrency) {
            $this->_oxConfig->saveShopConfVar('str', 'ccBitcoin' . $sCurrency, $sRate * $this->_aRates[$sCurrency], $this->_sShopId, $this->_sModule);
        }
    }

    /**
     * Get fiat currency exchange rates
     */
    protected function _getExchangeRates($sBasis = 'USD')
    {
        $sUrl = 'https://bitdango.com/api/currencypairs/' . $sBasis;
        foreach ($this->_aCurrencies as $sCurrency) {
            $sJson = @file_get_contents($sUrl . $sCurrency);
            $oJson = json_decode($sJson);
            $this->_aRates[$sCurrency] = $oJson->ExchangeRate;
        }
    }
}