ALTER TABLE `oxorder`
ADD `CCBITCOINVALUE` DECIMAL(16,8) NOT NULL DEFAULT 0,
ADD `CCBITCOINADDRESS` VARCHAR(50) NOT NULL DEFAULT '',
ADD `CCBITCOINSECRET` VARCHAR(40) NOT NULL DEFAULT '';

INSERT INTO `oxpayments` (`OXID`, `OXACTIVE`, `OXDESC`, `OXADDSUM`, `OXADDSUMTYPE`, `OXADDSUMRULES`, `OXFROMBONI`, `OXFROMAMOUNT`, `OXTOAMOUNT`, `OXVALDESC`, `OXCHECKED`, `OXDESC_1`, `OXVALDESC_1`, `OXDESC_2`, `OXVALDESC_2`, `OXDESC_3`, `OXVALDESC_3`, `OXLONGDESC`, `OXLONGDESC_1`, `OXLONGDESC_2`, `OXLONGDESC_3`, `OXSORT`, `OXTSPAYMENTID`) VALUES
('oxidbitcoin', 1, 'Bitcoins', 0, 'abs', 0, 0, 0, 1000, '', 0, 'Bitcoins', '', '', '', '', '', 'Bezahlen Sie Ihre Bestellung mit Bitcoins. Der fällige Betrag wird Ihnen in der Bestellübersicht noch einmal angezeigt. Nach dem Bestellabschluss erhalten Sie zeitnah eine E-Mail mit der Empfängeradresse. Die Ware wird nach Zahlungseingang versendet.', 'Pay your order with Bitcoins. The amount due is displayed in the order summary. After the completion of your order you will receive e-mail to the recipients address in a timely manner. Items will be shipped after the payment was received.', '', '', -1, '');