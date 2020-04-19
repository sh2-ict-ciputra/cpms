USE [cpms]
GO

/****** Object:  View [dbo].[v_library_mou_details]    Script Date: 12/31/2019 1:42:40 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




CREATE VIEW [dbo].[v_library_mou_details]
AS
SELECT        mou.id, mou.nomor_mou, mou.file_mou, mou.jenis_mou, mou.rekan_id, rekan.name AS rekan_name, mou.project_id, proj.name AS proj_name, proj.address AS proj_alamat, mou.item_id, i.name AS item_name, 
                         ic.name AS item_category_name, mou.created_at, mou.updated_at, mou.deleted_at, mou.inactive_at, mou.created_by, mou.updated_by, mou.deleted_by, mou.inactive_by
FROM            dbo.mou AS mou INNER JOIN
                         dbo.rekanans AS rekan ON mou.rekan_id = rekan.id INNER JOIN
                         dbo.items AS i ON mou.item_id = i.id INNER JOIN
                         dbo.projects AS proj ON mou.project_id = proj.id INNER JOIN
                         dbo.item_categories AS ic ON i.item_category_id = ic.id

where 
mou.deleted_at is null
and rekan.deleted_at is null
and i.deleted_at is null
and proj.deleted_at is null
and ic.deleted_at is null





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
         Begin Table = "mou"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 208
            End
            DisplayFlags = 280
            TopColumn = 11
         End
         Begin Table = "rekan"
            Begin Extent = 
               Top = 6
               Left = 246
               Bottom = 136
               Right = 429
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "i"
            Begin Extent = 
               Top = 6
               Left = 467
               Bottom = 136
               Right = 670
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "proj"
            Begin Extent = 
               Top = 138
               Left = 38
               Bottom = 268
               Right = 258
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "ic"
            Begin Extent = 
               Top = 138
               Left = 296
               Bottom = 268
               Right = 466
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
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1170
      ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_mou_details'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane2', @value=N'   Output = 720
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
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_mou_details'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=2 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_mou_details'
GO


