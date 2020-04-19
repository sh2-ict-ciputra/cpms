USE [cpms]
GO

/****** Object:  View [dbo].[v_rekanan_pricelist_with_item]    Script Date: 12/31/2019 1:44:34 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_rekanan_pricelist_with_item] AS select 
rekan.id as rekan_id,
rekan.name as rekan_name,
rg.id as rekan_group_id,
rg.name as rekan_group_name,
rp.id as pricelist_id,
rp.berlaku_dari_tanggal as pricelist_berlaku_dari_tanggal,
rp.berlaku_sampai_tanggal as pricelist_berlaku_sampai_tanggal,
rp.price_file as price_file,
rp.keterangan as keterangan,
i.id as item_id,
i.name as item_name,
ic.id as item_category_id,
ic.name as item_category_name,
rp.updated_at,
rp.updated_by,
rp.created_at,
rp.created_by,
rp.deleted_at,
rp.deleted_by,
rp.inactive_at,
rp.inactive_by
from rekanans as rekan
join rekanan_groups as rg on rekan.rekanan_group_id = rg.id
join rekanan_pricelist as rp on rg.id = rp.rekanan_group_id
join rekanan_items as ri on rp.id = ri.pricelist_id
join items as i on ri.item_id = i.id
join item_categories as ic on i.item_category_id = ic.id
where 
rekan.deleted_at is null 
and rg.deleted_at is null
--and rp.deleted_at is null
and ri.deleted_at is null
and i.deleted_at is null
and ic.deleted_at is null

GO


