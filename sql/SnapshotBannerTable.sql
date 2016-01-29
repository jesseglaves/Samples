USE [BannerRptpSnapshot]
GO
/****** Object:  StoredProcedure [dbo].[SnapshotBannerTable]    Script Date: 01/29/2016 01:42:44 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[SnapshotBannerTable] 
	-- Add the parameters for the stored procedure here
	@sourceView varchar(256),
	@targetTable varchar(256)
AS
BEGIN

SET NOCOUNT ON

print '--------------------------------------------------------------------------------'
print @targetTable
print ''
print cast(GetDate() as varchar) + ' START ' + @sourceView + ' > ' + @targetTable

-- variable to count the records in the banner table
Declare @recordCount as bigint

-- variable to build dynamic sql query strings
Declare @sqlQuery as varchar(8000)
Declare @sqlCountQuery as nvarchar(500)

-- copy the source banner table into a global temporary table with dynamic sql
Declare @tempTableName as varchar(30)
set @tempTableName = '##' + @targetTable + '_Temp'

set @sqlQuery = 'select * into ' + @tempTableName + ' from ' + @sourceView
Exec (@sqlQuery)
print Cast(GetDate() as varchar) + ' create in memory ' + @tempTableName + ' (' + cast(@@ROWCOUNT as varchar) + ' rows)'


-- count the records from banner in the temp table
set @sqlCountQuery = N'select @countOUT=count(*) from ' + @tempTableName
exec sp_executesql @sqlCountQuery, N'@countOUT nvarchar(32) OUTPUT', @countOUT = @recordCount OUTPUT

-- set @recordCount = (select count(*) from ' + @tempTableName + ')

-- check the record count to determine whether to snapshot
IF @recordCount > 0
	BEGIN
		-- SUCCESS! We have rows to copy
		-- delete the data out of the local table
		set @sqlQuery = 'delete from ' + @targetTable
		Exec (@sqlQuery)
		print Cast(GetDate() as varchar) + ' ' + @targetTable + ' local delete (' + cast(@@ROWCOUNT as varchar) + ' rows)'
		
		-- copy the data from the temp table to the local table
		set @sqlQuery = 'insert into ' + @targetTable + ' select * from ' + @tempTableName + ''
		Exec (@sqlQuery)
		print Cast(GetDate() as varchar) + ' ' + @targetTable + ' clone RPTP (' + cast(@@ROWCOUNT as varchar) + ' rows)'
	END
ELSE
	-- FAIL! Don't copy and print an error
	print Cast(GetDate() as varchar) + ' Error did not get any records from ' + @sourceView


-- Clean up
print Cast(GetDate() as varchar) + ' drop in memory ' + @tempTableName + ''
exec('DROP table ' + @tempTableName)

print Cast(GetDate() as varchar) + ' STOP ' + @sourceView + ' > ' + @targetTable
print '--------------------------------------------------------------------------------'
print ''
print ''

END
