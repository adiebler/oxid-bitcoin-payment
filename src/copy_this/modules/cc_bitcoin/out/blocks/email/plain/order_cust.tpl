[{$smarty.block.parent}]
[{if $payment->oxuserpayments__oxpaymentsid->value == "oxidbitcoin"}]
  [{if $order->oxorder__ccbitcoinaddress->value}]
    [{ oxmultilang ident="CC_BITCOIN_VALUE" }] [{ $order->oxorder__ccbitcoinvalue->value }]
    [{ oxmultilang ident="CC_BITCOIN_ADDRESS" }] [{ $order->oxorder__ccbitcoinaddress->value }]
  [{else}]
    [{ oxmultilang ident="CC_BITCOIN_WAIT_ADDRESS" }][{ $order->oxorder__ccbitcoinvalue->value }]
  [{/if}]
[{/if}]