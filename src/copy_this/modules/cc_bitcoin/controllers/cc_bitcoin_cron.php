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
 * Controller for cron job to update exchange rate on a regular basis.
 */
class cc_bitcoin_cron extends oxView
{
    /**
     * Module identifier
     *
     * @var string
     */
    protected $_sModuleId = 'cc_bitcoin';

    /**
     * Name of template file to render.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/shop/start.tpl';

    /**
     * Checks the given password and if it matches the exchange rates are updated.
     *
     * @return string current template file name
     */
    public function render()
    {
        $oxConfig = oxRegistry::getConfig();
        $sShopId = $oxConfig->getShopId();
        $sModule = oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;

        $sCronPasswd = $oxConfig->getShopConfVar('ccCronPassword', $sShopId, $sModule);
        $sSendPasswd = isset($_GET['passwd']) ? $_GET['passwd'] : '';

        if ($sCronPasswd == $sSendPasswd) {
            oxNew('cc_bitcoin_exchange_rate_updater');
        }

        return parent::render();
    }
}