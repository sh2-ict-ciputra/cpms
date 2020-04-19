USE [cpms]
GO

/****** Object:  View [dbo].[v_library_supplier_project_po]    Script Date: 12/31/2019 1:43:27 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO







CREATE VIEW [dbo].[v_library_supplier_project_po]
AS
select 
rekan.id as rekan_id,
rekan.name as supplier_name, 
proj.id as proj_id, 
proj.name AS project_name, 
proj.address as alamat, 
po.no as surat_no,
kaitan = 'PO',
proj.created_at as created_at,
proj.updated_at as updated_at,
										  proj.created_by as created_by,
										  proj.updated_by as updated_by,
										  proj.deleted_at as deleted_at,
										  proj.deleted_by as deleted_by,
										  proj.inactive_at as inactive_at,
										  proj.inactive_by as inactive_by
FROM            dbo.projects AS proj INNER JOIN
                         dbo.purchaseorders AS po ON proj.id = po.project_for_id INNER JOIN
                         dbo.rekanans AS rekan ON po.rekanan_id = rekan.id
						 






GO


