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
 * Controller for Bitcoin order detail view.
 */
class cc_bitcoin_admin_order extends oxAdminView
{
    /**
     * Template for order overview tab
     *
     * @var string
     */
    protected $_sThisTemplate = 'cc_bitcoin_admin_order.tpl';

    /**
     * Collects Bitcoin information of the order and returns the template file.
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $oOrder = $this->getEditObject();
        $this->bitcoinValue = $oOrder->oxorder__ccbitcoinvalue->value;
        $this->bitcoinAddress = $oOrder->oxorder__ccbitcoinaddress->value;

        return $this->_sThisTemplate;
    }

    /**
     * Returns the orders Bitcoin value.
     *
     * @return decimal
     */
    public function getBitcoinValue()
    {
        return $this->bitcoinValue;
    }

    /**
     * Returns the merchants recipient address.
     *
     * @return string
     */
    public function getBitcoinAddress()
    {
        return $this->bitcoinAddress;
    }

    /**
     * Loads the corresponding order object.
     *
     * @return object
     */
    public function getEditObject()
    {
        $soxId = $this->getEditObjectId();

        if ($this->_oEditObject === null && isset($soxId) && $soxId != "-1") {
            $this->_oEditObject = oxNew("oxorder");
            $this->_oEditObject->load($soxId);
        }

        return $this->_oEditObject;
    }

    /**
     * Adds the Bitcoins address to the order object and sends an information
     * email to the customer.
     */
    public function addBitcoinAddress()
    {
        $sAddress = filter_var($_POST['bitcoin_address'], FILTER_SANITIZE_STRING);

        $oOrder = $this->getEditObject();
        $oOrder->oxorder__ccbitcoinaddress = new oxField($sAddress);
        $oOrder->save();

        $oEmail = oxNew('oxemail');
        $oEmail->sendBitcoinAddressToUser($oOrder);
    }
}