[{$smarty.block.parent}]
[{if $payment->oxuserpayments__oxpaymentsid->value == "oxidbitcoin" && $order->oxorder__ccbitcoinaddress->value == "" }]
[{ oxmultilang ident="CC_BITCOIN_SEND_ADDRESS" }]
[{/if}]