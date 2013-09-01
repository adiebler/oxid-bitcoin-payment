[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
  [{ $oViewConf->getHiddenSid() }]
  <input type="hidden" name="cur" value="[{ $oCurr->id }]">
  <input type="hidden" name="oxid" value="[{ $oxid }]">
  <input type="hidden" name="cl" value="cc_bitcoin_admin_order">
</form>


<div style="position: relative; float: left;">
<img src="/modules/cc_bitcoin/out/img/bitcoin.png">
</div>

<div style="position: relative; float: left; margin: 0px 0px 0px 30px;">
[{if $oView->getBitcoinValue() > 0}]
  <strong>[{oxmultilang ident="CC_BITCOIN_PAID"}]</strong><br>
  [{oxmultilang ident="CC_BITCOIN_ORDER_VALUE"}][{$oView->getBitcoinValue()}] BTC<br>
  [{if $oView->getBitcoinAddress() == ""}]
    <br>
    [{oxmultilang ident="CC_BITCOIN_ENTER_ADDRESS"}]<br>
    <form action="[{ $oViewConf->getSelfLink() }]" method="post">
      [{ $oViewConf->getHiddenSid() }]
      <input type="hidden" name="cur" value="[{ $oCurr->id }]">
      <input type="hidden" name="oxid" value="[{ $oxid }]">
      <input type="hidden" name="cl" value="cc_bitcoin_admin_order">
      <input type="hidden" name="fnc" value="addBitcoinAddress">
      <input type="text" name="bitcoin_address" size="50">
      <input type="submit" value="[{oxmultilang ident="CC_BITCOIN_SEND_ADDRESS"}]">
    </form>
  [{else}]
    [{oxmultilang ident="CC_BITCOIN_CHOOSEN_ADDRESS"}][{$oView->getBitcoinAddress()}] [<a href="https://blockchain.info/address/[{$oView->getBitcoinAddress()}]" target="_blank">Info</a>]
  [{/if}]
[{else}]
  <strong>[{oxmultilang ident="CC_BITCOIN_NOT_PAID"}]</strong>
[{/if}]
</div>