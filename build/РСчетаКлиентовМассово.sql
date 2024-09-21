INSERT INTO client_firm_bank_accounts
(client_id,firm_id,
 ext_bank_account_id,ext_bank_account_descr
)
(SELECT
	cl.id AS client_id,
	49 AS firm_id,
	'306a5f7c-2546-11ea-bd78-f2158bff1135' AS ext_bank_account_id,
	'рублевый СБЕРБАНК  (ПолимерПласт)' AS ext_bank_account_descr
FROM
clients cl
WHERE (
select b.client_id from client_firm_bank_accounts AS b where
b.firm_id=49 AND b.ext_bank_account_id='0ed350f8-6bba-11ed-9dc0-f2158bff1135'
	AND b.client_id=cl.id
) IS NULL
ORDER BY cl.id
	  )
/*
select client_id from client_firm_bank_accounts where
firm_id=49 AND ext_bank_account_id='306a5f7c-2546-11ea-bd78-f2158bff1135' AND client_id=101
*/

--client_id=720
--49	'306a5f7c-2546-11ea-bd78-f2158bff1135'	'рублевый СБЕРБАНК  (ПолимерПласт)'

--10/01/24
UPDATE client_firm_bank_accounts
SET ext_bank_account_id='196cf1e3-8db7-11ee-bc96-f2158bff1135',
ext_bank_account_descr = 'ПолимерПласт  СОВКОМБАНК'
WHERE firm_id=49 AND ext_bank_account_id='0ed350f8-6bba-11ed-9dc0-f2158bff1135'


--Все банковские счета
(select ext_bank_account_descr from client_firm_bank_accounts where ext_bank_account_id='ce15d9e6-f2e9-11ec-94de-f2158bff1135' limit 1)
union all
(select ext_bank_account_descr from client_firm_bank_accounts where ext_bank_account_id='196cf1e3-8db7-11ee-bc96-f2158bff1135' limit 1)
union all
(select ext_bank_account_descr from client_firm_bank_accounts where ext_bank_account_id='bd3e92bb-2b7c-11ee-a230-f2158bff1135' limit 1)

--26/02/24
--ДекорБД Совком <- Окрытие
UPDATE client_firm_bank_accounts
SET ext_bank_account_id='bbf7d909-c583-11ee-8963-f2158bff1135',
ext_bank_account_descr = 'Декор БД (Совкомбанк)'
WHERE firm_id=57 AND ext_bank_account_id='bd3e92bb-2b7c-11ee-a230-f2158bff1135'
/*
 * 
 * был такой счет "bd3e92bb-2b7c-11ee-a230-f2158bff1135"	"ДЕКОР БД Сбербанк " у всех, где firm_id=57
 */

--ДекорБД Совком <- Окрытие
UPDATE client_firm_bank_accounts
SET ext_bank_account_id='83be7eea-8dca-11ee-bc96-f2158bff1135',
ext_bank_account_descr = 'Геопласт ООО Совкомбанк '
WHERE firm_id=56 AND ext_bank_account_id<>'83be7eea-8dca-11ee-bc96-f2158bff1135'


