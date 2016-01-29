<?	
	$start_term = '201601';
	$end_term = '201602';
	
	$pageTitle = "";

	function ms_escape_string($data) {
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "", $data );
        return $data;
    }
	
	if('GET' == $_SERVER['REQUEST_METHOD']) {
		if (!empty($_GET['text'])) { $freetext = ms_escape_string($_GET["text"]); } else { $freetext = ''; }
		if (!empty($_GET['subject'])) { $subject = $_GET["subject"]; } else { $subject = ''; }
		if (!empty($_GET["location"])) { $location = $_GET["location"]; } else { $location = ''; }
		if (!empty($_GET["semester"])) { $semester = $_GET["semester"]; } else { $semester = ''; }
	}
	
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
	$query = "SELECT REPLACE(course_title, '*','') as sort_title, REPLACE(section_title, '*','') as section_sort_title, term_code, crn, subject_code, LEVEL, section, course_title, section_title, subject, term, department, cast(first_names AS TEXT) AS first_names, cast(middle_names AS TEXT) AS middle_names, cast(last_names AS TEXT) AS last_names, course_description, seats_available, section_credits, course_credits, campus_code, CAST(ssrtext AS TEXT) AS ssrtext, cast(start_dates AS TEXT) as start_dates, cast(end_dates AS TEXT) as end_dates, cast(types AS TEXT) as types, cast(buildings AS TEXT) as buildings, cast(rooms AS TEXT) as rooms, cast(sundays AS TEXT) as sundays, cast(mondays AS TEXT) as mondays, cast(tuesdays AS TEXT) AS tuesdays, cast(wednesdays AS TEXT) as wednesdays, cast(thursdays AS TEXT) as thursdays, cast(fridays AS TEXT) as fridays, cast(saturdays AS TEXT) AS saturdays, cast(start_times AS TEXT) AS start_times, cast(end_times AS TEXT) AS end_times FROM dbo.test WHERE section <> 'IDW' AND cast(term_code as int) >= $start_term AND cast(term_code as int) <= $end_term";
	
	if ($freetext != '') {
		$query .= " AND (";
		$query .= "term_code LIKE '%". $freetext ."%' OR";
		$query .= " crn LIKE '%". $freetext ."%' OR";
		$query .= " subject_code LIKE '%". $freetext ."%' OR";
		$query .= " LEVEL LIKE '%". $freetext ."%' OR";
		$query .= " section LIKE '%". $freetext ."%' OR";
		$query .= " course_title LIKE '%". $freetext ."%' OR";
		$query .= " section_title LIKE '%". $freetext ."%' OR";
		$query .= " subject LIKE '%". $freetext ."%' OR";
		$query .= " term LIKE '%". $freetext ."%' OR";
		$query .= " department LIKE '%". $freetext ."%' OR";
		$query .= " first_names LIKE '%". $freetext ."%' OR";
		$query .= " middle_names LIKE '%". $freetext ."%' OR";
		$query .= " last_names LIKE '%". $freetext ."%' OR";
		$query .= " course_description LIKE '%". $freetext ."%' OR";
		$query .= " ssrtext LIKE '%". $freetext ."%')";
	}
	
	if ($subject != '') {
		$query .= " AND subject_code = '".$subject."'";
	}
	
	//I, A, R, S
	
	if ($location != '') {
		
		if ($location == 'Online') {
			$query .= " AND (section LIKE '%W%' OR section LIKE '%EL%')";
		} elseif ($location == 'Blended') {
			$query .= " AND (section LIKE '%BL%' OR section LIKE '%IB%')";
		} elseif ($location == 'Video Conferenced') {
			$query .= " AND section LIKE '%V%'";
		} elseif (strpos($location, 'I') !== false) {
			//Kenai River Campus
			if (strpos($location, 'W') !== false) {
				$query .= " AND section LIKE '%IW%'";
			} elseif (strpos($location, 'EL') == true) {
				$query .= " AND section = 'IEL'";
			} else {
				$query .= " AND (section LIKE '%I%' AND section NOT LIKE '%IW%' AND section NOT LIKE '%IEL%' AND section NOT LIKE '%IBL%')";
			}
		} elseif (strpos($location, 'A') !== false) {
			//Anchorage Extension Site
			if (strpos($location, 'W') !== false) {
				$query .= " AND section LIKE '%AW%'";
			} elseif (strpos($location, 'EL') !== false) {
				$query .= " AND section = 'AEL'";
			} else {
				$query .= " AND (section LIKE '%A%' AND section NOT LIKE '%AW%' AND section NOT LIKE '%IEL%' AND section NOT LIKE '%ABL%')";
			}
		} elseif (strpos($location, 'R') !== false) {
			//Kachemak Bay Campus
			if (strpos($location, 'W') !== false) {
				$query .= " AND section LIKE '%RW%'";
			} elseif (strpos($location, 'EL') !== false) {
				$query .= " AND section = 'REL'";
			} else {
				$query .= " AND (section LIKE '%R%' AND section NOT LIKE '%RW%' AND section NOT LIKE '%REL%' AND section NOT LIKE '%RBL%')";
			}
		} elseif (strpos($location, 'S') !== false) {
			//Resurrection Bay Extension Site
			if (strpos($location, 'W') !== false) {
				$query .= " AND section LIKE '%SW%'";
			} elseif (strpos($location, 'EL') !== false) {
				$query .= " AND section = 'SEL'";
			} else {
				$query .= " AND (section LIKE '%S%' AND section NOT LIKE '%SW%' AND section NOT LIKE '%SEL%' AND section NOT LIKE '%SBL%')";
			}
		}
	}
	
	if ($semester != '') {
		$query .= " AND term_code = '".$semester."'";
	}
	
	$query .= " ORDER BY term_code DESC, subject_code, level, section, section_sort_title, sort_title";
	
	//execute the SQL query and return records
	$result = mssql_query($query);
	
?>

<div class="page">

	<style type="text/css">
	
		.schedule #results table {
			width:100%;	
		}
	
		.schedule #results table.dateinfo td {
			border-bottom:0px solid #fff;
		}
	</style>

	<? include "../templates/layouts/_topshelf.php" ?>
    
    <div class="row outercontent">
    
    	<style type="text/css">
			.selectbox { display:inline-block;border: 1px solid rgb(204, 204, 204);height:30px;margin:0px;padding:0;vertical-align:top; }
			.selectbox select { width:100%; background:transparent;padding:5px; line-height:1;border: 0px;border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;height:30px;font-family: proxima-nova, sans-serif; font-size: 14px; }
			.selectbox select .option { font-weight:bold; }
			.dirsearchbox { width:100%; }
			
			.schedule #results {
				width:100%;
			}
			
			.schedule #results th {
				font-weight:bold;
				padding:5px;
				background: rgba(244, 242, 237, 1.0);
				border-bottom:1px solid #ddd;
				text-align:center;
			}
			
			.schedule #results tr {
				border-top:1px solid #ddd;	
			}
			
			.schedule #results td {
				padding:5px;
				/*border-bottom:1px solid #ddd;*/
				font-size:13px;
				text-align:center;
			}
			
			.schedule #results tr:hover { /*background: rgba(244, 242, 237, 0.35);*/ }
			
			.error { background:rgba(255,0,0,.2); border:1px solid rgba(255,0,0,.2); border-radius:5px; padding:20px; font-size:14px; margin-bottom:35px; text-align:center; }
		</style>
    
    	<? include "../templates/layouts/_breadcrumbs.php" ?>
        
		<div class="row content">
        	
            <form action ="" method="get" class="dirsearchform" name="searchform" id="searchform" style="vertical-align:auto;">
                
                <div class="desktop-4 tablet-2 mobile-full padded">
                	<input type="text" placeholder="Search (Subject, Instructor, Title, CRN)" name="text" class="dirsearchbox" value="<?= (isset($freetext))?$freetext:'';?>" style="display:inline-block;" />
                </div>
                
                <div class="desktop-2 tablet-1 mobile-full padded">
                    <span class="selectbox">
                    <select name="subject" id="sub">
                        <option selected="selected" value="">Subject</option>
                        <?
                            $query_subj = "SELECT DISTINCT subject_code, subject FROM dbo.test";
                            $subjects = mssql_query($query_subj);
                            
                            while($row = mssql_fetch_array($subjects))
                            {
                                if ($subject == $row["subject_code"]) {
                                    echo '<option class="option" selected value="'.$row["subject_code"].'">'.$row["subject_code"] .' - '.$row["subject"].'</option>';
                                } else {
                                    echo '<option class="option" value="'.$row["subject_code"].'">'.$row["subject_code"] .' - '.$row["subject"].'</option>';
                                }
                            }
                        ?>
                    </select>
                    </span>
                </div>
                
                <div class="desktop-2 tablet-1 mobile-full padded">
                    <span class="selectbox">
                    <select name="location" id="loc">
                        <option selected="selected" value="">Delivery</option>
                        <?
                            $query_loc = "SELECT DISTINCT LEFT(section, 3) AS section FROM dbo.test WHERE section <> 'IDW'";
                            $locations = mssql_query($query_loc);
                            
                            $locarray = array();
                            
                            while($row = mssql_fetch_array($locations))
                            {
                                $locarray[] = $row["section"];
                            }
                            
                            $cleanloc = array();
                            
                            
                            foreach ($locarray as $loc) {
                                
                                switch ($loc) {
                                    case strpos($loc, 'W') == true:
                                        $cleanloc[] = 'Online';
                                        break;
                                    case strpos($loc, 'EL') == true:
                                        $cleanloc[] = 'Online';
                                        break;
									case strpos($loc, 'E1') == true:
                                        $cleanloc[] = 'Online';
                                        break;
									case strpos($loc, 'E2') == true:
                                        $cleanloc[] = 'Online';
                                        break;
									case strpos($loc, 'TP') == true:
                                        $cleanloc[] = 'Online';
                                        break;
                                    case strpos($loc, 'BL') == true:
                                        $cleanloc[] = 'Blended';
                                        break;
									case strpos($loc, 'B') == true:
                                        $cleaned = 'Blended';
                                        break;
									case strpos($loc, 'V') == true:
                                        $cleanloc[] = 'Video Conferenced';
                                        break;
                                    default:
                                        $cleanloc[] = trim(str_replace(range(0,9),'',$loc));
                                }
                                
                            }
                            
                            sort($cleanloc);
                            
                            $clean = array();
                            
                            foreach (array_unique($cleanloc) as $c) {
                                
                                if ($location == $c) {
                                    echo '<option selected value="'.$c.'">'.ParseCampus($c).'</option>';
                                } else {
                                    echo '<option value="'.$c.'">'.ParseCampus($c).'</option>';
                                }
                            }
                            
                            function ParseCampus($campus) {
                                switch ($campus) {
                                    case 'I':
                                        return 'Kenai River Campus';
                                        break;
                                    case 'A':
                                        return 'Anchorage Extension Site';
                                        break;
                                    case 'R':
                                        return 'Kachemak Bay Campus';
                                        break;
                                    case 'S':
                                        return 'Resurrection Bay Extension Site';
                                        break;
                                    case 'Online':
                                        return 'Online';
                                        break;
                                    case 'Blended':
                                        return 'Blended';
                                        break;
									case 'Video Conferenced';
										return 'Video Conferenced';
                                    default:
                                        return $campus.':Undefined';
                                }
                            }
							
							$cleaned = "";
							
							function ParseLocation($loc) {
								
								switch ($loc) {
                                    case strpos($loc, 'W') == true:
                                        $cleaned = 'Online';
                                        break;
                                    case strpos($loc, 'EL') == true:
                                        $cleaned = 'Online';
                                        break;
									case strpos($loc, 'E1') == true:
                                        $cleaned = 'Online';
                                        break;
									case strpos($loc, 'E2') == true:
                                        $cleaned = 'Online';
                                        break;
									case strpos($loc, 'TP') == true:
                                        $cleaned = 'Online';
                                        break;
                                    case strpos($loc, 'BL') == true:
                                        $cleaned = 'Blended';
                                        break;
									case strpos($loc, 'B') == true:
                                        $cleaned = 'Blended';
                                        break;
									case strpos($loc, 'V') == true:
                                        $cleaned = 'Video Conferenced';
                                        break;
                                    default:
                                        $cleaned = trim(str_replace(range(0,9),'',$loc));
                                }
								
								switch ($cleaned) {
                                    case 'I':
                                        return 'Kenai';
                                        break;
                                    case 'A':
                                        return 'Anchorage';
                                        break;
                                    case 'R':
                                        return 'Homer';
                                        break;
                                    case 'S':
                                        return 'Seward';
                                        break;
                                    case 'Online':
                                        return 'Online';
                                        break;
                                    case 'Blended':
                                        return 'Blended';
                                        break;
									case 'Video Conferenced':
                                        return 'Video';
                                        break;
                                    default:
                                        return $cleaned.':Undefined';
                                }
							}
                        ?>
                    </select>
                    </span>
                </div>
                
                <div class="desktop-2 tablet-1 mobile-full padded">
                
                    <span class="selectbox" style="width:100%;">
                    <select name="semester" id="sem" style="width:100%;">
                        <?
                            $query_term = "SELECT DISTINCT term_code FROM dbo.test WHERE cast(term_code as int) >= $start_term AND cast(term_code as int) <= $end_term ORDER BY term_code DESC";
                            $terms = mssql_query($query_term);
                            
                            while($row = mssql_fetch_array($terms))
                            {
								if ($semester == "") {
									if ($row["term_code"] !== $start_term && $row["term_code"] !== $end_term) {
										echo '<option selected value="'.$row["term_code"].'">'.ParseSemester($row["term_code"]).'</option>';
									} else {
										echo '<option value="'.$row["term_code"].'">'.ParseSemester($row["term_code"]).'</option>';
									}
								} else {
								
									if ($semester == $row["term_code"]) {
										echo '<option selected value="'.$row["term_code"].'">'.ParseSemester($row["term_code"]).'</option>';	
									} else {
										echo '<option value="'.$row["term_code"].'">'.ParseSemester($row["term_code"]).'</option>';	
									}
								}
                            }
                            
                            function ParseSemester($term) {
                                
                                $semester = substr($term, -2);
                                $year = substr($term, 0, -2);
                                
                                switch ($semester) {
                                    case '1':
                                        $semester = 'Spring';
                                        break;
                                    case '2':
                                        $semester = 'Summer';
                                        break;
                                    case '3':
                                        $semester = 'Fall';
                                        break;	
                                }
                                 
                                return $semester.' '.$year;
                                
                            }
                            
                        ?>
                    </select>
                    </span>
                    <div style="clear:both;"></div>
                
                </div>
                
                <div class="desktop-2 tablet-1 mobile-full contained">
                
                	<div class="mobileshow" style="margin-top:15px;"></div>
                    
                    <input type="submit" class="submit dirsearchbutton search" />
                    <input type="reset" class="submit dirsearchbutton" Value="Clear" onclick="ClearForm();" style="background-image:none;text-indent:0px;color:#fff;font-weight:normal;" />
                
                </div>
                
            </form>
            
            <div class="clear"></div>
        
        </div>
            
        <div class="schedule desktop-full tablet-full mobile-full contained">
        	
            
            <?
			
				$val = "";
			
				if ($freetext || $subject || $location || $semester) {
					
					
					function ParseDays($d) {
								
						if ($d == '0') {
							return '';	
						} else {
							return $d.' ';
						}
					}
					
					function ParseZero($z) {
					
						if ($z == '0') {
							return '&mdash;';
						} else {
							return $z;	
						}
						
					}
					
					$val .= "<table id=\"results\" class=\"schedule\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\">";
					$val .= "<tr><th>Title</th><th class=\"mobilehide\">CRN</th><th class=\"mobilehide\">Code/Level/Section</th><th class=\"mobilehide\">Semester</th><th class=\"mobilehide\">Location</th><th class=\"mobilehide\">Bldg</th><th class=\"mobilehide\">Rm</th><th class=\"mobilehide\">Day</th><th class=\"mobilehide\">Date</th><th class=\"mobilehide\">Time</th><th class=\"mobilehide\">Instructor</th><th>Seats</th></tr>";
					$coursetitle = "";
					
					while($row = mssql_fetch_array($result))
					{
						$buildings = explode(',',$row["buildings"]);
						$rooms = explode(',',$row["rooms"]);
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
						
						
						$count = count(explode(',',$row["sundays"]));
						$i = 0;
						
						for ($i == 0; $i < $count; $i++)
						{
							if (empty($row["section_title"]) == false) { $coursetitle = $row["section_title"];  } else { $coursetitle = $row["course_title"]; }
							
							if ($i == 0) {
								
								$val .= "<tr class=\"".$row["crn"]."\"><td class=\"scheduletitle\"><a href=\"/academics/schedule/detail/?crn=".$row["crn"]."&term=".$row["term_code"]."\">".$coursetitle."</a></td><td class=\"mobilehide\">".$row["crn"]."</td><td class=\"mobilehide\">".$row["subject_code"]." ".$row["LEVEL"]." ".$row["section"]."</td><td class=\"mobilehide\">".str_replace(' Semester','',ParseSemester($row["term_code"]))."</td><td class=\"mobilehide\">".ParseLocation($row["section"])."</td>";
						
								//$val .= '<tr>';
								$val .= '<td class="mobilehide">'.ParseZero($buildings[$i]).'</td>';
								$val .= '<td class="mobilehide">'.ParseZero($rooms[$i]).'</td>';
								
								if (empty($sundays[$i]) == false || empty($mondays[$i]) == false || empty($tuesdays[$i]) == false || empty($wednesdays[$i]) == false || empty($thursdays[$i]) == false || empty($fridays[$i]) == false || empty($saturdays[$i]) == false) {
								
									$val .= '<td class="mobilehide">'.ParseDays($sundays[$i]);
									$val .= ParseDays($mondays[$i]);
									$val .= ParseDays($tuesdays[$i]);
									$val .= ParseDays($wednesdays[$i]);
									$val .= ParseDays($thursdays[$i]);
									$val .= ParseDays($fridays[$i]);
									$val .= ParseDays($saturdays[$i]).'</td>';
								
								} else {
									$val .= '<td class="mobilehide">&mdash;</td>';
								}
								
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
								
								$val .= '<td class="mobilehide">'.$s;
								$val .= $e.'</td>';
								
								$val .= '<td class="mobilehide">'.$start;
								$val .= $end.'</td>';
								
								if ($row["last_names"] === NULL) {
									$lastname = 'TBA';	
								} else {
									
									$lastname = str_replace(',',', ',$row['last_names']);
								}
								
								$val .= "<td class=\"mobilehide\">".$lastname."</td><td>".$row["seats_available"]."</td></tr>";
								
							} else {
							
								$val .= "<tr class=\"".$row["crn"]."\" style=\"border:none;\" class=\"mobilehide\"><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td>";
								
								$val .= '<td class="mobilehide">'.ParseZero($buildings[$i]).'</td>';
								$val .= '<td class="mobilehide">'.ParseZero($rooms[$i]).'</td>';
								
								if (empty($sundays[$i]) == false || empty($mondays[$i]) == false || empty($tuesdays[$i]) == false || empty($wednesdays[$i]) == false || empty($thursdays[$i]) == false || empty($fridays[$i]) == false || empty($saturdays[$i]) == false) {
								
									$val .= '<td class="mobilehide">'.ParseDays($sundays[$i]);
									$val .= ParseDays($mondays[$i]);
									$val .= ParseDays($tuesdays[$i]);
									$val .= ParseDays($wednesdays[$i]);
									$val .= ParseDays($thursdays[$i]);
									$val .= ParseDays($fridays[$i]);
									$val .= ParseDays($saturdays[$i]).'</td>';
								
								} else {
									$val .= '<td class="mobilehide">&mdash;</td>';
								}
								
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
								
								$val .= '<td class="mobilehide">'.$s;
								$val .= $e.'</td>';
								
								$val .= '<td class="mobilehide">'.$start;
								$val .= $end.'</td>';
								
								$val .= "<td class=\"mobilehide\"></td><td class=\"mobilehide\"></td></tr>";
								
							}
						}
					}
					
					while($row = mssql_fetch_array($result))
					{
						$buildings = explode(',',$row["buildings"]);
						$rooms = explode(',',$row["rooms"]);
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
						
						$count = count(explode(',',$row["sundays"]));
						$i = 0;
						
						for ($i == 0; $i < $count; $i++)
						{
							if (empty($row["section_title"]) == false) { $coursetitle = $row["section_title"];  } else { $coursetitle = $row["course_title"]; }
							
							if ($i == 0) {
								
								$val .= "<tr class=\"".$row["crn"]."\"><td class=\"scheduletitle\"><a href=\"/academics/schedule/detail/?crn=".$row["crn"]."&term=".$row["term_code"]."\">".$coursetitle."</a></td><td class=\"mobilehide\">".$row["crn"]."</td><td class=\"mobilehide\">".$row["subject_code"]." ".$row["LEVEL"]." ".$row["section"]."</td><td class=\"mobilehide\">".str_replace(' Semester','',ParseSemester($row["term_code"]))."</td><td class=\"mobilehide\">".ParseLocation($row["section"])."</td>";
						
								//$val .= '<tr>';
								$val .= '<td class="mobilehide">'.ParseZero($buildings[$i]).'</td>';
								$val .= '<td class="mobilehide">'.ParseZero($rooms[$i]).'</td>';
								
								if (empty($sundays[$i]) == false || empty($mondays[$i]) == false || empty($tuesdays[$i]) == false || empty($wednesdays[$i]) == false || empty($thursdays[$i]) == false || empty($fridays[$i]) == false || empty($saturdays[$i]) == false) {
								
									$val .= '<td class="mobilehide">'.ParseDays($sundays[$i]);
									$val .= ParseDays($mondays[$i]);
									$val .= ParseDays($tuesdays[$i]);
									$val .= ParseDays($wednesdays[$i]);
									$val .= ParseDays($thursdays[$i]);
									$val .= ParseDays($fridays[$i]);
									$val .= ParseDays($saturdays[$i]).'</td>';
								
								} else {
									$val .= '<td class="mobilehide">&mdash;</td>';
								}
								
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
								
								$val .= '<td class="mobilehide">'.$s;
								$val .= $e.'</td>';
								
								$val .= '<td class="mobilehide">'.$start;
								$val .= $end.'</td>';
								
								$val .= "<td class=\"mobilehide\">".$row["last_name"]."</td><td class=\"mobilehide\">".$row["seats_available"]."</td></tr>";
								
							} else {
							
								$val .= "<tr class=\"".$row["crn"]."\" style=\"border:none;\"><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td><td class=\"mobilehide\"></td>";
								
								$val .= '<td class="mobilehide">'.ParseZero($buildings[$i]).'</td>';
								$val .= '<td class="mobilehide">'.ParseZero($rooms[$i]).'</td>';
								
								if (empty($sundays[$i]) == false || empty($mondays[$i]) == false || empty($tuesdays[$i]) == false || empty($wednesdays[$i]) == false || empty($thursdays[$i]) == false || empty($fridays[$i]) == false || empty($saturdays[$i]) == false) {
								
									$val .= '<td class="mobilehide">'.ParseDays($sundays[$i]);
									$val .= ParseDays($mondays[$i]);
									$val .= ParseDays($tuesdays[$i]);
									$val .= ParseDays($wednesdays[$i]);
									$val .= ParseDays($thursdays[$i]);
									$val .= ParseDays($fridays[$i]);
									$val .= ParseDays($saturdays[$i]).'</td>';
								
								} else {
									$val .= '<td class="mobilehide">&mdash;</td>';
								}
								
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
								
								$val .= '<td class="mobilehide">'.$s;
								$val .= $e.'</td>';
								
								$val .= '<td class="mobilehide">'.$start;
								$val .= $end.'</td>';
								
								$val .= "<td class=\"mobilehide\"></td><td class=\"mobilehide\"></td></tr>";
								
							}
						}
					}
					
					//close the connection
					mssql_close($dbhandle);
					
					$val.= "</table>";
					
					echo $val;
					
				} else {
					echo '<div class="content" style="padding-bottom:0;"><h2>Welcome to KPC\'s Searchable Schedule</h2>
					<p>This schedule has been designed for student convenience. In order to REGISTER, students must access UAOnline. TAKE CARE to compare all information seen here to that on UAOnline—the definitive source for course information. AFTER registering, double check the list of courses to be certain the resulting schedule reads as expected.</p><p>Students need to be careful to note WHERE (which campus or extension site) COURSES ORIGINATE FROM. Students can search course offerings from all KPC locations with KPC\'s Searchable Schedule. </p>
					<p>The searchable schedule is driven by a database that is refreshed every 24 hours making it the most current and accurate resource for students. This feature is invaluable for ensuring that students can put together a schedule that meets their unique needs.</p>
					<p>The advanced search option allows a more detailed query when searching for course options. Draft schedule information for planning upcoming semesters will be posted as soon as it becomes available. Draft schedules are subject to change and students are encouraged to check the draft often for changes.</p></div>';
				}
				
				
			?>
            
            <hr />
    
    		<div style="text-align:center;font-size:13px;font-weight:bold;">* Course titles that start with an asterisk satisfy a General Education Requirement (GER) for UAA.</div>
            
        </div>
        
    </div>
    
    <div class="clear"></div>
    
    <div class="margin_m white"></div>

</div>

<script type="text/javascript">
	function ClearForm() {
		window.location = window.location.pathname;
	}
	
</script>