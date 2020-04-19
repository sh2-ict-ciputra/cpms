USE [cpms]
GO

/****** Object:  View [dbo].[v_cte_hargasatuan_total]    Script Date: 12/31/2019 1:40:09 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_cte_hargasatuan_total] AS WITH cte_ipk AS (
		SELECT
			p.id,
			p.parent_id,
			p.code,
			p.name,
			p.isDivider
		FROM
			dbo.itempekerjaans AS p 
		WHERE
			( p.parent_id is not null ) UNION ALL
		SELECT
			subpart.id,
			subpart.parent_id,
			subpart.code,
			subpart.name,
			subpart.isDivider
		FROM
			cte_ipk AS part
			INNER JOIN itempekerjaans AS subpart 
			ON part.id = subpart.parent_id 
		WHERE
			( part.parent_id is not null ) 
		) 
		
		
	SELECT TOP
	( 100 ) PERCENT
		cte.id,
		cte.parent_id,
		(select code from itempekerjaans where id = cte.parent_id) as parent_code,
		cte.code,
		cte.name,
		tmd.nilai,
		tmd.volume,
		tmd.satuan,
		tm.id as tm_id,
		tr.rekanan_id as rekan_id,
		cte.isDivider,
		(tmd.nilai * tmd.volume) as nilai_total,
		spk.project_id,
		proj.name as project_name,
		proj.code as project_code
	FROM
		tender_menang_details as tmd
		inner join cte_ipk as cte on tmd.itempekerjaan_id = cte.id
		inner join tender_menangs as tm on tmd.tender_menang_id = tm.id
		inner join tender_rekanans as tr on tm.tender_rekanan_id = tr.id
		inner join spks as spk on tr.id = spk.tender_rekanan_id
		inner join projects as proj on spk.project_id = proj.id
	WHERE
		( parent_id is not null ) 
	ORDER BY
		code asc

GO


