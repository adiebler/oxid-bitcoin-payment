[{$smarty.block.parent}]
[{if $payment->oxuserpayments__oxpaymentsid->value == "oxidbitcoin"}]
  [{if $order->oxorder__ccbitcoinaddress->value}]
    <strong>[{ oxmultilang ident="CC_BITCOIN_VALUE" }]</strong>[{ $order->oxorder__ccbitcoinvalue->value }]<br>
    <strong>[{ oxmultilang ident="CC_BITCOIN_ADDRESS" }]</strong>[{ $order->oxorder__ccbitcoinaddress->value }]<br>
    <br>
    <img src="https://blockchain.info/de/qr?data=[{ $order->oxorder__ccbitcoinaddress->value }]&size=200">
  [{else}]
    [{ oxmultilang ident="CC_BITCOIN_WAIT_ADDRESS" }]
  [{/if}]
[{/if}]