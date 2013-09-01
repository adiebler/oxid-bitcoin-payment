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
 * Extends the module config controller to load exchange rates after saving.
 */
class cc_bitcoin_module_config extends cc_bitcoin_module_config_parent
{
    /**
     * Module identifier
     *
     * @var string
     */
    protected $_sModuleId = 'cc_bitcoin';

    /**
     * Checks if cc_bitcoin is the active module and updates the exchanges rates.
     */
    public function saveConfVars()
    {
        parent::saveConfVars();

        if ($this->getEditObjectId() == $this->_sModuleId) {

            $oxConfig = $this->getConfig();
            $sShopId = $oxConfig->getShopId();
            $sModule = oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
            $iSource = $oxConfig->getShopConfVar('ccExchangeSource', $sShopId, $sModule);

            if ($iSource > 0) {
                oxNew('cc_bitcoin_exchange_rate_updater');
            }
        }
    }
}