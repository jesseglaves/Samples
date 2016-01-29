<?
	$cms->makeSecure();
	$mode = $_GET["a"];
	
?>

<script type="text/javascript" src="/js/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="/js/lib/jquery.validate.js"></script>
<script type="text/javascript" src="/js/lib/jquery.validate.additional-methods.min.js"></script>

<script type="text/javascript">

	$(document).ready(function() {
		
		$( "#submit" ).click(function() {
			
		});
		
		$('#firstname').focus();
		
		$.fn.ForceNumericOnly = function() {
			return this.each(function() {
				$(this).keydown(function(e)
				{
					var key = e.charCode || e.keyCode || 0;
					// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
					// home, end, period, and numpad decimal
					return (
						key == 8 || 
						key == 9 ||
						key == 13 ||
						key == 46 ||
						key == 110 ||
						(key >= 35 && key <= 40) ||
						(key >= 48 && key <= 57) ||
						(key >= 96 && key <= 105));
				});
			});
		};
		
		$.fn.ForcePhoneOnly = function() {
			return this.each(function() {
				$(this).keydown(function(e)
				{
					var key = e.charCode || e.keyCode || 0;
					// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
					// home, end, period, and numpad decimal
					return (
						key == 8 || 
						key == 9 ||
						key == 13 ||
						key == 32 ||
						key == 46 ||
						key == 189 ||
						(key >= 35 && key <= 40) ||
						(key >= 48 && key <= 57) ||
						(key >= 96 && key <= 105));
				});
			});
		};
	
		$('#zip').ForceNumericOnly();
		$('#phoneprimary').ForcePhoneOnly();
		$('#phoneother').ForcePhoneOnly();
		$('#amount').ForceNumericOnly();
		
		$('#alumni').change(function() {
        	if ($(this).is(":checked")) {
				$('.alumpanel').show();
				$('#alumdetails').addClass('required error');
			} else {
				$('.alumpanel').hide();
				$('#alumdetails').val('').removeClass('required').removeClass('error').removeClass('valid');
			}
		});
		
		$('#matchinggift').change(function () {
			
			if ($(this).val() == 'Yes') {
				
				$('.companypanel').show();
				$('#companyname').addClass('required error');
				
			} else {
				$('.companypanel').hide();
				$('#companyname').val('').removeClass('required').removeClass('error').removeClass('valid');
			}
		});
		
		$('#funds').change(function () {
			
			if ($(this).val() == 'momentum') {
				
				$('.momentumpanel').show();
				//momentum
				$('.momentumpanel .selectbox').addClass('required error');
				$('#momentum').addClass('required error');
				
			} else {
				$('.momentumpanel').hide();
				$('.momentumpanel .selectbox').val('').removeClass('required').removeClass('error').removeClass('valid');
				$('#momentum').val('').removeClass('required').removeClass('error').removeClass('valid');
			}
		});
		
		$('#momentum').change(function () {
			
			if ($(this).val() !== '') {
				
				$('.momentumpanel .selectbox').addClass('valid');
				$('#momentum').addClass('valid');
		
			} else {
				$('.momentumpanel .selectbox').removeClass('valid').addClass('error');
				$('#momentum').removeClass('valid').addClass('error');
			}
		});
		
		$('#businesscard').change(function () {
			
			if ($(this).val() == 'Yes') {
				
				$('.creditpanel').show();
				$('#creditname').addClass('required error');
				$('#creditaddress').addClass('required error');
				
			} else {
				$('.creditpanel').hide();
				$('#creditname').val('').removeClass('required').removeClass('error').removeClass('valid');
				$('#creditaddress').val('').removeClass('required').removeClass('error').removeClass('valid');
			}
			
		});
	});

</script>
                
<div class="page">

	<? include "../templates/layouts/_topshelf.php" ?>
    
    <div class="row outercontent">
        
        <div class="desktop-full tablet-full mobile-full padded">
                    
            <?
                include '../templates/layouts/_breadcrumbs.php';
            ?>
        
            <div class="content">
            
            	<? if ($teaser) { ?>
                    <blockquote><?=$teaser?></blockquote>
                <? } ?>

    			<h2>Gift information</h2>
                
                <hr />
                
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
					select { width:100%; background:transparent;padding:5px; line-height:1;border: 0px;border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;height:30px;font-family: proxima-nova, sans-serif; font-size: 14px; }
					select .option { font-weight:bold; }
					
					input,
					textarea
					{
						border: 1px solid rgb(204, 204, 204);
						display: inline-block;
						font-family: proxima-nova, sans-serif;
						font-size: 14px;
						font-weight: bold;
						line-height:28px;
						margin:0;
						padding:0 15px;
					}
					
					.momentum {
						border:1px solid #ccc;
						padding:20px;
						display:none;
					}
					
					#givingoverlay {
						z-index:6;
						display:none;
						width:100%;
						height:100%;
						top:0;
						left:0;
						position:fixed;
						background:rgba(0,0,0,0.4);
						font-size:13px;
					}
					
					#stepTwo {
						position: absolute;
						left: 50%;
						top: 15%;
						margin-left: -260px;
						width: 520px;
						z-index: 9999;
						font-weight: normal;
						padding: 25px 25px 25px 25px;
						border: 1px solid #ddd;
						background-color: #fff;
						border-radius: 4px;
					}
					
					.spinner {
						display:none;	
					}
					
					fieldset p { margin:0; padding:0; }
					fieldset .phonelabels label { display:inline-block; width:auto; }
					
					.error { background:rgba(255,0,0,.2); border:1px solid rgba(255,0,0,.2); }
					label.error { display:inline-block; color:red; background:none; border:none; padding-left:15px; margin-left:8px; background:transparent url(/images/alert.png) no-repeat 0 3px; }
					
					.selectbox.error select { border: 1px solid rgba(255,0,0,.2); }
					
					.valid { background:rgba(0,255,0,.2); border:1px solid rgba(0,255,0,.2); }
					
					.momentumpanel .selectbox { max-width:300px; }
					
					.alumpanel,
					.companypanel,
					.momentumpanel,
					.creditpanel { display:none; }
					
					.momentuminfo {
						background:rgba(244, 242, 237, 1.0);
						border-radius:10px;
						padding-top:15px;
						padding-right:30px;
						padding-bottom:15px;
						padding-left:30px;
					}
					
					div.affiliation label { float:none; margin-left:5px; }
					
					div.affiliation input {  }
					
					#givingform .row { font-size:14px; padding:10px; }
					
				</style>
                
                <form id="givingform" class="givingform" name="givingform" method="post" autocomplete="off" action="/about/giving/give/processing-email/" novalidate>
                    <fieldset style="width:100%;padding:0;margin:0;">
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="firstname">First Name *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="firstname" name="firstname" type="text" placeholder="First Name" required />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="lastname">Last Name *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="lastname" name="lastname" type="text" placeholder="Last Name" required />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="prevname">Previous Name (if applicable)</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="prevname" name="prevname" placeholder="Previous Name" type="text" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="recognize">How would you like us to recognize you?</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <span class="selectbox">
                                    <select name="recognize" id="recognize">
                                        <option value="The name I entered above">The name I entered above</option>
                                        <option value="I prefer to give anonymously">I prefer to give anonymously</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="address1">Address Line 1 *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="address1" name="address1" type="text" placeholder="Address Line 1" required />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="address2">Address Line 2</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="address2" name="address2" placeholder="Address Line 2" type="text" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="city">City *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="city" name="city" type="text" placeholder="City" required />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="state">State *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <span class="selectbox">
                                    <select name="state" id="state" required />
                                        <option selected="selected" value="Alaska">AK</option>
                                        <option value="Alberta">AB</option>
                                        <option value="Alabama">AL</option>
                                        <option value="Arizona">AZ</option>
                                        <option value="Arkansas">AR</option>
                                        <option value="British Columbia">BC</option>
                                        <option value="California">CA</option>
                                        <option value="Colorado">CO</option>
                                        <option value="Connecticut">CT</option>
                                        <option value="Delaware">DE</option>
                                        <option value="District of Columbia">DC</option>
                                        <option value="Florida">FL</option>
                                        <option value="Georgia">GA</option>
                                        <option value="Guam">GU</option>
                                        <option value="Hawaii">HI</option>
                                        <option value="Idaho">ID</option>
                                        <option value="Illinois">IL</option>
                                        <option value="Indiana">IN</option>
                                        <option value="Iowa">IA</option>
                                        <option value="Kansas">KS</option>
                                        <option value="Kentucky">KY</option>
                                        <option value="Louisiana">LA</option>
                                        <option value="Maine">ME</option>
                                        <option value="Manitoba">MB</option>
                                        <option value="Maryland">MD</option>
                                        <option value="Massachusetts">MA</option>
                                        <option value="Michigan">MI</option>
                                        <option value="Minnesota">MN</option>
                                        <option value="Mississippi">MS</option>
                                        <option value="Missouri">MO</option>
                                        <option value="Montana">MT</option>
                                        <option value="Nebraska">NE</option>
                                        <option value="Nevada">NV</option>
                                        <option value="New Brunswick">NB</option>
                                        <option value="New Hampshire">NH</option>
                                        <option value="New Jersey">NJ</option>
                                        <option value="New Mexico">NM</option>
                                        <option value="New York">NY</option>
                                        <option value="Newfoundland and Labrador">NL</option>
                                        <option value="North Carolina">NC</option>
                                        <option value="North Dakota">ND</option>
                                        <option value="Northwest Territories">NT</option>
                                        <option value="Nova Scotia">NS</option>
                                        <option value="Nunavut">NU</option>
                                        <option value="Ohio">OH</option>
                                        <option value="Oklahoma">OK</option>
                                        <option value="Ontario">ON</option>
                                        <option value="Oregon">OR</option>
                                        <option value="Pennsylvania">PA</option>
                                        <option value="Prince Edward Island">PE</option>
                                        <option value="Puerto Rico">PR</option>
                                        <option value="Quebec">QC</option>
                                        <option value="Rhode Island">RI</option>
                                        <option value="Saskatchewan">SK</option>
                                        <option value="South Carolina">SC</option>
                                        <option value="South Dakota">SD</option>
                                        <option value="Tennessee">TN</option>
                                        <option value="Texas">TX</option>
                                        <option value="Utah">UT</option>
                                        <option value="Vermont">VT</option>
                                        <option value="Virginia">VA</option>
                                        <option value="Washington">WA</option>
                                        <option value="West Virginia">WV</option>
                                        <option value="Wisconsin">WI</option>
                                        <option value="Wyoming">WY</option>
                                        <option value="Yukon">YK</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="zip">Zip Code *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input type="text" name="zip" id="zip" maxlength="5" minlength="5" placeholder="Zip code" style="width:100px;" required />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="country">Country *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <span class="selectbox">
                                    <select name="country" id="country">
                                        <option selected="selected" value="United States of America">United States of America</option>
                                        <option value="Afghanistan">Afghanistan</option>
                                        <option value="Albania">Albania</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="Andorra">Andorra</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                        <option value="Argentina">Argentina</option>
                                        <option value="Armenia">Armenia</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Austria">Austria</option>
                                        <option value="Azerbaijan">Azerbaijan</option>
                                        <option value="Bahamas">Bahamas</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Barbados">Barbados</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Belize">Belize</option>
                                        <option value="Benin">Benin</option>
                                        <option value="Bhutan">Bhutan</option>
                                        <option value="Bolivia">Bolivia</option>
                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                        <option value="Botswana">Botswana</option>
                                        <option value="Brazil">Brazil</option>
                                        <option value="Brunei">Brunei</option>
                                        <option value="Bulgaria">Bulgaria</option>
                                        <option value="Burkina Faso">Burkina Faso</option>
                                        <option value="Burma">Burma</option>
                                        <option value="Burundi">Burundi</option>
                                        <option value="Cambodia">Cambodia</option>
                                        <option value="Cameroon">Cameroon</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Cape Verde">Cape Verde</option>
                                        <option value="Central African Republic">Central African Republic</option>
                                        <option value="Chad">Chad</option>
                                        <option value="Chile">Chile</option>
                                        <option value="China">China</option>
                                        <option value="Colombia">Colombia</option>
                                        <option value="Comoros">Comoros</option>
                                        <option value="Congo">Congo</option>
                                        <option value="Costa Rica">Costa Rica</option>
                                        <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                        <option value="Croatia">Croatia</option>
                                        <option value="Cuba">Cuba</option>
                                        <option value="Cyprus">Cyprus</option>
                                        <option value="Czech Republic">Czech Republic</option>
                                        <option value="Denmark">Denmark</option>
                                        <option value="Djibouti">Djibouti</option>
                                        <option value="Dominica">Dominica</option>
                                        <option value="Dominican Republic">Dominican Republic</option>
                                        <option value="East Timor">East Timor</option>
                                        <option value="Ecuador">Ecuador</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="El Salvador">El Salvador</option>
                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                        <option value="Eritrea">Eritrea</option>
                                        <option value="Estonia">Estonia</option>
                                        <option value="Ethiopia">Ethiopia</option>
                                        <option value="Fiji">Fiji</option>
                                        <option value="Finland">Finland</option>
                                        <option value="France">France</option>
                                        <option value="Gabon">Gabon</option>
                                        <option value="Gambia">Gambia</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Greece">Greece</option>
                                        <option value="Grenada">Grenada</option>
                                        <option value="Guatemala">Guatemala</option>
                                        <option value="Guinea">Guinea</option>
                                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                                        <option value="Guyana">Guyana</option>
                                        <option value="Haiti">Haiti</option>
                                        <option value="Honduras">Honduras</option>
                                        <option value="Hungary">Hungary</option>
                                        <option value="Iceland">Iceland</option>
                                        <option value="India">India</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Iran">Iran</option>
                                        <option value="Iraq">Iraq</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="Israel">Israel</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Jamaica">Jamaica</option>
                                        <option value="Japan">Japan</option>
                                        <option value="Jordan">Jordan</option>
                                        <option value="Kazakhstan">Kazakhstan</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="Kiribati">Kiribati</option>
                                        <option value="Korea, North">Korea, North</option>
                                        <option value="Korea, South">Korea, South</option>
                                        <option value="Kuwait">Kuwait</option>
                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                        <option value="Laos">Laos</option>
                                        <option value="Latvia">Latvia</option>
                                        <option value="Lebanon">Lebanon</option>
                                        <option value="Lesotho">Lesotho</option>
                                        <option value="Liberia">Liberia</option>
                                        <option value="Libya">Libya</option>
                                        <option value="Liechtenstein">Liechtenstein</option>
                                        <option value="Lithuania">Lithuania</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Macedonia">Macedonia</option>
                                        <option value="Madagascar">Madagascar</option>
                                        <option value="Malawi">Malawi</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Maldives">Maldives</option>
                                        <option value="Mali">Mali</option>
                                        <option value="Malta">Malta</option>
                                        <option value="Marshall Islands">Marshall Islands</option>
                                        <option value="Mauritania">Mauritania</option>
                                        <option value="Mauritius">Mauritius</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Micronesia">Micronesia</option>
                                        <option value="Moldova">Moldova</option>
                                        <option value="Monaco">Monaco</option>
                                        <option value="Mongolia">Mongolia</option>
                                        <option value="Montenegro">Montenegro</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Mozambique">Mozambique</option>
                                        <option value="Namibia">Namibia</option>
                                        <option value="Nauru">Nauru</option>
                                        <option value="Nepal">Nepal</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Nicaragua">Nicaragua</option>
                                        <option value="Niger">Niger</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Norway">Norway</option>
                                        <option value="Oman">Oman</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="Palau">Palau</option>
                                        <option value="Panama">Panama</option>
                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                        <option value="Paraguay">Paraguay</option>
                                        <option value="Peru">Peru</option>
                                        <option value="Philippines">Philippines</option>
                                        <option value="Poland">Poland</option>
                                        <option value="Portugal">Portugal</option>
                                        <option value="Qatar">Qatar</option>
                                        <option value="Romania">Romania</option>
                                        <option value="Russia">Russia</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                        <option value="Saint Lucia">Saint Lucia</option>
                                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                        <option value="Samoa">Samoa</option>
                                        <option value="San Marino">San Marino</option>
                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Senegal">Senegal</option>
                                        <option value="Serbia">Serbia</option>
                                        <option value="Seychelles">Seychelles</option>
                                        <option value="Sierra Leone">Sierra Leone</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Slovakia">Slovakia</option>
                                        <option value="Slovenia">Slovenia</option>
                                        <option value="Solomon Islands">Solomon Islands</option>
                                        <option value="Somalia">Somalia</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Sri Lanka">Sri Lanka</option>
                                        <option value="Sudan">Sudan</option>
                                        <option value="Suriname">Suriname</option>
                                        <option value="Swaziland">Swaziland</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="Switzerland">Switzerland</option>
                                        <option value="Syria">Syria</option>
                                        <option value="Taiwan">Taiwan</option>
                                        <option value="Tajikistan">Tajikistan</option>
                                        <option value="Tanzania">Tanzania</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Togo">Togo</option>
                                        <option value="Tonga">Tonga</option>
                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Turkey">Turkey</option>
                                        <option value="Turkmenistan">Turkmenistan</option>
                                        <option value="Tuvalu">Tuvalu</option>
                                        <option value="Uganda">Uganda</option>
                                        <option value="Ukraine">Ukraine</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Uruguay">Uruguay</option>
                                        <option value="Uzbekistan">Uzbekistan</option>
                                        <option value="Vanuatu">Vanuatu</option>
                                        <option value="Vatican City">Vatican City</option>
                                        <option value="Venezuela">Venezuela</option>
                                        <option value="Vietnam">Vietnam</option>
                                        <option value="Yemen">Yemen</option>
                                        <option value="Zambia">Zambia</option>
                                        <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="email">Email *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="email" name="email" type="email" placeholder="Email" required />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="phoneprimary">Primary Phone *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="phoneprimary" name="phoneprimary" type="text" placeholder="Primary phone" required />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="phoneother">Other Phone</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="phoneother" name="phoneother" type="text" placeholder="Other phone" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="affiliation">KPC Affiliation</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <div class="affiliation">
                                    <input id="alumni" type="checkbox" name="alumni"> <label for="alumni">Alumni</label><br />
                                    <input id="faculty" type="checkbox" name="faculty"> <label for="faculty">Faculty</label><br />
                                    <input id="staff" type="checkbox" name="staff"> <label for="staff">Staff</label><br />
                                    <input id="student" type="checkbox" name="student"> <label for="student">Student</label><br />
                                    <input id="parent" type="checkbox" name="parent"> <label for="parent">Parent of KPC student</label><br />
                                    <input id="retiree" type="checkbox" name="retiree" /> <label for="retiree">Retiree</label><br />
                                    <input id="friend" type="checkbox" name="friend"> <label for="friend">Friend of KPC</label>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                        
                        <div class="row alumpanel">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="alumdetails">Year(s) and degree(s) received at KPC</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                    <textarea name="alumdetails" rows="4" cols="20" id="alumdetails" style="width:350px;"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="matchinggift">I work for a matching gift company</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <span class="selectbox">
                                    <select name="matchinggift" id="matchinggift" required />
                                        <option value="No">No</option>
                                        <option value="Yes">Yes, the name of the company is:</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row companypanel">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="companyname">&nbsp;</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input name="companyname" type="text" id="companyname" placeholder="Company Name" />
                                            
                                <br /><br />
    
                                <strong>Please mail your company's matching gift form to:</strong><br /><br />
                                
                                <div class="companyaddress">
                                    UA Foundation<br />
                                    RE: KPC Matching Gift<br />
                                    1815 Bragaw St, Ste 203<br />
                                    Anchorage, AK 99508
                                </div>
                                
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                        
                        <hr />
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <label for="amount">Amount *</label>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <div class="row">
                                    <div class="desktop-2 tablet-1 mobile-1 contained">
                                        <input id="amount" name="amount" type="text" style="width:100%;" placeholder="Amount" required />
                                    </div>
                                    <div class="desktop-2 tablet-1 mobile-1 padded">
                                        <span class="selectbox">
                                            <select name="amountcents" id="amountcents" required />
                                                <?
                                                    for ($i == 0; $i < 100;$i++) {
                                                        $i = sprintf("%02d", $i);
                                                        echo '<option value="'.$i.'" />'.$i.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div style="clear:both;"></div>
                        <p>&nbsp;</p>
                        <hr />
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                Where would you like to direct your gift?
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <span class="selectbox">
                                <select name="funds" id="funds" required>
                                    <option selected="selected" value="20768">KPC - Area of Greatest Need</option>
                                    <option value="momentum">KPC - Momentum Campaign</option>
                                    <option value="20287">KPC - Student Scholarship</option>
                                    <option value="20751">Kachemak Bay Campus – Mary Epperson Endowed Student Support and Scholarship</option>
                                    <option value="60684">Kachemak Bay Campus - Spendable</option>
                                    <option value="20426">Kachemak Bay Campus - Student Scholarship</option>
                                    <option value="20940">Kachemak Bay Campus - Writers' Conference</option>
                                    <option value="21058">Anchorage Extension Site - Support</option>
                                </select>
                            </span>
                            </div>
                        </div>
                        
                        <div class="row momentumpanel">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                &nbsp;
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded momentuminfo">
                            
                                <img src="/images/momentum.png" style="float:right;margin:0 0 20px 20px;" />
                                <strong>Thank you for interest in participating in the KPC Momentum Campaign!</strong>
                                
                                <br /><br />
                                
                                Please be aware that this is an annual, internal giving campaign for KPC faculty, staff and College Council members. Please choose where you would like to direct your Momentum Campaign gift below.
                                
                                <br /><br />
                                
                                <span class="selectbox">
                                    <select name="momentum" id="momentum">
                                        <option value=""></option>
                                        <option value="20342">KPC Faculty Scholarship</option>
                                        <option value="20343">KPC Staff Scholarship</option>
                                        <option value="21115">KPC College Council Scholarship</option>
                                        <option value="20768">Kenai Peninsula College - Area of Greatest Need</option>
                                        <option value="20850">Kenai Peninsula College – Pam Ward Memorial Endowed Scholarship</option>
                                        <option value="20751">Kachemak Bay Campus – Mary Epperson Endowed Student Support and Scholarship</option>
                                        <option value="60684">Kachemak Bay Campus Support Spendable</option>
                                        <option value="20426">Kachemak Bay Campus Scholarship</option>
                                        <option value="21058">Anchorage Extension Site - Support</option>
                                    </select>
                                </span>
                                
                                <br /><br />
                                
                                <p>Unfortunately, we cannot set up payroll deducations via this online form. If you are interested in this possibility, please contact KPC's Advancement office at (907) 262-0320</p>
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                Additional information or specifics about your gift. (If you would like to donate to a location other than those listed above, please specify here)
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <textarea name="additionalinfo" rows="6" id="additionalinfo" style="min-width:300px;"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                This gift will be on a business credit card
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <span class="selectbox">
                                    <select name="businesscard" id="businesscard">
                                        <option selected="selected" value="No">No</option>
                                        <option value="Yes">Yes, and I have the credit card name and billing address</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="row creditpanel">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                Business credit card info
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input name="creditname" type="text" id="creditname" placeholder="Name on credit card">
        
                                <br /><br />

                                Billing address of credit card<br />

                                <textarea name="creditaddress" rows="6" id="creditaddress" style="min-width:300px;"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                This gift is in honor/memory of
                                
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input name="honor" type="text" id="honor" placeholder="In honor/memory of"> (i.e. In honor of James Smith)
                            </div>
                        </div>
                        
                        <p>&nbsp;</p>
                        <h2>We want to stay in touch with you</h2>
                        <hr />
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                <p>We want to hear from you. Please share why you give to KPC.</p>
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <textarea name="comments" rows="6" cols="20" id="" style="min-width:300px;"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="desktop-3 tablet-2 mobile-full padded">
                                &nbsp;
                            </div>
                            <div class="desktop-9 tablet-4 mobile-full padded">
                                <input id="estateplans" type="checkbox" name="estateplans"> <label for="estateplans">Please contact me about including KPC in my estate plans.</label><br />
                                <input id="newsletter" type="checkbox" name="newsletter"> <label for="newsletter">Please subscribe me to the KPC daily e-mail newsletter, the KPCWord.</label>
                            </div>
                        </div>
                        
                        <hr />
                        
                        <div class="row">
                            <div class="desktop-6 tablet-3 mobile-half min-full contained">
                                <input type="submit" id="submit" value="Continue to Payment" />
                            </div>
                            <div class="desktop-6 tablet-3 mobile-half min-full mobilebadge"><img src="/images/128bit-badge.gif" /></div>
                        </div>
                    </fieldset>
                </form>
				
                <div class="margin_m"></div>
                
            </div>
            
        </div>
        
	</div>
</div>

<script type="text/javascript">
	var form = $( "#givingform" );
	form.validate();
</script>
