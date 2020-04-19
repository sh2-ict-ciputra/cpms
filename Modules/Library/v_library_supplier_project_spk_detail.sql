USE [cpms]
GO

/****** Object:  View [dbo].[v_library_supplier_project_spk_detail]    Script Date: 12/31/2019 1:44:17 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




CREATE VIEW [dbo].[v_library_supplier_project_spk_detail]
AS
SELECT        
spk.id AS spk_id, 
spk.name AS spk_name, 
spk.no AS spk_no, 
rekan.id AS rekan_id, 
rekan.name AS rekan_name, 
proj.id AS proj_id, 
proj.address AS proj_alamat, 
proj.name AS proj_name, 
ipk.name AS ipk_name, 
ipk.code AS ipk_code,
tmd.nilai as nilai,
spk.created_at as created_at,
spk.created_by as created_by,
spk.updated_at as updated_at,
spk.updated_by as updated_by,
spk.deleted_at as deleted_at,
spk.deleted_by as deleted_by,
spk.inactive_at as inactive_at,
spk.inactive_by as inactive_by
FROM            
dbo.spks AS spk INNER JOIN
dbo.rekanans AS rekan ON spk.rekanan_id = rekan.id INNER JOIN
dbo.tender_rekanans AS tr ON spk.tender_rekanan_id = tr.id INNER JOIN
dbo.tender_menangs AS tm ON tr.id = tm.tender_rekanan_id INNER JOIN
dbo.tender_menang_details AS tmd ON tm.id = tmd.tender_menang_id INNER JOIN
dbo.itempekerjaans AS ipk ON tmd.itempekerjaan_id = ipk.id INNER JOIN
dbo.projects AS proj ON spk.project_id = proj.id
WHERE        
(rekan.deleted_at IS NULL) 
AND (tr.deleted_at IS NULL) 
AND (tm.deleted_at IS NULL) 
AND (tmd.deleted_at IS NULL) AND (ipk.deleted_at IS NULL) AND (proj.deleted_at IS NULL) AND (ipk.parent_id IS NOT NULL)




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
         Begin Table = "spk"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 237
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "rekan"
            Begin Extent = 
               Top = 6
               Left = 275
               Bottom = 136
               Right = 458
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "tr"
            Begin Extent = 
               Top = 6
               Left = 496
               Bottom = 136
               Right = 675
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "tm"
            Begin Extent = 
               Top = 6
               Left = 713
               Bottom = 136
               Right = 898
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "tmd"
            Begin Extent = 
               Top = 138
               Left = 38
               Bottom = 268
               Right = 274
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "ipk"
            Begin Extent = 
               Top = 138
               Left = 312
               Bottom = 268
               Right = 482
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "proj"
            Begin Extent = 
               Top = 138
               Left = 520
               Bottom = 268
               Right = 740
            End
            DisplayFlags = 280
            TopColumn = 0
  ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_project_spk_detail'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane2', @value=N'       End
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
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_project_spk_detail'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=2 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_supplier_project_spk_detail'
GO


