USE [cpms]
GO

/****** Object:  View [dbo].[v_cte_join]    Script Date: 12/31/2019 1:41:17 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_cte_join] AS SELECT TOP (100) PERCENT
ROW_NUMBER() OVER (ORDER BY ipk.code asc) as rowId,
ipk.id, 
ipk.parent_id, 
ipk.code, 
ipk.name,
hs.nilai,
hs.volume,
hs.nilai_total,
hs.satuan,
hs.project_id,
hs.project_name,
hs.isDivider,
hs.tm_id
from v_cte_itempekerjaan_sub as ipk
left JOIN v_cte_hargasatuan_total as hs
on ipk.id = hs.id
--where ipk.code < '200'
--GROUP BY ipk.id, ipk.parent_id, ipk.code, hs.name

order by code asc

GO


