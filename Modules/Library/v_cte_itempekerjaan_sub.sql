USE [cpms]
GO

/****** Object:  View [dbo].[v_cte_itempekerjaan_sub]    Script Date: 12/31/2019 1:40:54 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_cte_itempekerjaan_sub] AS WITH cte_ipk AS (
	SELECT
		id,
		parent_id,
		code,
		name,
		isDivider
	FROM
		dbo.itempekerjaans AS p 
	WHERE
		( parent_id IS NOT NULL ) UNION ALL
	SELECT
		subpart.id,
		subpart.parent_id,
		subpart.code,
		subpart.name,
		subpart.isDivider
	FROM
		cte_ipk AS part
		INNER JOIN dbo.itempekerjaans AS subpart ON part.id = subpart.parent_id 
	WHERE
		( part.parent_id IS NOT NULL ) 
	) SELECT TOP
	( 100 ) PERCENT *
FROM
	cte_ipk AS cte_ipk_1 
WHERE
	( parent_id IS NOT NULL ) 
ORDER BY
	code


GO


