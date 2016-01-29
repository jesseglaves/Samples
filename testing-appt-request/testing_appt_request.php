<?


						
	$page = $bigtree["page"];
	if ($page["in_nav"] == "on") {
		$mode = "9";	
	} else {
		$mode = "full";	
	}
	
	$servername = "";
	$username = "";
	$password = "";
	$dbname = "";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$valid_dates = array();
	$valid_datesm = array();
	$start_times = array();
	$end_times  = array();
	$unavailable  = array();
	
	$sql = "SELECT DATE_FORMAT(p_date,'%e-%c-%Y') as p_datef, DATE_FORMAT(p_date,'%m/%d/%Y') as p_datem, DATE_FORMAT(start_datetime,'%l:%i%p') as start_times, DATE_FORMAT(end_datetime,'%l:%i%p') as end_times, unavailable_periods FROM LC_proctor_schedule where active_ind = 'Y' and DATE(p_date) > DATE(NOW()) order by p_date ASC";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		
		while($row = mysqli_fetch_assoc($result)) {
			 $valid_dates[] = $row["p_datef"];
			 $valid_datesm[] = $row["p_datem"]; 
			 $start_times[] = $row["start_times"];
			 $end_times[] = $row["end_times"];	
			 $unavailable[] = $row["unavailable_periods"]; 		 
		}
	} else {
		echo "0 results";
	}
	
	mysqli_close($conn); 
?>

<style type="text/css">
				
	input[type="text"]:focus, input[type="email"]:focus, input[type="checkbox"]:focus, textarea:focus, select:focus, input[type="submit"]:focus {
		box-shadow: 0 0 5px rgba(81, 203, 238, 1);
		border: 1px solid rgba(81, 203, 238, 1);
	}
	
	.content table.formTbl,
	.content table.formTbl table,
	.content table.formTbl tr,
	.content table.formTbl td,
	.content table.formTbl th {
		border-top:none;
		border-right:none;
		border-bottom:none;
		border-left:none;
		background:none;
	}
	
	.content table.formTbl td {
		padding:15px;
		vertical-align:top;
	}
	
	label.error { display:none; font-size:12px; }
	label.valid { font-size:12px; }
	
	.selectbox {
		display: inline-block;
		border: 1px solid rgb(204, 204, 204);
		height: 30px;
		margin: 0px;
		padding: 0;
		vertical-align: top;
		float:inherit;
	}
	
	select { border: 1px solid rgb(204, 204, 204); display:inline-block; border: 1px solid rgb(204, 204, 204); height:30px;margin:0px;padding:0;vertical-align:top; background:#fff; }
	select { background:transparent;padding:5px; line-height:1;height:30px;font-family: proxima-nova, sans-serif; font-size: 14px; }
	select .option { font-weight:bold; }
	
	input,
	textarea
	{
		border: 1px solid rgb(204, 204, 204);
		display: inline-block;
		font-family: proxima-nova, sans-serif;
		font-size: 14px;
		font-weight: bold;
		line-height:32px;
		margin:0;
		padding:0 15px;
	}
	
	.momentum {
		border:1px solid #ccc;
		padding:20px;
		display:none;
	}
	
	
										
	fieldset p { margin:0; padding:0; }
	fieldset .phonelabels label { display:inline-block; width:auto; }
	
	.error { background:rgba(255,0,0,.2); border:1px solid rgba(255,0,0,.2); }
	label.error { display:inline-block; color:red; background:none; border:none; padding-left:15px; margin-left:8px; background:transparent url(/images/alert.png) no-repeat 0 3px; }
	
	.selectbox.error select { border: 1px solid rgba(255,0,0,.2); }
	
	.valid { background:rgba(0,255,0,.2); border:1px solid rgba(0,255,0,.2); }
						
	#appointment .row { font-size:16px; padding:10px; }
	#appointment .phone_3 { float: left; margin: 0 10px 0 0; }
	#appointment .phone_4 { float: left; }
	#appointment .centered { text-align: center; }
	#appointment .sublabel { font-size: 12px; padding: 5px 0 0 0; margin: 0; color: #444; }
	#appointment .star { font-size: 16px; padding: 5px 0 0 0; margin: 7px; color: red; font-weight:bold; }

</style>

<link rel="stylesheet" href="http://www.kpc.alaska.edu/css/btx-form-builder.css" />

<script type="text/javascript" src="/js/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="/js/lib/jquery.validate.js"></script>
<script type="text/javascript" src="/js/lib/jquery.validate.additional-methods.min.js"></script>
<script type="text/javascript" src="/js/lib/jquery-timepicker/jquery.timepicker.js"></script>
<link rel="stylesheet" type="text/css" href="/js/lib/jquery-timepicker/jquery.timepicker.css" />
<script type="text/javascript" src="/js/lib/jquery-timepicker/lib/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="/js/lib/jquery-timepicker/lib/bootstrap-datepicker.css" />
<script type="text/javascript" src="/js/lib/jquery-timepicker/lib/site.js"></script>
<!--<link rel="stylesheet" type="text/css" href="/js/lib/jquery-timepicker/lib/site.css" />-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!--<link rel="stylesheet" href="/css/master.css">-->

<script type="text/javascript">
	
	var availableDates= [<? echo '"'.implode('","', $valid_dates).'"' ?>];
	var availableDatesm= [<? echo '"'.implode('","', $valid_datesm).'"' ?>];
	var availableStart= [<? echo '"'.implode('","', $start_times ).'"' ?>];
	var availableEnd= [<? echo '"'.implode('","', $end_times ).'"' ?>];
	var unavailable= [<? echo '"'.implode('","', $unavailable ).'"' ?>];

	var startdate = availableDatesm[0];

	function available(date) {
		dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
		console.log(dmy+' : '+($.inArray(dmy, availableDates)));
		if ($.inArray(dmy, availableDates) != -1) {
			return [true,"","Available Proctor Date"];
		} else {
			return [false,"","Unavailable Proctor Date"];
		}
	}

	var jq111 = jQuery.noConflict();
	
	function changeTime() {

		var currentDate = jq111( "#datepicker" ).val();

		var selected_date_pos = availableDatesm.indexOf(currentDate);
		var mintime = availableStart[selected_date_pos];
		var maxtime = availableEnd[selected_date_pos];
		var disabled = unavailable[selected_date_pos];
		
		//jq111('#timepicker').timepicker('remove');
		
        
		document.getElementById("timepicker").value = '';
		
		jq111('#timepicker').timepicker({
		'noneOption': [{'label': 'Please Select a Time...','value': ''}],
		'minTime': mintime,
		'maxTime': maxtime,
		'step': 15,
		'disableTimeRanges': eval(disabled),
		'disableTextInput': true,
		'useSelect': true,
		'showDuration': false});
		
		jq111("#timepicker")[0].selectedIndex = 0;
		jq111("#UAsystem")[0].selectedIndex = 0;
		jq111('#timepicker').timepicker('show');
		

	}
	
     jq111(function() {
		jq111('#timepicker').timepicker({
		'noneOption': [{'label': 'Select a Date First','value': 'aaa'}],
		'useSelect': true,
		'minTime': '0',
		'maxTime': '0'
		});
	    
	 });
	
	jq111(function() {
	 jq111( "#datepicker" ).datepicker( { beforeShowDay: available, defaultDate: startdate });
	});
	
	function checkother() {
		
		if (!grecaptcha.getResponse()){
			// alert("Google reCAPTCHA not complete");//Display error here e.g. jQuery('#targetID').html("Please check the 'I'm not a robot' checkbox!")
			jq111('#redtag').text("Please check the 'I'm not a robot' checkbox!");
			return false;//End the processing
		}
		else
		{
			jq111('#redtag').text("");
		}	
		
		// check if endtime selected and give warning
		if (jq111("#appointment").valid() ){
			
			var currentDate = jq111( "#datepicker" ).val();

			var selected_date_pos = availableDatesm.indexOf(currentDate);
			var maxtime = availableEnd[selected_date_pos];
		    var disabled = unavailable[selected_date_pos];
								
			var minutes = calculateDiff(jq111( "#timepicker" ).val().toUpperCase(), maxtime);
			
			if (minutes <= 60)
			{
				
				if ( confirm('Warning!! The time selected will only allow ' + minutes +' minutes to complete your exam within the center proctored hours for the day. Are you sure you want to proceed?') )
				{
					return true;
				}
				
				return false;
			}
		}
				
		return true;
	}
	
function checktime() {

 var currentDate = jq111( "#datepicker" ).val();

			var selected_date_pos = availableDatesm.indexOf(currentDate);
			var maxtime = availableEnd[selected_date_pos];
		    var disabled = unavailable[selected_date_pos];
								
			var minutes = calculateDiff(jq111( "#timepicker" ).val().toUpperCase(), maxtime);
			
			if (minutes <= 60)
			{
				setTimeout(function() {
					alert('Warning!! The time selected will only allow ' + minutes +' minutes to complete your exam within the center proctored hours for the day.');
				},0);
				
            }

clearmsg();
return true;
				
}
	function calculateDiff(_start, _end){
        
        _start_time = parseAMDate(_start);
        _end_time = parseAMDate(_end);

        if (_end_time < _start_time){
            _end_time = parseAMDate(_end,1);
        }

        var difference= _end_time - _start_time;

        
			var minutes =  Math.floor(difference / 1000 / 60);
        
		return (minutes+30)
    }

    function parseAMDate(input, next_day) {

        var dateReg = /(\d{1,2}):(\d{2})\s*(AM|PM)/;

        var hour, minute, result = dateReg.exec(input);

        if (result) {
            hour = +result[1];
            minute = +result[2];

            if (result[3] === 'PM' && hour !== 12) {
                hour += 12;
            }
        }
        if (!next_day) {
            return new Date(1970, 01, 01, hour, minute).getTime();
        }else{
            return new Date(1970, 01, 02, hour, minute).getTime();
        }
    }
	
	function clearmsg() {
			
		var validator = jq111( "#appointment" ).validate();
		validator.element( "#timepicker" );			
            
	}
	
	
</script>  

<div class="page">

	<? include "../templates/layouts/_topshelf.php" ?>
    
    <div class="row outercontent">
    
        <?
			if (!$is404) {
		?>
        
            <aside class="desktop-3 tablet-full mobile-full nocontent">
            <? if ($page["in_nav"] == "on") { ?>
                <? include "../templates/layouts/_sidemenu.php" ?>
            <? } ?>
            </aside>
            
            <? if (count($featured) > 0) { ?>
            
                <div class="desktop-<?=$mode?> tablet full mobile-full">
                
                    <? include "../templates/layouts/_breadcrumbs.php" ?>
                
                    <? if ($teaser !== "") { ?>
                        <blockquote><?=$teaser?></blockquote>
                    <? } ?>
                    
                    <div class="row">
                        <div class="desktop-8 tablet-full mobile-full content">
                            <? include "../templates/callouts/photo_carousel.php" ?>
                            <?=$content?>
                        </div>
                        <? include "../templates/callouts/featured.php" ?>
                    </div>
                
                </div>
    
            <? } else { ?>
            
                <div class="desktop-<?=$mode?> tablet-full mobile-full">
                    
                    <? include "../templates/layouts/_breadcrumbs.php" ?>
                    
                    <div class="clear"></div>
                    
                    <? if ($teaser) { ?>
                        <blockquote><?=$teaser?></blockquote>
                    <? } ?>
                
                    <div class="content">
					
					                    
						<? if ($header !== "") { ?>
                            <?=$header?>
                        <? } ?>
						
                        <form id="appointment" method="post" action="/forms/testing_appt_email.php" enctype="multipart/form-data" onsubmit="return checkother()" autocomplete="off">
                                                
                            <fieldset style="width:100%;padding:0;margin:0;">
								
								<span class="star">*</span> =  required
                                <br />
								<br />
                                <h2>Appointment Date/Time Desired</h2>
                                
                                
                                <strong style="line-height: 2.1;">NOTE***:</strong><span style="line-height: 2.1;"> Only available proctored dates and times are enabled below for selection. The last available appointment is always 30 minutes before closing. Please plan accordingly when selecting your start time.</span><BR>
                                
                                <BR />
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
                                        <label for="datepicker"> <strong style="line-height: 2.1;">Date <span class="star"> *</span></strong></label>
                                    </div>
                                    <div class=" desktop-9 tablet-4 mobile-full padded">
                                        <input id="datepicker" name="selecteddate" type="text" readonly placeholder="Date"  onChange="changeTime()" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" desktop-3 tablet-2 mobile-full padded">
                                        <label for="timepicker"> <strong style="line-height: 2.1;">Time <span class="star"> *</span></strong></label>
                                    </div>
                                    <div class=" desktop-5 tablet-4 mobile-full padded">
                                        <input id="timepicker" name="selectedtime" type="text" placeholder="Time"  onChange="checktime()"  value=""   />
                                    </div>
                                </div>
                                <hr />
                                 
                            
                                <h2>Student Information</h2>
                                                        
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									    <label for="firstname"> <strong style="line-height: 2.1;">First Name <span class="star"> *</span></strong></label>
                                    </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="firstname" name="firstname" type="text" placeholder="First Name" required />
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
                                        <label for="lastname"> <strong style="line-height: 2.1;">Last Name <span class="star"> *</span></strong></label>
                                    </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="lastname" name="lastname" type="text" placeholder="Last Name" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									<label for="email"> <strong style="line-height: 2.1;">Email Address</strong> (Enter your preferred email. This will be used to confirm or deny your appointment.)  <span class="star"> *</span></label>
                                     </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="email" name="email" type="text" placeholder="Email address" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
                                         <label for="phone1"> <strong style="line-height: 2.1;">Phone Number <span class="star"> *</span></strong></label>
                                    </div>
                                      <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input type="text" maxlength="12"  name="phone1" id="phone1" placeholder="###-###-####" required value="" onkeypress='return ( (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45) || (event.charCode == 32))' />
                    
                                    </div>
                                    
                                </div><div class="row"></div><br />	
                                <hr />
                                    
                                    <h2>Course and Exam Information</h2>
                                    <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
										<label for="campus"> <strong style="line-height: 2.1;">Campus</strong> (Indicate the campus the course originates from, i.e. KPC, UAF, UAA, WGU, etc.)  <span class="star"> *</span></label>
                                        
                                    </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="campus" name="campus" type="text" placeholder="Campus" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									    <label for="UAsystem"> <strong style="line-height: 2.1;">Non-UA System University?</strong> (If the campus is outside the state of Alaska, please select 'Yes.' A $30 proctor fee will be charged on test day. Please call or email to verify receipt of exam materials before your appointment.) <span class="star"> *</span></label>
                                      
                                    </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                    <select class="selectbox" style="float:left;border-radius:0px;" id="UAsystem" name="UAsystem" required>
                                        <option value="No" data-price="0">No</option>
                                        <option value="Yes" data-price="0">Yes</option>
                                    </select>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
										<label for="coursetype"> <strong style="line-height: 2.1;">Type of Course</strong> (For KPC-KRC courses, please select the correct option. For all other campuses, you must select E-Learning.) <span class="star"> *</span></label>
                                      </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                    <select class="selectbox" style="float:left;border-radius:0px;" id="coursetype" name="coursetype" required>
                                    <option value="">Select Course Type...</option>
                                    <option value="E-Learning" data-price="0">E-Learning</option>
                                    <option value="Face-to-Face" data-price="0">Face-to-Face</option>
                                    </select>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									     <label for="course"> <strong style="line-height: 2.1;">Course Name and Number</strong> (Use the format: ECON202, PRT101, MATH105, etc.) <span class="star"> *</span></label>
                                        
                                    </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="course" name="course" type="text" placeholder="Course Name and Number" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									     <label for="crn"> <strong style="line-height: 2.1;">CRN Number</strong> (Please enter the 5-digit course reference number. This can help us to verify that we have the correct exam for you.)</label>
                                    </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="crn" name="crn" type="text" size="5" maxlength="5" placeholder="CRN" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									    <label for="instructor"> <strong style="line-height: 2.1;">Instructor</strong> (Please include both first and last name.) <span class="star"> *</span></label>     
                                    </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="instructor" name="instructor" type="text" placeholder="Instructor" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									     <label for="exam"> <strong style="line-height: 2.1;">Exam Name or Number</strong> (i.e. Exam 1, Chapter 2, Midterm, etc.) <span class="star"> *</span></label> 
                                </div>
                                    <div class="desktop-9 tablet-4 mobile-full padded">
                                        <input id="exam" name="exam" type="text" placeholder="Exam Name or Number" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="desktop-3 tablet-2 mobile-full padded">
									     <label for="reschedule"> <strong style="line-height: 2.1;">Rescheduling Appointment?</strong> (If you are rescheduling an appointment, please select 'Yes.') <span class="star"> *</span></label> 
                                                                            </div>
                                    
                                <div class="desktop-9 tablet-4 mobile-full padded">
                                <select class="selectbox" style="float:left;border-radius:0px;" id="reschedule" name="reschedule" required>
                                <option value="No" data-price="0">No</option>
                                <option value="Yes" data-price="0">Yes</option>
                                </select>
                                </div>
                                </div>
                                <br />
                                
								
                                <label>
                                <h2>Security</h2>
                                </label>
                                
                                <p>Please fill in reCAPTCHA below.*</p>
                                
								<script src="https://www.google.com/recaptcha/api.js" async defer></script>
								<div class="g-recaptcha" data-sitekey="6Lfb9AQTAAAAAJ_w-7lr9MXt06YOIu2_LQKX0rrE"></div>
								<span style="color:red;font-weight:bold" id="redtag"></span><br />
                                
                                <br />
                                <div class="row">
                                    <div class="desktop-6 tablet-3 mobile-half min-full contained">
                                        <input type="submit" id="submit" value="Submit Appointment Request" />
                                    </div>
                                </div>
                                                   
                                <div class="row"></div><br />
                            </fieldset>
                                                                    
                        </form>
                
						<? if ($footer !== "") { ?>
                            <?=$footer?>
                        <? } ?>
                        
                        <div class="margin_m"></div>
                        <? include "../templates/callouts/contact_info.php" ?>
                    </div>
                </div>
            <? } ?>
        <? } ?>
	</div>
</div>
	
<script type="text/javascript">

    var jq111 = jQuery.noConflict();
	
	//overwrite jquery validator to allow readonly field to be 'required' fields. 
	
	jq111.validator.prototype.elements = function() {
	var validator = this,
	rulesCache = {};
	this.settings.ignore = "";
	return jq111( this.currentForm )
	.find( "input, select, textarea" )
	.not( ":submit, :reset, :image") // changed from: .not( ":submit, :reset, :image, [disabled], [readonly]" )
	.not( this.settings.ignore )
	.filter( function() {
		if ( !this.name && validator.settings.debug && window.console ) {
			console.error( "%o has no name assigned", this );
		}

		if ( this.name in rulesCache || !validator.objectLength( jq111( this ).rules() ) ) {
			return false;
		}
		
		
		rulesCache[ this.name ] = true;
		return true;
	});         
	};
	
	jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 9 && 
    phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
}, "Please specify a valid phone number");

	jq111.validator.addMethod("Check",function (value,element){
		   
		   if (document.getElementById("timepicker").value == '')
		  {
			    document.getElementById("firstname").focus();
				return false;
		 }
		   return true;
		   

        }, 'Please Select a Time');
		
	
	
	var form = jq111( "#appointment" );
	
	
	form.validate({	
	 onfocusout: false,
    invalidHandler: function(form, validator) {
        var errors = validator.numberOfInvalids();
        if (errors) {                    
            validator.errorList[0].element.focus();
        }
	},
	rules: {
	ignore: [],
    email: {
      required: true,
      email: true
    },
	phone1: {
      required: true,
      phoneUS: true
    },
	selectedtime:{
	  Check: true	   
	}
	} });
	

	
</script>



