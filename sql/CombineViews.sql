SELECT     dbo.view_ssbsect.term_code, dbo.view_ssbsect.crn, dbo.view_ssbsect.section_title, dbo.view_ssbsect.section, dbo.view_ssbsect.seats_available, 
                      dbo.view_ssbsect.section_credits, dbo.view_ssbsect.SSBSECT_SSTS_CODE, dbo.view_ssbsect.campus_code, dbo.view_ssbsect.wait_count, 
                      dbo.view_ssbsect.wait_available, dbo.view_ssbsect.wait_capacity, dbo.view_scbdesc.course_description, dbo.view_scbcrse.subject_code, 
                      dbo.view_scbcrse.course_title, dbo.view_scbcrse.course_credits, dbo.view_ssbsect.[LEVEL], dbo.view_ssrtext.ssrtext, 
                      start_dates = SUBSTRING
                          ((SELECT     (',' + ISNULL(CAST([start_date] AS VARCHAR(50)), '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = term_code AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), end_dates = SUBSTRING
                          ((SELECT     (',' + ISNULL(CAST([end_date] AS VARCHAR(50)), '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = term_code AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), [types] = SUBSTRING
                          ((SELECT     (',' + ISNULL([type], '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = term_code AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), buildings = SUBSTRING
                          ((SELECT     (',' + ISNULL(building, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = term_code AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), rooms = SUBSTRING
                          ((SELECT     (',' + ISNULL(room, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), sundays = SUBSTRING
                          ((SELECT     (',' + ISNULL(sunday, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), mondays = SUBSTRING
                          ((SELECT     (',' + ISNULL(monday, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), tuesdays = SUBSTRING
                          ((SELECT     (',' + ISNULL(tuesday, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), wednesdays = SUBSTRING
                          ((SELECT     (',' + ISNULL(wednesday, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), thursdays = SUBSTRING
                          ((SELECT     (',' + ISNULL(thursday, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), fridays = SUBSTRING
                          ((SELECT     (',' + ISNULL(friday, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), saturdays = SUBSTRING
                          ((SELECT     (',' + ISNULL(saturday, '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), start_times = SUBSTRING
                          ((SELECT     (',' + ISNULL(LEFT(start_time, 2) + ':' + RIGHT(start_time, 2), '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000), end_times = SUBSTRING
                          ((SELECT     (',' + ISNULL(LEFT(end_time, 2) + ':' + RIGHT(end_time, 2), '0'))
                              FROM         BannerViews.dbo.view_ssrmeet
                              WHERE     SSRMEET_TERM_CODE = TERM_CODE AND SSRMEET_CRN = CRN
                              ORDER BY end_date FOR XML PATH('')), 2, 2000)
FROM         dbo.view_ssrtext INNER JOIN
                      dbo.view_ssrmeet ON dbo.view_ssrtext.ssrtext_crn = dbo.view_ssrmeet.SSRMEET_CRN AND 
                      dbo.view_ssrtext.ssrtext_term_code = dbo.view_ssrmeet.SSRMEET_TERM_CODE RIGHT JOIN
                      dbo.view_ssbsect LEFT JOIN
                      dbo.view_scbcrse ON dbo.view_ssbsect.SSBSECT_CRSE_NUMB = dbo.view_scbcrse.SCBCRSE_CRSE_NUMB AND 
                      dbo.view_ssbsect.SSBSECT_SUBJ_CODE = dbo.view_scbcrse.subject_code LEFT JOIN
                      dbo.view_scbdesc ON dbo.view_ssbsect.SSBSECT_SUBJ_CODE = dbo.view_scbdesc.SCBDESC_SUBJ_CODE AND 
                      dbo.view_ssbsect.SSBSECT_CRSE_NUMB = dbo.view_scbdesc.SCBDESC_CRSE_NUMB ON 
                      dbo.view_ssrmeet.SSRMEET_TERM_CODE = dbo.view_ssbsect.term_code AND dbo.view_ssrmeet.SSRMEET_CRN = dbo.view_ssbsect.crn
WHERE     (dbo.view_ssbsect.campus_code = 'I') AND (dbo.view_ssbsect.section <> 'R40') AND (SSBSECT_SSTS_CODE <> 'I') AND 
                      (dbo.view_ssbsect.section <> 'I40') AND (dbo.view_scbdesc.SCBDESC_ACTIVITY_DATE =
                          (SELECT     MAX(SCBDESC_ACTIVITY_DATE) AS Expr1
                            FROM          BannerRptpSnapshot.dbo.SCBDESC AS insideTable
                            WHERE      (SCBDESC_SUBJ_CODE = dbo.view_scbdesc.SCBDESC_SUBJ_CODE) AND 
                                                   (SCBDESC_CRSE_NUMB = dbo.view_scbdesc.SCBDESC_CRSE_NUMB)) OR
                      dbo.view_scbdesc.SCBDESC_ACTIVITY_DATE IS NULL) AND (dbo.view_ssrmeet.SSRMEET_ACTIVITY_DATE =
                          (SELECT     MAX(SSRMEET_ACTIVITY_DATE) AS Expr1
                            FROM          BannerRptpSnapshot.dbo.ssrmeet AS insideTable
                            WHERE      (SSRMEET_CRN = dbo.view_ssrmeet.SSRMEET_CRN) AND (SSRMEET_TERM_CODE = dbo.view_ssrmeet.SSRMEET_TERM_CODE))) AND
                       (dbo.view_ssbsect.SSBSECT_SSTS_CODE <> 'C') AND (dbo.view_ssbsect.term_code = '201403')