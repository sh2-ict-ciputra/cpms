USE [cpms]
GO

/****** Object:  Table [dbo].[mou]    Script Date: 12/13/2019 7:15:39 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[mou](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[nomor_mou] [nvarchar](100) NOT NULL,
	[rekan_id] [int] NOT NULL,
	[project_id] [int] NOT NULL,
	[item_id] [int] NOT NULL,
	[jenis_mou] [nvarchar](255) NOT NULL,
	[file_mou] [nvarchar](max) NOT NULL,
	[tanggal_berlaku_awal] [datetime] NOT NULL,
	[tanggal_berlaku_akhir] [datetime] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[inactive_at] [datetime] NULL,
	[created_by] [int] NULL,
	[updated_by] [int] NULL,
	[deleted_by] [int] NULL,
	[inactive_by] [int] NULL,
 CONSTRAINT [PK_mou] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO

