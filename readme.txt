==Title==
CommerceCoding Bitcoin Payment

==Author==
Alexander Diebler

==Prefix==
cc

==Version==
0.2.0

==Link==
http://www.commerce-coding.de

==Mail==
info@commerce-coding.de

==Description==
Accept Bitcoins as payment method.

==Extend==
*Module_Config
--saveConfVars

*Details
--getBitcoinPrice

*Basket
--getBitcoinPrice

*Order
--getBitcoinPrice

*oxorder
--finalizeOrder

*oxemail
--sendBitcoinAddressToUser

==Installation==
* copy contents from copy_this directory into the shop root
* update the database with install.sql in the admin area via Service/Tools
* clear tmp directory