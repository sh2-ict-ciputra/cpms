USE [cpms]
GO

/****** Object:  View [dbo].[v_library_supplier_project_po_detail]    Script Date: 12/31/2019 1:43:42 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




CREATE VIEW [dbo].[v_library_supplier_project_po_detail]
AS
SELECT        po.id AS po_id, pod.id AS pod_id, proj.id AS proj_id, rekan.id as rekan_id, proj.name AS proj_name, proj.address AS proj_alamat, po.no AS nomor_po, rekan.name AS rekan_name, i.name AS nama_barang, ic.name AS kategori_barang, 
                         pod.harga_satuan, po.created_at, po.updated_at, po.deleted_at, po.created_by, po.updated_by, po.deleted_by, po.inactive_at, po.inactive_by
FROM            dbo.purchaseorders AS po INNER JOIN
                         dbo.purchaseorder_details AS pod ON po.id = pod.purchaseorder_id INNER JOIN
                         dbo.items AS i ON pod.item_id = i.id INNER JOIN
                         dbo.item_categories AS ic ON i.item_category_id = ic.id INNER JOIN
                         dbo.projects AS proj ON po.project_for_id = proj.id INNER JOIN
                         dbo.rekanans AS rekan ON po.rekanan_id = rekan.id
WHERE        (po.deleted_at IS NULL) AND (pod.deleted_at IS NULL) AND (i.deleted_at IS NULL) AND (ic.deleted_at IS NULL) AND (proj.deleted_at IS NULL) AND (rekan.deleted_at IS NULL)




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
         Left = -223
      End
      Begin Tables = 
         Begin Table = "po"
            Begin Extent = 
               Top = 6
               Left = 261
               Bottom = 136
               Right = 448
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "pod"
            Begin Extent = 
               Top = 6
               Left = 486
               Bottom = 136
               Right = 666
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "i"
            Begin Extent = 
               Top = 6
               Left = 704
               Bottom = 136
               Right = 907
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "ic"
            Begin Extent = 
               Top = 138
               Left = 261
               Bottom = 268
               Right = 431
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "proj"
            Begin Extent = 
               Top = 138
               Left = 469
               Bottom = 268
               Right = 689
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "rekan"
            Begin Extent = 
               Top = 138
               Left = 727
               Bottom = 268
               Right = 910
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
      Begin ColumnWidths = 19
         Width = 284
         Width = 1500
         Width = 1500
         Width = 1500
   ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_project_po_detail'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane2', @value=N'      Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
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
      Begin ColumnWidths = 11
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
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_project_po_detail'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=2 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_project_po_detail'
GO


