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
 * Metadata version
 */
$sMetadataVersion = '1.0';

/**
 * Module information
 */
$aModule = array(
  'id'           => 'cc_bitcoin',
  'title'        => 'Bitcoin',
  'description'  => array(
    'de' => 'Akzeptieren Sie Bitcoins als Zahlungsmittel.',
    'en' => 'Accept Bitcoins as payment method.'
  ),
  'lang' => 'de',
  'thumbnail'    => 'CommerceCoding.png',
  'version'      => '0.2.0',
  'author'       => 'Commerce Coding',
  'url'          => 'http://www.commerce-coding.de',
  'email'        => 'info@commerce-coding.de',
  'files'        => array(
    'cc_bitcoin_exchange_rate_updater' => 'cc_bitcoin/controllers/cc_bitcoin_exchange_rate_updater.php',
    'cc_bitcoin_admin_order'           => 'cc_bitcoin/controllers/cc_bitcoin_admin_order.php',
    'cc_bitcoin_cron'                  => 'cc_bitcoin/controllers/cc_bitcoin_cron.php',
    'cc_bitcoin_callback'              => 'cc_bitcoin/controllers/cc_bitcoin_callback.php'
  ),
  'extend'       => array(
    'Module_Config' => 'cc_bitcoin/controllers/cc_bitcoin_module_config',
    'Details'       => 'cc_bitcoin/controllers/cc_bitcoin_details',
    'Basket'        => 'cc_bitcoin/controllers/cc_bitcoin_basket',
    'Order'         => 'cc_bitcoin/controllers/cc_bitcoin_order',
    'oxorder'       => 'cc_bitcoin/models/cc_bitcoin_oxorder',
    'oxemail'       => 'cc_bitcoin/models/cc_bitcoin_oxemail'
  ),
  'blocks' => array(
    array('template' => 'page/details/inc/productmain.tpl',     'block' => 'details_productmain_priceperunit',    'file' => 'out/blocks/page/details/inc/productmain'),
    array('template' => 'page/checkout/inc/basketcontents.tpl', 'block' => 'checkout_basketcontents_grandtotal',  'file' => 'out/blocks/page/checkout/inc/basketcontents'),
    array('template' => 'email/html/order_cust.tpl',            'block' => 'email_html_order_cust_paymentinfo',   'file' => 'out/blocks/email/html/order_cust'),
    array('template' => 'email/html/order_owner.tpl',           'block' => 'email_html_order_owner_paymentinfo',  'file' => 'out/blocks/email/html/order_owner'),
    array('template' => 'email/plain/order_cust.tpl',           'block' => 'email_plain_order_cust_paymentinfo',  'file' => 'out/blocks/email/plain/order_cust'),
    array('template' => 'email/plain/order_owner.tpl',          'block' => 'email_plain_order_owner_paymentinfo', 'file' => 'out/blocks/email/plain/order_owner')
  ),
  'settings' => array(
    array('group' => 'ccexchange', 'name' => 'ccExchangeSource', 'type' => 'select', 'value' => '0', 'constrains' => '0|1|2', 'position' => 0),
    array('group' => 'ccexchange', 'name' => 'ccBitcoinEUR',     'type' => 'str',    'value' => ''),
    array('group' => 'ccexchange', 'name' => 'ccBitcoinUSD',     'type' => 'str',    'value' => ''),
    array('group' => 'ccexchange', 'name' => 'ccBitcoinGBP',     'type' => 'str',    'value' => ''),
    array('group' => 'ccexchange', 'name' => 'ccBitcoinCHF',     'type' => 'str',    'value' => ''),
    array('group' => 'ccexchange', 'name' => 'ccCronPassword',   'type' => 'str',    'value' => ''),
    array('group' => 'ccauto',     'name' => 'ccAutomatic',      'type' => 'bool',   'value' => '0'),
    array('group' => 'ccauto',     'name' => 'ccAddress',        'type' => 'str',    'value' => ''),
    array('group' => 'ccauto',     'name' => 'ccMinConfirms',    'type' => 'str',    'value' => '1'),
    array('group' => 'ccauto',     'name' => 'ccShared',         'type' => 'bool',   'value' => '0')
  ),
  'templates' => array(
    'cc_bitcoin_admin_order.tpl'       => 'cc_bitcoin/out/admin/tpl/cc_bitcoin_admin_order.tpl',
    'cc_bitcoin_email_address.tpl'     => 'cc_bitcoin/out/tpl/email/cc_bitcoin_email_address.tpl',
    'cc_bitcoin_callback_response.tpl' => 'cc_bitcoin/out/tpl/callback/cc_bitcoin_callback_response.tpl'
  )
);