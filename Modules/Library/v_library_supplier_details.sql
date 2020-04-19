USE [cpms]
GO

/****** Object:  View [dbo].[v_library_supplier_details]    Script Date: 12/31/2019 1:42:53 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[v_library_supplier_details]
AS
SELECT					MAX(rekan.id) AS id, rekan_grup.id AS id_grup, 
						MAX(rekan.name) AS nama_supplier, 
						MAX(item.name) AS nama_barang, 
						MAX(item_kat.name) AS jenis_barang, 
						MAX(rekan_grup.npwp_no) AS npwp, 
						MAX(rekan.cp_name) AS pic_owner_name, 
						MAX(rekan.cp_whatsap) AS pic_owner_telp, 
						MAX(rekan.sales_name) AS pic_sales_name, 
						MAX(rekan.sales_tlp) AS pic_sales_telp,
											Max(rekan.created_at) as created_at,
										  Max(rekan.updated_at) as updated_at,
										  Max(rekan.created_by) as created_by,
										  Max(rekan.updated_by) as updated_by,
										  Max(rekan.deleted_at) as deleted_at,
										  Max(rekan.deleted_by) as deleted_by,
										  Max(rekan.inactive_at) as inactive_at,
										  Max(rekan.inactive_by) as inactive_by
FROM dbo.rekanans AS rekan LEFT OUTER JOIN
	 dbo.rekanan_groups AS rekan_grup ON rekan.rekanan_group_id = rekan_grup.id LEFT OUTER JOIN
	 dbo.purchaseorders AS po ON rekan.id = po.rekanan_id LEFT OUTER JOIN
	(SELECT purchaseorder_id, item_id FROM dbo.purchaseorder_details AS po_detail 
	GROUP BY purchaseorder_id, item_id) AS po_details ON po.id = po_details.purchaseorder_id LEFT OUTER JOIN
	 dbo.items AS item ON po_details.item_id = item.id LEFT OUTER JOIN
     dbo.item_categories AS item_kat ON item.item_category_id = item_kat.id 
	GROUP BY rekan_grup.id



GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "rekan"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 237
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "rekan_grup"
            Begin Extent = 
               Top = 6
               Left = 275
               Bottom = 136
               Right = 492
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "rekan_price"
            Begin Extent = 
               Top = 6
               Left = 530
               Bottom = 136
               Right = 729
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "po"
            Begin Extent = 
               Top = 138
               Left = 38
               Bottom = 268
               Right = 241
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "po_details"
            Begin Extent = 
               Top = 138
               Left = 279
               Bottom = 234
               Right = 475
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "item"
            Begin Extent = 
               Top = 138
               Left = 513
               Bottom = 268
               Right = 732
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "item_kat"
            Begin Extent = 
               Top = 234
               Left = 279
               Bottom = 364
               Right = 465
            End
            DisplayFlags = 280
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_details'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane2', @value=N'
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
      Begin ColumnWidths = 9
         Width = 284
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 12
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_details'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=2 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_details'
GO


