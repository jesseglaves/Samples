<?
	$crn = $_GET["crn"];
	$term = $_GET["term"];
	$page_title = "Searchable Schedule";
	
	$myServer = "";
	$myUser = "";
	$myPass = "";
	$myDB = ""; 
	
	//connection to the database
	$dbhandle = mssql_connect($myServer, $myUser, $myPass)
	  or die("Couldn't connect to SQL Server on $myServer"); 
	
	//select a database to work with
	$selected = mssql_select_db($myDB, $dbhandle)
	  or die("Couldn't open database $myDB"); 
	
	//declare the SQL statement that will query the database
	$query = "SELECT term_code, crn, subject_code, LEVEL, section, course_title, section_title, subject, term, department, CAST(prereqs AS TEXT) AS prereqs, CAST(first_names AS TEXT) AS first_names, CAST(middle_names AS TEXT) AS middle_names, CAST(last_names AS TEXT) AS last_names, course_description, seats_available, section_credits, course_credits, campus_code, CAST(ssrtext AS TEXT) AS ssrtext, cast(start_dates AS TEXT) as start_dates, cast(end_dates AS TEXT) as end_dates, cast(types AS TEXT) as types, cast(buildings AS TEXT) as buildings, cast(rooms AS TEXT) as rooms, cast(sundays AS TEXT) as sundays, cast(mondays AS TEXT) as mondays, cast(tuesdays AS TEXT) AS tuesdays, cast(wednesdays AS TEXT) as wednesdays, cast(thursdays AS TEXT) as thursdays, cast(fridays AS TEXT) as fridays, cast(saturdays AS TEXT) AS saturdays, cast(start_times AS TEXT) AS start_times, cast(end_times AS TEXT) AS end_times FROM dbo.test WHERE section <> 'IDW' AND CRN='".$crn."' AND term_code='".$term."'";
	
	$result = mssql_query($query);
	
?>

<div class="page">

	<style type="text/css">
	
		.scheduleHeader {
			font-weight:bold;
			background: rgba(244, 242, 237, 1.0);
			border-bottom:1px solid #ddd;
			padding: 0 10px;
		}
		
		.scheduleData {
			padding: 10px;
		}
	
	</style>

	<? include "../templates/layouts/_topshelf.php" ?>
    
    <div class="row outercontent">
    
    	<? include "../templates/layouts/_breadcrumbs.php" ?>
            
        <div class="events desktop-full tablet-full mobile-full contained">
        
        	<?
				function MapSSRTEXT($text) {
				
					$text = str_replace('T1#', '<br /><br />', $text);
					$text = str_replace('T2#', '<br /><br />', $text);
					$text = str_replace('T3#', '<br /><br />', $text);
					$text = str_replace('SN#', '', $text);
					$text = str_replace('SI#', '', $text);
					$text = str_replace('CD#', '', $text);
					
					return $text;
					
				}
				
				function MapCD($text) {
				
					$text = str_replace('<br>', '', $text);
					$text = str_replace('Registration Restrictions:','<br /><br /><strong>Registration Restrictions:</strong>', $text);
					return str_replace('Special Note:','<br /><br /><strong>Special Note:</strong>', $text);
						
				}
			
				while($row = mssql_fetch_array($result)) {
			?>
        
        	<?
				if (empty($row["section_title"]) == false) { $coursetitle = $row["section_title"];  } else { $coursetitle = $row["course_title"]; }
			
            	if (empty($row["course_title"]) == false) {
        			echo '<h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit;text-align:left;display:inline-block;margin-right:15px;">'.$coursetitle.'</h2>';
				}
			?>
            <hr style="margin-top:0px;" />
            
            <div class="row">
            
                <div class="desktop-full tablet-full mobile-full contained" style="position:relative;">
                
						<div class="content">
                            
                            <div class="details">
                            
                            	<div class="row">
                                
                                	<div class="desktop-4 tablet-2 mobile-full padded">
                            
                            			<?
                                        	$mi = "";
											if (empty($row["middle_name"]) == false) { $mi = '.'; }
											
											if (strpos($row["start_dates"],',') !== false && strpos($row["end_dates"],',') !== false) {
												
												$startdate = 'Multiple';
												$enddate = 'Multiple';
												
											} else {
												
												//No multiple results
												$startdate = date_create($row["start_dates"]);
												$startdate = date_format($startdate, 'm/d/Y');
												$enddate = date_create($row["end_dates"]);
												$enddate = date_format($enddate, 'm/d/Y');
											}
											
											if ($row["last_names"] === NULL) {
												$lastname = 'TBA';
											} else {
												$lastname = $row["last_names"];
											}
											
										?>
                            
                            			<? echo '<div class="scheduleHeader">Instructor</div><div class="scheduleData">'.$tba.str_replace(',',', ',$lastname).'</div>'; ?>
                                        <? if (empty($row["seats_available"]) == false) { echo '<div class="scheduleHeader">Seats Available</div><div class="scheduleData">'.$row["seats_available"].'</div>'; } ?>
                                        <? if (empty($row["crn"]) == false) { echo '<div class="scheduleHeader">CRN</div><div class="scheduleData">'.$row["crn"].'</div>'; } ?>
                                        <? if (empty($row["subject_code"]) == false) { echo '<div class="scheduleHeader">Subject Code/Level/Section</div><div class="scheduleData">'.$row["subject_code"]." - ".$row["LEVEL"]." - ".$row["section"].'</div>'; } ?>
                                        <? if (empty($row["subject"]) == false) { echo '<div class="scheduleHeader">Subject</div><div class="scheduleData">'.$row["subject"].'</div>'; } ?>
                                        <? if (empty($row["term"]) == false) { echo '<div class="scheduleHeader">Term</div><div class="scheduleData">'.$row["term"].'</div>'; } ?>
                                        <? if (empty($row["course_credits"]) == false) { echo '<div class="scheduleHeader">Course Credits</div><div class="scheduleData">'.$row["course_credits"].'.000</div>'; } ?>
                                        
                                        <? $content = file_get_contents("http://uaonline.alaska.edu/banprod/owa/bwckschd.p_disp_detail_sched?term_in=" . $term . "&crn_in=" . $crn . ""); ?>
                                    
										<style type="text/css">
										
											.scheduleHeader { margin-bottom:10px; }
										
											.scheduleHeader,
											.scheduleData { font-size:15px; }
                                        
                                            table.datadisplaytable {
												display:table;
												width:auto;
												font-size:15px;
											}
											
											table.datadisplaytable th {
                                                background:none;
                                            }
											
											table.datadisplaytable th,
											table.datadisplaytable td {
												width:auto;
												display:table-cell;	
											}
											
											table.datadisplaytable tr {
												width:auto;
												display:table-row;
											}
                                        
                                            caption.captiontext {
                                                display:none;
                                            }
											
											ul.meetings { display:block; list-style:none; margin:0;padding:0 10px 0 10px; }
											ul.meetings li { display:inline-block; list-style:none; margin:0; padding:0 15px 10px 0; }
											ul.meetings li:before {
												content: "";
											}
                                        
                                        </style>
                                        
                                        <?
                                            $content = strstr($content, '<TABLE  CLASS="datadisplaytable" SUMMARY="This layout table is used to present the fee amounts and descriptions.');
                                        
                                            if (empty($content) == false) {
                                                
                                                echo '<div class="scheduleHeader">Base Fees (Other charges may apply)</div>';
                                                echo '<div class="margin_s"></div>';
                                                echo substr($content, '0', strpos($content,'<BR>'));
												echo '<div class="margin_s"></div>';
                                            }
                                        ?>
                                    
                                    </div>
                                    
                                    <div class="desktop-8 tablet-4 mobile-full padded">
                                    
                                    	<?
											function GetTypeHeading($type) {
												
												switch ($type) {
													case "ARR":
														return "Arranges";
													case "AUD":
														return "Audio-Conference";
													case "CLAS":
														return "Class Meeting";
													case "FLD":
														return "Field";
													case "LAB":
														return "Laboratory Meetings";
													case "OFF":
														return "Off Campus";
													case "PRIN":
														return "Practicum or Internship";
													case "REC":
														return "Recitation";
													case "SEM":
														return "Seminar";
													case "STU":
														return "Studio";
													case "VID":
														return "Video-Conference";
													case "WEB":
														return "Web-Based";
													case "WORK":
														return "Workplace";
													default:
														return $type;
												}
												
											}
										
                                        	
												
												$types = explode(',', $row["types"]);
												
												sort($types);
												$buildings = explode(',', $row["buildings"]);
												$sundays = explode(',',$row["sundays"]);
												$mondays = explode(',',$row["mondays"]);
												$tuesdays = explode(',',$row["tuesdays"]);
												$wednesdays = explode(',',$row["wednesdays"]);
												$thursdays = explode(',',$row["thursdays"]);
												$fridays = explode(',',$row["fridays"]);
												$saturdays = explode(',',$row["saturdays"]);
												$start_dates = explode(',',$row["start_dates"]);
												$end_dates = explode(',',$row["end_dates"]);
												$start_times = explode(',',$row["start_times"]);
												$end_times = explode(',',$row["end_times"]);
												$rooms = explode(',', $row["rooms"]);
												$prereqs = explode(',', $row["prereqs"]);
												
												$distinct = count(array_unique($types));
												
												$unique = '';
												foreach (array_unique($types) as $t) {
													$unique .= $t;	
												}
												
												$count = count($types);
												$flag = false;
												$used = '';
												
												function ParseDays($d) {
													if ($d == '0') {
														return '';	
													} else {
														return $d.' ';
													}
												}
												
												$val = "";
												$val .= "<table id=\"meetings\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
												
												for ($i=0; $i<$count; $i++) {
													
													if (strpos($unique,$types[$i]) !== false) {
														
														$val .= '<tr><th colspan="5">'.GetTypeHeading($types[$i]).'</th></tr>';
														$val .= "<tr><td>Building</td><td>Room</td><td>Date</td><td>Time</td><td>Day</td></tr>";
														$unique = str_replace($types[$i],'',$unique);
													}
													
													$val .= '<tr>';
													
													//echo '<li>'.($buildings[$i] == '0' ? 'n/a': $buildings[$i]).'</li>';
													//echo '<ul class="meetings">';
													
													$val .= '<td>'.($buildings[$i] == '0' ? 'n/a': $buildings[$i]).'</td>';
													$val .= '<td>'.($rooms[$i] == '0' ? 'n/a': $rooms[$i]).'</td>';
													
													if (empty($start_dates[$i]) == false) {
														$s = date('n/j', strtotime($start_dates[$i]));
													} else {
														$s = '&mdash;';
													}
													
													if (empty($end_dates[$i]) == false) {
														$e = date('n/j', strtotime($end_dates[$i]));
													} else {
														$e = '&mdash;';
													}
													
													if ($start_times[$i] != '0') {
														$start = date('g:ia', strtotime($start_times[$i]));
													} else {
														$start = '&mdash;';
													}
													
													if ($end_times[$i] != '0') {
														$end = date('g:ia', strtotime($end_times[$i]));
													} else {
														$end = '&mdash;';
													}
													
													if ($s == $e) {
														$e = '';	
													} else {
														$e = ' - '.$e;	
													}
													
													if ($start == $end) {
														$end = '';	
													} else {
														$end = ' - '.$end;	
													}
													
													$val .= '<td>'.$s;
													$val .= $e.'</td>';
													
													$val .= '<td>'.$start;
													$val .= $end.'</td>';
													//echo '<li>'.str_replace('0','',$sunday[$i]).' '.str_replace('0','',$monday[$i]).' '.str_replace('0','',$tuesday[$i]).' '.str_replace('0','',$wednesday[$i]).' '.str_replace('0','',$thursday[$i]).' '.str_replace('0','',$friday[$i]).' '.str_replace('0','',$saturday[$i]).'</li>';
													
													if (empty($sundays[$i]) == false || empty($mondays[$i]) == false || empty($tuesdays[$i]) == false || empty($wednesdays[$i]) == false || empty($thursdays[$i]) == false || empty($fridays[$i]) == false || empty($saturdays[$i]) == false) {
													
														$val .= '<td>'.ParseDays($sundays[$i]);
														$val .= ParseDays($mondays[$i]);
														$val .= ParseDays($tuesdays[$i]);
														$val .= ParseDays($wednesdays[$i]);
														$val .= ParseDays($thursdays[$i]);
														$val .= ParseDays($fridays[$i]);
														$val .= ParseDays($saturdays[$i]).'</td>';
													
													} else {
														$val .= '<td>&mdash;</td>';
													}
													
												}
												
												$val .= '</tr></table><div class="margin_s"></div>';
												echo $val;
                                        
											
										?>
                                        
                                        
                                        <? if (empty($row["prereqs"]) == false) {
											echo '<div class="scheduleHeader">Prerequisites</div><div class="scheduleData">';
											
											foreach ($prereqs as $p) {
												echo $p . '<br />';
											}
											echo '</div><div class="margin_s"></div>';
											
											}
										?>       
										<? if (empty($row["course_description"]) == false) { echo '<div class="scheduleHeader">Course Description</div><div class="scheduleData">'.MapCD($row["course_description"]).'</div><br />'; } ?>
                                        <? if (empty($row["ssrtext"]) == false) { echo '<div class="scheduleHeader">Additional Information</div><div class="scheduleData">'.MapSSRTEXT($row["ssrtext"]).'</div><br />'; } ?>
                                            
                                        
                                    
                                    </div>
                                
                                </div>
                                
                            </div>
                            
                        </div>
                
                </div>
            </div>
            
			<?	
                }
            ?>
            
        </div>
        
    </div>

</div>