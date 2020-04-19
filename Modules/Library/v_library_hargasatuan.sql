USE [cpms]
GO

/****** Object:  View [dbo].[v_library_hargasatuan]    Script Date: 12/31/2019 1:41:31 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_library_hargasatuan]
AS
SELECT        ipk.id AS ipk_id, 
ipk.parent_id as ipk_parent,
ipk.isDivider as ipk_isDivider,
ipk.code AS coa, 
ipk.name AS ipk_name, 
proj.id AS proj_id, 
proj.code AS proj_code,
proj.name as proj_name,
tmd.id AS tmd_id, 
tmd.nilai as tmd_nilai, 
tmd.satuan as tmd_satuan,
tmd.volume as tmd_volume,
tm.id AS tm_id,
tr.rekanan_id as rekan_id
FROM            
dbo.itempekerjaans AS ipk inner JOIN
dbo.tender_menang_details AS tmd ON ipk.id = tmd.itempekerjaan_id inner JOIN
dbo.tender_menangs AS tm ON tmd.tender_menang_id = tm.id inner JOIN
dbo.tender_rekanans AS tr ON tm.tender_rekanan_id = tr.id inner JOIN
dbo.spks AS spk ON tr.id = spk.tender_rekanan_id inner JOIN
dbo.projects AS proj ON spk.project_id = proj.id
WHERE        
(ipk.deleted_at IS NULL) AND 
(tmd.deleted_at IS NULL) AND 
(tm.deleted_at IS NULL) AND 
(tr.deleted_at IS NULL) AND 
(spk.deleted_at IS NULL) AND 
(proj.deleted_at IS NULL)











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
         Begin Table = "ipk"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 208
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "tmd"
            Begin Extent = 
               Top = 6
               Left = 246
               Bottom = 136
               Right = 482
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "tm"
            Begin Extent = 
               Top = 138
               Left = 38
               Bottom = 268
               Right = 223
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "tr"
            Begin Extent = 
               Top = 138
               Left = 261
               Bottom = 268
               Right = 440
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "spk"
            Begin Extent = 
               Top = 138
               Left = 478
               Bottom = 268
               Right = 677
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "proj"
            Begin Extent = 
               Top = 270
               Left = 38
               Bottom = 400
               Right = 258
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
        ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_hargasatuan'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane2', @value=N' Width = 1500
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
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_hargasatuan'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=2 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_library_hargasatuan'
GO


