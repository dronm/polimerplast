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
b.firm_id=49 AND b.ext_bank_account_id='306a5f7c-2546-11ea-bd78-f2158bff1135'
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
