USE [BannerRptpSnapshot]
GO
/****** Object:  StoredProcedure [dbo].[PrepareLocalBannerSchema]    Script Date: 01/29/2016 01:37:35 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[PrepareLocalBannerSchema]
AS
BEGIN

declare @whereDate as VARCHAR(MAX)


-- SSRTEXT
-- 
print 'ssrtext'
--declare @whereDate as VARCHAR(MAX) -- WHERE ' + @whereDate +  ''') ')
set @whereDate = dbo.OracleDateWhereClauses('SSRTEXT_ACTIVITY_DATE',',')
exec('Drop View v_ssrtext')
exec('create view v_ssrtext as SELECT * FROM OPENQUERY(RPTP, ''SELECT SSRTEXT_TERM_CODE, SSRTEXT_CRN, SSRTEXT_SEQNO, SSRTEXT_TEXT, SSRTEXT_ACTIVITY_DATE FROM ssrtext WHERE SSRTEXT_TERM_CODE >= ''''201403'''' AND ' + @whereDate +  ''') ')
exec('drop table ssrtext')
exec('select top 1 * into ssrtext from v_ssrtext')


-- SPRIDEN
-- People's Identities
--
-- spriden_change_ind IS NULL	-- Most current, active identity, not historical
print 'SPRIDEN'
set @whereDate = dbo.OracleDateWhereClauses('SPRIDEN_ACTIVITY_DATE,SPRIDEN_CREATE_DATE',',')
exec('drop view v_spriden')
exec('create view v_spriden as SELECT * FROM OPENQUERY(RPTP, ''SELECT SPRIDEN_PIDM, SPRIDEN_ID, SPRIDEN_LAST_NAME, SPRIDEN_FIRST_NAME, SPRIDEN_MI, SPRIDEN_CHANGE_IND, SPRIDEN_ENTITY_IND, SPRIDEN_ACTIVITY_DATE FROM SPRIDEN WHERE SPRIDEN_ENTITY_IND = ''''P'''' AND spriden_change_ind IS NULL AND ' + @whereDate  + ' '')' )
exec('drop table spriden')
exec('select top 1 * into spriden from v_spriden')
exec('CREATE INDEX [pidm] ON  [dbo].[spriden] ([SPRIDEN_PIDM])')
exec('CREATE INDEX [id] ON  spriden (SPRIDEN_ID)')
exec('CREATE INDEX [demographics] ON  spriden (SPRIDEN_ID,SPRIDEN_PIDM,SPRIDEN_FIRST_NAME,SPRIDEN_LAST_NAME)')


-- SCRRTST
-- Prerequisite Info
--
print 'SCRRTST'
exec('drop view v_scrrtst')
exec('create view v_scrrtst as SELECT * FROM OPENQUERY (RPTP, ''SELECT c.scrrtst_subj_code
     , c.scrrtst_crse_numb
     , c.scrrtst_seqno
     , DECODE(c.scrrtst_connector,''''O'''',''''OR '''',''''A'''',''''AND '''')||c.scrrtst_lparen||CASE WHEN c.scrrtst_subj_code_preq IS NOT NULL THEN
         decode(c.scrrtst_levl_code,null,null,stvlevl_desc||'''' level '''')||c.scrrtst_subj_code_preq||'''' ''''||c.scrrtst_crse_numb_preq||DECODE(c.scrrtst_min_grde,NULL,'''''''','''' Minimum grade of ''''||c.scrrtst_min_grde)||decode(c.scrrtst_concurrency_ind,null,'''''''',DECODE(c.scrrtst_subj_code_preq,NULL,'''' '''','''' OR '''')||''''concurrent enrollment'''')
       ELSE
         t.stvtesc_desc||DECODE(c.scrrtst_test_score,NULL,'''''''','''' score ''''||c.scrrtst_test_score)
       END||c.scrrtst_rparen prereq
FROM SCRRTST c
   , stvlevl l
   , stvtesc t
WHERE c.scrrtst_term_code_eff = (SELECT MAX(scrrtst_term_code_eff)
                                 FROM scrrtst
                                 WHERE scrrtst_subj_code = c.scrrtst_subj_code
                                 AND scrrtst_crse_numb = c.scrrtst_crse_numb)
AND c.scrrtst_crse_numb LIKE ''''A%''''
AND c.scrrtst_levl_code = l.stvlevl_code(+)
AND c.scrrtst_tesc_code = t.stvtesc_code(+)
AND c.scrrtst_seqno IS NOT NULL
ORDER BY c.scrrtst_subj_code, c.scrrtst_crse_numb, c.scrrtst_seqno'')')
exec('drop table scrrtst')
exec('select top 1 * into scrrtst from v_scrrtst')
exec('CREATE INDEX [scrrtst_subj_code] ON scrrtst (scrrtst_subj_code)')
exec('CREATE INDEX [scrrtst_crse_numb] ON scrrtst (scrrtst_crse_numb)')
exec('CREATE INDEX [scrrtst_seqno] ON scrrtst (scrrtst_seqno)')
exec('CREATE INDEX [prereq] ON scrrtst (prereq)')


-- STVTERM
-- Semesters
print 'STVTERM'
--declare @whereDate as VARCHAR(MAX)
set @whereDate = dbo.OracleDateWhereClauses('STVTERM_START_DATE,STVTERM_END_DATE,STVTERM_ACTIVITY_DATE,STVTERM_HOUSING_START_DATE,STVTERM_HOUSING_END_DATE',',')
exec('drop view v_stvterm')
exec('create view v_stvterm AS SELECT * FROM OPENQUERY(RPTP, ''SELECT * FROM stvterm WHERE (stvterm_code like ''''2___01'''' OR stvterm_code like ''''2___02'''' OR stvterm_code like ''''2___03'''') AND ' + @whereDate +  ''') ')
exec('drop table stvterm')
exec('select top 1 * into stvterm from v_stvterm where stvterm_end_date > (CURRENT_TIMESTAMP - (365*2)) AND stvterm_start_date < (CURRENT_TIMESTAMP + 180)')


-- SCBCRSE
-- Courses
-- Amber Brubaker 2007, "The scbcrse_eff_term means that you need the most recent effective term for that course. Some courses have title changes but the changes aren''t reflected every term so you just want to make sure that it is the most recent change. You can''t use max(scbcrse_activity_date) because that doesn''t always show the most recent term. "-- courses.scbcrse_eff_term = (SELECT MAX(scbcrse_eff_term) FROM scbcrse x WHERE x.scbcrse_subj_code = courses.scbcrse_subj_code AND x.scbcrse_crse_numb = courses.scbcrse_crse_numb)
print 'SCBCRSE'
--declare @whereDate as VARCHAR(MAX)
set @whereDate = dbo.OracleDateWhereClauses('SCBCRSE_ACTIVITY_DATE',',')
exec('drop view v_scbcrse')
exec('create view v_scbcrse as SELECT * FROM OPENQUERY(RPTP, ''SELECT SCBCRSE_SUBJ_CODE, SCBCRSE_DEPT_CODE, SCBCRSE_CRSE_NUMB, SCBCRSE_EFF_TERM, SCBCRSE_TITLE, SCBCRSE_CREDIT_HR_IND, SCBCRSE_CREDIT_HR_LOW, SCBCRSE_CREDIT_HR_HIGH, SCBCRSE_ACTIVITY_DATE FROM scbcrse courses WHERE SCBCRSE_CRSE_NUMB LIKE ''''A%'''' AND courses.scbcrse_eff_term = (SELECT MAX(scbcrse_eff_term) FROM scbcrse x WHERE x.scbcrse_subj_code = courses.scbcrse_subj_code AND x.scbcrse_crse_numb = courses.scbcrse_crse_numb) AND ' + @whereDate +  ''') ')
exec('drop table scbcrse')
exec('select top 1 * into scbcrse from v_scbcrse')
exec('CREATE INDEX [SUBJ_NUM] ON scbcrse (scbcrse_subj_code,scbcrse_crse_numb)')


-- SCBDESC
-- Course descriptions
print 'SCBDESC'
--declare @whereDate as VARCHAR(MAX)
set @whereDate = dbo.OracleDateWhereClauses('SCBDESC_ACTIVITY_DATE',',')
exec('drop view v_SCBDESC')
exec('create view v_SCBDESC as SELECT * FROM OPENQUERY(RPTP, ''SELECT SCBDESC_SUBJ_CODE, SCBDESC_CRSE_NUMB, SCBDESC_TERM_CODE_EFF, SCBDESC_ACTIVITY_DATE, SCBDESC_TEXT_NARRATIVE FROM SCBDESC courses WHERE SCBDESC_CRSE_NUMB LIKE ''''A%'''' '') ')
exec('drop table SCBDESC')
exec('select top 1 * into SCBDESC from v_SCBDESC')
exec('CREATE INDEX [SUBJ_NUM] ON SCBDESC (SCBDESC_subj_code,SCBDESC_crse_numb)')


-- SSBSECT
-- Sections
print 'SSBSECT'
--declare @whereDate as VARCHAR(MAX)
set @whereDate = dbo.OracleDateWhereClauses('SSBSECT_CENSUS_ENRL_DATE,SSBSECT_ACTIVITY_DATE,SSBSECT_PTRM_START_DATE,SSBSECT_PTRM_END_DATE,SSBSECT_CENSUS_2_DATE,SSBSECT_ENRL_CUT_OFF_DATE,SSBSECT_ACAD_CUT_OFF_DATE,SSBSECT_DROP_CUT_OFF_DATE,SSBSECT_REG_FROM_DATE,SSBSECT_REG_TO_DATE,SSBSECT_LEARNER_REGSTART_FDATE,SSBSECT_LEARNER_REGSTART_TDATE',',')
exec('drop view v_ssbsect')
exec('create view v_ssbsect as SELECT * FROM OPENQUERY(RPTP, ''SELECT SSBSECT_TERM_CODE, SSBSECT_CRN, SSBSECT_CRSE_TITLE, SSBSECT_SEATS_AVAIL, SSBSECT_CREDIT_HRS, SSBSECT_CAMP_CODE, SSBSECT_PTRM_START_DATE, SSBSECT_SUBJ_CODE, SSBSECT_WAIT_COUNT, SSBSECT_WAIT_AVAIL, SSBSECT_WAIT_CAPACITY, SSBSECT_SEQ_NUMB, SSBSECT_SSTS_CODE, SSBSECT_ACTIVITY_DATE, SSBSECT_CRSE_NUMB FROM ssbsect WHERE ssbsect_term_code >= ''''201403'''' AND ssbsect_camp_code IN (''''I'''') AND ssbsect_ssts_code <> ''''C''''  and ' + @whereDate +  ''') ')
exec('drop table ssbsect')
exec('select top 1 * into ssbsect from v_ssbsect where ssbsect_term_code in (select stvterm_code from stvterm)')
exec('CREATE INDEX [primkeya] ON ssbsect (ssbsect_term_code,ssbsect_crn)')
exec('CREATE INDEX [primkeyb] ON ssbsect (ssbsect_term_code,ssbsect_crn,ssbsect_subj_code,ssbsect_crse_numb,ssbsect_seq_numb)')


-- SIRASGN
-- Faculty assignments
print 'SIRASGN'
--declare @whereDate as VARCHAR(MAX) -- ' + @whereDate +  ''') ')
set @whereDate = dbo.OracleDateWhereClauses('SIRASGN_ACTIVITY_DATE,SIRASGN_INCR_ENRL_DATE',',')
exec('drop view v_sirasgn')
exec('create view v_sirasgn as SELECT * FROM OPENQUERY(RPTP, ''SELECT SIRASGN_TERM_CODE, SIRASGN_CRN, SIRASGN_PIDM, SIRASGN_PRIMARY_IND, SIRASGN_ACTIVITY_DATE FROM sirasgn, ssbsect WHERE (sirasgn_term_code LIKE ''''2___01'''' OR sirasgn_term_code LIKE ''''2___02'''' OR sirasgn_term_code LIKE ''''2___03'''') AND ssbsect_camp_code IN (''''I'''') AND sirasgn.sirasgn_term_code = ssbsect.ssbsect_term_code AND sirasgn.sirasgn_crn = ssbsect.ssbsect_crn AND ssbsect_ssts_code <> ''''C'''' and ' + @whereDate +  ''') ')
exec('drop table sirasgn')
exec('select top 1 * into sirasgn from v_sirasgn where sirasgn_term_code in (select stvterm_code from stvterm)')
exec('CREATE INDEX [primkey] ON sirasgn (sirasgn_crn,sirasgn_term_code)')


-- STVSUBJ
-- Subjects
print 'STVSUBJ'
--declare @whereDate as VARCHAR(MAX) -- ' + @whereDate +  ''') ')
set @whereDate = dbo.OracleDateWhereClauses('STVSUBJ_ACTIVITY_DATE',',')
exec('Drop View v_STVSUBJ')
exec('create view v_STVSUBJ as SELECT * FROM OPENQUERY(RPTP, ''SELECT * FROM STVSUBJ WHERE ' + @whereDate +  ''') ')
exec('drop table STVSUBJ')
exec('select top 1 * into STVSUBJ from v_STVSUBJ ')
exec('CREATE INDEX [code] ON [dbo].STVSUBJ ([STVSUBJ_CODE])')


-- STVDEPT
-- 
print 'STVDEPT'
--declare @whereDate as VARCHAR(MAX) --  WHERE ' + @whereDate +  ''') ')
set @whereDate = dbo.OracleDateWhereClauses('STVDEPT_ACTIVITY_DATE',',')
exec('Drop View v_STVDEPT')
exec('create view v_STVDEPT as SELECT * FROM OPENQUERY(RPTP, ''SELECT * FROM STVDEPT WHERE ' + @whereDate +  ''') ')
exec('drop table STVDEPT')
exec('select top 1 * into STVDEPT from v_STVDEPT')


-- SSRMEET
-- 
--SSRMEET_ACTIVITY_DATE
--SSRMEET_START_DATE
--SSRMEET_END_DATE
print 'ssrmeet'
--declare @whereDate as VARCHAR(MAX) -- WHERE ' + @whereDate +  ''') ')
set @whereDate = dbo.OracleDateWhereClauses('SSRMEET_ACTIVITY_DATE,SSRMEET_START_DATE,SSRMEET_END_DATE',',')
exec('Drop View v_ssrmeet')
exec('create view v_ssrmeet as SELECT * FROM OPENQUERY(RPTP, ''SELECT * FROM ssrmeet WHERE SSRMEET_CRN NOT LIKE ''''A%'''' AND SSRMEET_TERM_CODE <> ''''EVENT'''' AND SSRMEET_TERM_CODE >= ''''201403'''' AND ' + @whereDate +  ''') ')
exec('drop table ssrmeet')
exec('select top 1 * into ssrmeet from v_ssrmeet')


END
