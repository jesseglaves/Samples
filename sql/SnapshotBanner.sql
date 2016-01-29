USE [BannerRptpSnapshot]
GO
/****** Object:  StoredProcedure [dbo].[SnapshotBanner]    Script Date: 01/29/2016 01:40:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE [dbo].[SnapshotBanner]
AS
BEGIN
SET NOCOUNT ON

exec SnapshotBannerTable 'v_scbcrse','scbcrse';
exec snapshotbannertable 'v_scbdesc','scbdesc';
exec SnapshotBannerTable 'v_scrrtst','scrrtst';
exec SnapshotBannerTable 'v_sirasgn','sirasgn';
exec SnapshotBannerTable 'v_spriden','spriden';
exec SnapshotBannerTable 'v_ssbsect','ssbsect';
exec SnapshotBannerTable 'v_ssrmeet','ssrmeet';
exec SnapshotBannerTable 'v_ssrtext','ssrtext';
exec SnapshotBannerTable 'v_stvdept','stvdept';
exec SnapshotBannerTable 'v_stvsubj','stvsubj';
exec SnapshotBannerTable 'v_stvterm','stvterm'; 

END
