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
 * Extends oxemail model to send the bitcoin address to the customer.
 */
class cc_bitcoin_oxemail extends cc_bitcoin_oxemail_parent
{
    /**
     * Bitcoin Address E-Mail-Template
     *
     * @var string
     */
    protected $_sBitcoinAddressTemplatePlain = "cc_bitcoin_email_address.tpl";

    /**
     * Prepares and sends out the email with the recipients address.
     *
     * @param object $oOrder order object
     * @return bool
     */
    public function sendBitcoinAddressToUser($oOrder)
    {
        $oShop = $this->_getShop();

        // load user
        $oUser = oxNew('oxuser');
        $oUser->load($oOrder->oxorder__oxuserid->rawValue);
        $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();

        //set mail params (from, fromName, smtp...)
        $this->_setMailParams($oShop);
        $oLang = oxRegistry::getLang();

        $oSmarty = $this->_getSmarty();
        $this->setViewData("value", $oOrder->oxorder__ccbitcoinvalue->value);
        $this->setViewData("address", $oOrder->oxorder__ccbitcoinaddress->value);
        $this->setViewData("fullname", $sFullName);

        // Process view data array through oxoutput processor
        $this->_processViewArray();

        $this->setRecipient($oUser->oxuser__oxusername->value, $sFullName);
        $this->setFrom($oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue());
        $this->setBody($oSmarty->fetch($this->_sBitcoinAddressTemplatePlain, false));
        $this->setAltBody("");
        $this->setSubject($oLang->translateString('CC_BITCOIN_ORDER_ADDRESS') . $oOrder->oxorder__oxordernr->getRawValue());

        $blSend = $this->send();
        return $blSend;
    }
}