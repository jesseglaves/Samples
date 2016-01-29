<%@ Page Title="Give to KPC." Language="C#" Debug="true" MasterPageFile="~/templates/master/interior.master" AutoEventWireup="true" CodeFile="default.aspx.cs" Inherits="give_default" %>
<%@ Register TagPrefix="recaptcha" Namespace="Recaptcha" Assembly="Recaptcha" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">

    <meta http-equiv="Page-Enter" content="blendTrans(Duration=0.01)" />

    <link href="styles.css" rel="stylesheet" type="text/css" />
    <link href="button.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
    
        #middlerightcontent
        {
            width:100%;
        }
    
    </style>

    <script src="jquery.validate.js" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function () {

            //$('#pnlModal').show();

            $('table.formTbl > tr:odd > td').css('background-color', '#eeeeee');
            $('table.formTbl > tr > td:first-child').css('font-weight', 'bold');

            $('table.formTbl > tr:odd > td td').css('background-color', '#ffffff');
            $('table.formTbl > tr > td:first-child td').css('font-weight', 'bold');

            $('#pnlRecogOther').hide();
            $('#pnlMatchingGift').hide();
            $('#pnlMomentum').hide();
            $('#pnlBusinessCredit').hide();

            $('#aspnetForm').validate().form();
            $('#<%= txtFirstName.ClientID %>').focus();
            //$('#pnlModal').show();

            $('input, select, textarea').each(function () {
                $(this).attr('autocomplete', 'off');
                //$(this).addClass('valid');
            });

            $('#btnBack').click(function () {
                $('#pnlModal').hide();
            });

            $("input, select").bind("keydown", function (event) {
                var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));
                if (keycode == 13) {
                    ValidateForm();
                    return false;
                } else {
                    return true;
                }
            });

            $('.btn, textarea.tabToSubmit').bind('keydown', function (event) {
                var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));

                var btn = $(this).attr('id');
                //alert(btn);

                if (keycode == 9) {
                    if (btn == 'btnBack') {
                        $('#btnNext').focus();
                        return false;
                    }
                    if (btn == 'btnNext') {
                        $('#btnBack').focus();
                        return false;
                    }
                }
                else {
                    return true;
                }
            });

            $('#button').click(function () {
                ValidateForm();
            });

            $('#<%= btnNext.ClientID %>').click(function () {

                //alert('test');
                $('#stepTwo').hide();
                //$('#loading').show();

            });

            function ValidateForm() {
                if ($('#aspnetForm').valid()) {
                    $('#pnlModal').show();
                    $('#btnBack').focus();
                }
                else {
                    $('#aspnetForm').validate().form();
                    $('#aspnetForm').find(":input.error:first").focus();
                }
            }

            $('#<%= ddlRecognizeMeAs.ClientID %>').change(function () {

                if ($(this).val() == 'other') {
                    $('#pnlRecogOther').show();
                    $('#<%= txtRecognizeOther.ClientID %>').focus();
                    $('#<%= txtRecognizeOther.ClientID %>').addClass("required");
                    $('#aspnetForm').validate().form();
                }
                else if ($(this).val() != 'other') {
                    $('#pnlRecogOther').hide();
                    $('#<%= txtRecognizeOther.ClientID %>').val('');
                    $('#<%= txtRecognizeOther.ClientID %>').removeClass("required");
                }
            });

            $('#<%= ddlMatch.ClientID %>').change(function () {
                if ($(this).val() == 'Yes') {
                    $('#pnlMatchingGift').show();
                    $('#<%= txtMatchingCompany.ClientID %>').focus();
                    $('#<%= txtMatchingCompany.ClientID %>').addClass('required');
                    $('#aspnetForm').validate().form();

                }
                else if ($(this).val() != 'Yes') {
                    $('#pnlMatchingGift').hide();
                    $('#<%= txtMatchingCompany.ClientID %>').val('');
                    $('#<%= txtMatchingCompany.ClientID %>').removeClass('required');
                }
            });

            $('#<%= ddlGiftDir.ClientID %>').change(function () {

                if ($(this).val() == 'momentum') {
                    $('#pnlMomentum').show();
                    $('#<%= ddlMomentum.ClientID %>').focus();
                    $('#<%= ddlMomentum.ClientID %>').addClass("required");
                    $('#aspnetForm').validate().form();
                }
                else if ($(this).val() != 'momentum') {
                    $('#pnlMomentum').hide();
                    $('#<%= ddlMomentum.ClientID %>').val('');
                    $('#<%= ddlMomentum.ClientID %>').removeClass("required");
                }
            });

            $('#<%= ddlBusinessCredit.ClientID %>').change(function () {

                if ($(this).val() == 'Yes') {
                    $('#pnlBusinessCredit').show();
                    $('#<%= txtBusinessCreditCompanyName.ClientID %>').focus();
                    $('#<%= txtBusinessCreditCompanyName.ClientID %>').addClass("required");
                    $('#<%= txtBusinessCreditBilling.ClientID %>').addClass("required");
                    $('#aspnetForm').validate().form();
                }
                else if ($(this).val() != 'Yes') {
                    $('#pnlBusinessCredit').hide();
                    $('#<%= txtBusinessCreditCompanyName.ClientID %>').val('');
                    $('#<%= txtBusinessCreditCompanyName.ClientID %>').removeClass("required");
                    $('#<%= txtBusinessCreditBilling.ClientID %>').val('');
                    $('#<%= txtBusinessCreditBilling.ClientID %>').removeClass("required");
                }
            });
        });

    </script>

</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder2" Runat="Server"></asp:Content>

<asp:Content ID="Content3" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">

    <p class="breadcrumb">
        <span><a target="_self" href="/KPC/" title="KPC">Kenai Peninsula College</a> &gt; <a href="/KPC/giving/" title="Giving">Giving</a> &gt; Give to KPC</span>
    </p>

    <br />

    <div id="givingBanner" style="position:relative;z-index:200;">

        <div id="givingLogo" style="width:212px;height:192px;background:url(photo.png) no-repeat;position:absolute;top:15px;right:25px;"></div>
        <img src="header.png" title="Give to KPC" />

        <div style="padding:22px 235px 22px 10px;display:block;font-size:14px;">
            To make a gift to Kenai Peninsula College, please begin by completing the following form. 
            The University of Alaska Foundation processes online gifts for KPC and you will be directed to the UA Foundation payment site to complete your gift. 
            You will receive a receipt in the mail from the UA Foundation for your tax records.
        </div>

    </div>

    <div style="clear:both;"></div>

    <h1 class="heading">Step 1 of 2: Gift information.</h1>

    <noscript>
    <div id="scriptAlert">
        <img src="alert.png" alt="noscript" /> 
        <strong style="font-size:16px;">Oops! It looks like JavaScript is disabled in your browser.</strong><br />
        To function correctly this form relies heavily upon JavaScript. Please enable JavaScript and try again. 
    </div><br />
    </noscript>

    <table class="formTbl" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="width:360px;">Your First Name</td>
            <td>
                <asp:TextBox ID="txtFirstName" CssClass="required" runat="server" />
            </td>
        </tr>
        <tr>
            <td>Your Last Name</td>
            <td>
                <asp:TextBox ID="txtLastName" CssClass="required" runat="server" />
            </td>
        </tr>
        <tr>
            <td>Previous Name, if applicable</td>
            <td>
                <asp:TextBox ID="txtPrevName" runat="server" />
            </td>
        </tr>
        <tr>
            <td>How would you like us to recognize you?</td>
            <td class="recog">
                <asp:DropDownList ID="ddlRecognizeMeAs" CssClass="required" runat="server">
	                <asp:ListItem></asp:ListItem>
	                <asp:ListItem Value="name entered">The name I entered above</asp:ListItem>
	                <asp:ListItem Value="anonymous">I prefer to give anonymously</asp:ListItem>
	                <asp:ListItem Value="other">Other</asp:ListItem>
                </asp:DropDownList>

                <div id="pnlRecogOther">
                    Other <asp:TextBox ID="txtRecognizeOther" runat="server" />
                </div>
            </td>
        </tr>
        <tr>
            <td>Address line one</td>
            <td>
                <asp:TextBox ID="txtAddr1" Width="250px" CssClass="required" runat="server" />
            </td>
        </tr>
        <tr>
            <td>Address line two</td>
            <td><asp:TextBox ID="txtAddr2" Width="250px" runat="server" /></td>
        </tr>
        <tr>
            <td>City</td>
            <td>
                <asp:TextBox ID="txtCity" CssClass="required" runat="server" />
            </td>
        </tr>
        <tr>
            <td>State/Province</td>
            <td>
                    
                <asp:DropDownList ID="ddlState" CssClass="required" runat="server">
                    <asp:ListItem></asp:ListItem>
                    <asp:ListItem Value="Alaska">AK</asp:ListItem>
                    <asp:ListItem Value="Alberta">AB</asp:ListItem>
                    <asp:ListItem Value="Alabama">AL</asp:ListItem>
                    <asp:ListItem Value="Arizona">AZ</asp:ListItem>
                    <asp:ListItem Value="Arkansas">AR</asp:ListItem>
                    <asp:ListItem Value="British Columbia">BC</asp:ListItem>
                    <asp:ListItem Value="California">CA</asp:ListItem>
                    <asp:ListItem Value="Colorado">CO</asp:ListItem>
                    <asp:ListItem Value="Connecticut">CT</asp:ListItem>
                    <asp:ListItem Value="Delaware">DE</asp:ListItem>
                    <asp:ListItem Value="District of Columbia">DC</asp:ListItem>
                    <asp:ListItem Value="Florida">FL</asp:ListItem>
                    <asp:ListItem Value="Georgia">GA</asp:ListItem>
                    <asp:ListItem Value="Guam">GU</asp:ListItem>
                    <asp:ListItem Value="Hawaii">HI</asp:ListItem>
                    <asp:ListItem Value="Idaho">ID</asp:ListItem>
                    <asp:ListItem Value="Illinois">IL</asp:ListItem>
                    <asp:ListItem Value="Indiana">IN</asp:ListItem>
                    <asp:ListItem Value="Iowa">IA</asp:ListItem>
                    <asp:ListItem Value="Kansas">KS</asp:ListItem>
                    <asp:ListItem Value="Kentucky">KY</asp:ListItem>
                    <asp:ListItem Value="Louisiana">LA</asp:ListItem>
                    <asp:ListItem Value="Maine">ME</asp:ListItem>
                    <asp:ListItem Value="Manitoba">MB</asp:ListItem>
                    <asp:ListItem Value="Maryland">MD</asp:ListItem>
                    <asp:ListItem Value="Massachusetts">MA</asp:ListItem>
                    <asp:ListItem Value="Michigan">MI</asp:ListItem>
                    <asp:ListItem Value="Minnesota">MN</asp:ListItem>
                    <asp:ListItem Value="Mississippi">MS</asp:ListItem>
                    <asp:ListItem Value="Missouri">MO</asp:ListItem>
                    <asp:ListItem Value="Montana">MT</asp:ListItem>
                    <asp:ListItem Value="Nebraska">NE</asp:ListItem>
                    <asp:ListItem Value="Nevada">NV</asp:ListItem>
                    <asp:ListItem Value="New Brunswick">NB</asp:ListItem>
                    <asp:ListItem Value="New Hampshire">NH</asp:ListItem>
                    <asp:ListItem Value="New Jersey">NJ</asp:ListItem>
                    <asp:ListItem Value="New Mexico">NM</asp:ListItem>
                    <asp:ListItem Value="New York">NY</asp:ListItem>
                    <asp:ListItem Value="Newfoundland and Labrador">NL</asp:ListItem>
                    <asp:ListItem Value="North Carolina">NC</asp:ListItem>
                    <asp:ListItem Value="North Dakota">ND</asp:ListItem>
                    <asp:ListItem Value="Northwest Territories">NT</asp:ListItem>
                    <asp:ListItem Value="Nova Scotia">NS</asp:ListItem>
                    <asp:ListItem Value="Nunavut">NU</asp:ListItem>
                    <asp:ListItem Value="Ohio">OH</asp:ListItem>
                    <asp:ListItem Value="Oklahoma">OK</asp:ListItem>
                    <asp:ListItem Value="Ontario">ON</asp:ListItem>
                    <asp:ListItem Value="Oregon">OR</asp:ListItem>
                    <asp:ListItem Value="Pennsylvania">PA</asp:ListItem>
                    <asp:ListItem Value="Prince Edward Island">PE</asp:ListItem>
                    <asp:ListItem Value="Puerto Rico">PR</asp:ListItem>
                    <asp:ListItem Value="Quebec">QC</asp:ListItem>
                    <asp:ListItem Value="Rhode Island">RI</asp:ListItem>
                    <asp:ListItem Value="Saskatchewan">SK</asp:ListItem>
                    <asp:ListItem Value="South Carolina">SC</asp:ListItem>
                    <asp:ListItem Value="South Dakota">SD</asp:ListItem>
                    <asp:ListItem Value="Tennessee">TN</asp:ListItem>
                    <asp:ListItem Value="Texas">TX</asp:ListItem>
                    <asp:ListItem Value="Utah">UT</asp:ListItem>
                    <asp:ListItem Value="Vermont">VT</asp:ListItem>
                    <asp:ListItem Value="Virginia">VA</asp:ListItem>
                    <asp:ListItem Value="Washington">WA</asp:ListItem>
                    <asp:ListItem Value="West Virginia">WV</asp:ListItem>
                    <asp:ListItem Value="Wisconsin">WI</asp:ListItem>
                    <asp:ListItem Value="Wyoming">WY</asp:ListItem>
                    <asp:ListItem Value="Yukon">YK</asp:ListItem>
                </asp:DropDownList>
                    
            </td>
        </tr>
        <tr>
            <td>Zip/Postal Code</td>
            <td>
                <asp:TextBox ID="txtZip" CssClass="required zip" runat="server" />
            </td>
        </tr>
        <tr>
            <td>Country</td>
            <td>

                <asp:DropDownList ID="ddlCountry" CssClass="required" runat="server">
                    <asp:ListItem ></asp:ListItem>
                    <asp:ListItem Value="United States of America">United States of America</asp:ListItem>
                    <asp:ListItem Value="Afghanistan">Afghanistan</asp:ListItem>
                    <asp:ListItem Value="Albania">Albania</asp:ListItem>
                    <asp:ListItem Value="Algeria">Algeria</asp:ListItem>
                    <asp:ListItem Value="Andorra">Andorra</asp:ListItem>
                    <asp:ListItem Value="Angola">Angola</asp:ListItem>
                    <asp:ListItem Value="Antigua and Barbuda">Antigua and Barbuda</asp:ListItem>
                    <asp:ListItem Value="Argentina">Argentina</asp:ListItem>
                    <asp:ListItem Value="Armenia">Armenia</asp:ListItem>
                    <asp:ListItem Value="Australia">Australia</asp:ListItem>
                    <asp:ListItem Value="Austria">Austria</asp:ListItem>
                    <asp:ListItem Value="Azerbaijan">Azerbaijan</asp:ListItem>
                    <asp:ListItem Value="Bahamas">Bahamas</asp:ListItem>
                    <asp:ListItem Value="Bahrain">Bahrain</asp:ListItem>
                    <asp:ListItem Value="Bangladesh">Bangladesh</asp:ListItem>
                    <asp:ListItem Value="Barbados">Barbados</asp:ListItem>
                    <asp:ListItem Value="Belarus">Belarus</asp:ListItem>
                    <asp:ListItem Value="Belgium">Belgium</asp:ListItem>
                    <asp:ListItem Value="Belize">Belize</asp:ListItem>
                    <asp:ListItem Value="Benin">Benin</asp:ListItem>
                    <asp:ListItem Value="Bhutan">Bhutan</asp:ListItem>
                    <asp:ListItem Value="Bolivia">Bolivia</asp:ListItem>
                    <asp:ListItem Value="Bosnia and Herzegovina">Bosnia and Herzegovina</asp:ListItem>
                    <asp:ListItem Value="Botswana">Botswana</asp:ListItem>
                    <asp:ListItem Value="Brazil">Brazil</asp:ListItem>
                    <asp:ListItem Value="Brunei">Brunei</asp:ListItem>
                    <asp:ListItem Value="Bulgaria">Bulgaria</asp:ListItem>
                    <asp:ListItem Value="Burkina Faso">Burkina Faso</asp:ListItem>
                    <asp:ListItem Value="Burma">Burma</asp:ListItem>
                    <asp:ListItem Value="Burundi">Burundi</asp:ListItem>
                    <asp:ListItem Value="Cambodia">Cambodia</asp:ListItem>
                    <asp:ListItem Value="Cameroon">Cameroon</asp:ListItem>
                    <asp:ListItem Value="Canada">Canada</asp:ListItem>
                    <asp:ListItem Value="Cape Verde">Cape Verde</asp:ListItem>
                    <asp:ListItem Value="Central African Republic">Central African Republic</asp:ListItem>
                    <asp:ListItem Value="Chad">Chad</asp:ListItem>
                    <asp:ListItem Value="Chile">Chile</asp:ListItem>
                    <asp:ListItem Value="China">China</asp:ListItem>
                    <asp:ListItem Value="Colombia">Colombia</asp:ListItem>
                    <asp:ListItem Value="Comoros">Comoros</asp:ListItem>
                    <asp:ListItem Value="Congo">Congo</asp:ListItem>
                    <asp:ListItem Value="Costa Rica">Costa Rica</asp:ListItem>
                    <asp:ListItem Value="Côte d'Ivoire">Côte d'Ivoire</asp:ListItem>
                    <asp:ListItem Value="Croatia">Croatia</asp:ListItem>
                    <asp:ListItem Value="Cuba">Cuba</asp:ListItem>
                    <asp:ListItem Value="Cyprus">Cyprus</asp:ListItem>
                    <asp:ListItem Value="Czech Republic">Czech Republic</asp:ListItem>
                    <asp:ListItem Value="Denmark">Denmark</asp:ListItem>
                    <asp:ListItem Value="Djibouti">Djibouti</asp:ListItem>
                    <asp:ListItem Value="Dominica">Dominica</asp:ListItem>
                    <asp:ListItem Value="Dominican Republic">Dominican Republic</asp:ListItem>
                    <asp:ListItem Value="East Timor">East Timor</asp:ListItem>
                    <asp:ListItem Value="Ecuador">Ecuador</asp:ListItem>
                    <asp:ListItem Value="Egypt">Egypt</asp:ListItem>
                    <asp:ListItem Value="El Salvador">El Salvador</asp:ListItem>
                    <asp:ListItem Value="Equatorial Guinea">Equatorial Guinea</asp:ListItem>
                    <asp:ListItem Value="Eritrea">Eritrea</asp:ListItem>
                    <asp:ListItem Value="Estonia">Estonia</asp:ListItem>
                    <asp:ListItem Value="Ethiopia">Ethiopia</asp:ListItem>
                    <asp:ListItem Value="Fiji">Fiji</asp:ListItem>
                    <asp:ListItem Value="Finland">Finland</asp:ListItem>
                    <asp:ListItem Value="France">France</asp:ListItem>
                    <asp:ListItem Value="Gabon">Gabon</asp:ListItem>
                    <asp:ListItem Value="Gambia">Gambia</asp:ListItem>
                    <asp:ListItem Value="Georgia">Georgia</asp:ListItem>
                    <asp:ListItem Value="Germany">Germany</asp:ListItem>
                    <asp:ListItem Value="Ghana">Ghana</asp:ListItem>
                    <asp:ListItem Value="Greece">Greece</asp:ListItem>
                    <asp:ListItem Value="Grenada">Grenada</asp:ListItem>
                    <asp:ListItem Value="Guatemala">Guatemala</asp:ListItem>
                    <asp:ListItem Value="Guinea">Guinea</asp:ListItem>
                    <asp:ListItem Value="Guinea-Bissau">Guinea-Bissau</asp:ListItem>
                    <asp:ListItem Value="Guyana">Guyana</asp:ListItem>
                    <asp:ListItem Value="Haiti">Haiti</asp:ListItem>
                    <asp:ListItem Value="Honduras">Honduras</asp:ListItem>
                    <asp:ListItem Value="Hungary">Hungary</asp:ListItem>
                    <asp:ListItem Value="Iceland">Iceland</asp:ListItem>
                    <asp:ListItem Value="India">India</asp:ListItem>
                    <asp:ListItem Value="Indonesia">Indonesia</asp:ListItem>
                    <asp:ListItem Value="Iran">Iran</asp:ListItem>
                    <asp:ListItem Value="Iraq">Iraq</asp:ListItem>
                    <asp:ListItem Value="Ireland">Ireland</asp:ListItem>
                    <asp:ListItem Value="Israel">Israel</asp:ListItem>
                    <asp:ListItem Value="Italy">Italy</asp:ListItem>
                    <asp:ListItem Value="Jamaica">Jamaica</asp:ListItem>
                    <asp:ListItem Value="Japan">Japan</asp:ListItem>
                    <asp:ListItem Value="Jordan">Jordan</asp:ListItem>
                    <asp:ListItem Value="Kazakhstan">Kazakhstan</asp:ListItem>
                    <asp:ListItem Value="Kenya">Kenya</asp:ListItem>
                    <asp:ListItem Value="Kiribati">Kiribati</asp:ListItem>
                    <asp:ListItem Value="Korea, North">Korea, North</asp:ListItem>
                    <asp:ListItem Value="Korea, South">Korea, South</asp:ListItem>
                    <asp:ListItem Value="Kuwait">Kuwait</asp:ListItem>
                    <asp:ListItem Value="Kyrgyzstan">Kyrgyzstan</asp:ListItem>
                    <asp:ListItem Value="Laos">Laos</asp:ListItem>
                    <asp:ListItem Value="Latvia">Latvia</asp:ListItem>
                    <asp:ListItem Value="Lebanon">Lebanon</asp:ListItem>
                    <asp:ListItem Value="Lesotho">Lesotho</asp:ListItem>
                    <asp:ListItem Value="Liberia">Liberia</asp:ListItem>
                    <asp:ListItem Value="Libya">Libya</asp:ListItem>
                    <asp:ListItem Value="Liechtenstein">Liechtenstein</asp:ListItem>
                    <asp:ListItem Value="Lithuania">Lithuania</asp:ListItem>
                    <asp:ListItem Value="Luxembourg">Luxembourg</asp:ListItem>
                    <asp:ListItem Value="Macedonia">Macedonia</asp:ListItem>
                    <asp:ListItem Value="Madagascar">Madagascar</asp:ListItem>
                    <asp:ListItem Value="Malawi">Malawi</asp:ListItem>
                    <asp:ListItem Value="Malaysia">Malaysia</asp:ListItem>
                    <asp:ListItem Value="Maldives">Maldives</asp:ListItem>
                    <asp:ListItem Value="Mali">Mali</asp:ListItem>
                    <asp:ListItem Value="Malta">Malta</asp:ListItem>
                    <asp:ListItem Value="Marshall Islands">Marshall Islands</asp:ListItem>
                    <asp:ListItem Value="Mauritania">Mauritania</asp:ListItem>
                    <asp:ListItem Value="Mauritius">Mauritius</asp:ListItem>
                    <asp:ListItem Value="Mexico">Mexico</asp:ListItem>
                    <asp:ListItem Value="Micronesia">Micronesia</asp:ListItem>
                    <asp:ListItem Value="Moldova">Moldova</asp:ListItem>
                    <asp:ListItem Value="Monaco">Monaco</asp:ListItem>
                    <asp:ListItem Value="Mongolia">Mongolia</asp:ListItem>
                    <asp:ListItem Value="Montenegro">Montenegro</asp:ListItem>
                    <asp:ListItem Value="Morocco">Morocco</asp:ListItem>
                    <asp:ListItem Value="Mozambique">Mozambique</asp:ListItem>
                    <asp:ListItem Value="Namibia">Namibia</asp:ListItem>
                    <asp:ListItem Value="Nauru">Nauru</asp:ListItem>
                    <asp:ListItem Value="Nepal">Nepal</asp:ListItem>
                    <asp:ListItem Value="Netherlands">Netherlands</asp:ListItem>
                    <asp:ListItem Value="New Zealand">New Zealand</asp:ListItem>
                    <asp:ListItem Value="Nicaragua">Nicaragua</asp:ListItem>
                    <asp:ListItem Value="Niger">Niger</asp:ListItem>
                    <asp:ListItem Value="Nigeria">Nigeria</asp:ListItem>
                    <asp:ListItem Value="Norway">Norway</asp:ListItem>
                    <asp:ListItem Value="Oman">Oman</asp:ListItem>
                    <asp:ListItem Value="Pakistan">Pakistan</asp:ListItem>
                    <asp:ListItem Value="Palau">Palau</asp:ListItem>
                    <asp:ListItem Value="Panama">Panama</asp:ListItem>
                    <asp:ListItem Value="Papua New Guinea">Papua New Guinea</asp:ListItem>
                    <asp:ListItem Value="Paraguay">Paraguay</asp:ListItem>
                    <asp:ListItem Value="Peru">Peru</asp:ListItem>
                    <asp:ListItem Value="Philippines">Philippines</asp:ListItem>
                    <asp:ListItem Value="Poland">Poland</asp:ListItem>
                    <asp:ListItem Value="Portugal">Portugal</asp:ListItem>
                    <asp:ListItem Value="Qatar">Qatar</asp:ListItem>
                    <asp:ListItem Value="Romania">Romania</asp:ListItem>
                    <asp:ListItem Value="Russia">Russia</asp:ListItem>
                    <asp:ListItem Value="Rwanda">Rwanda</asp:ListItem>
                    <asp:ListItem Value="Saint Kitts and Nevis">Saint Kitts and Nevis</asp:ListItem>
                    <asp:ListItem Value="Saint Lucia">Saint Lucia</asp:ListItem>
                    <asp:ListItem Value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</asp:ListItem>
                    <asp:ListItem Value="Samoa">Samoa</asp:ListItem>
                    <asp:ListItem Value="San Marino">San Marino</asp:ListItem>
                    <asp:ListItem Value="Sao Tome and Principe">Sao Tome and Principe</asp:ListItem>
                    <asp:ListItem Value="Saudi Arabia">Saudi Arabia</asp:ListItem>
                    <asp:ListItem Value="Senegal">Senegal</asp:ListItem>
                    <asp:ListItem Value="Serbia">Serbia</asp:ListItem>
                    <asp:ListItem Value="Seychelles">Seychelles</asp:ListItem>
                    <asp:ListItem Value="Sierra Leone">Sierra Leone</asp:ListItem>
                    <asp:ListItem Value="Singapore">Singapore</asp:ListItem>
                    <asp:ListItem Value="Slovakia">Slovakia</asp:ListItem>
                    <asp:ListItem Value="Slovenia">Slovenia</asp:ListItem>
                    <asp:ListItem Value="Solomon Islands">Solomon Islands</asp:ListItem>
                    <asp:ListItem Value="Somalia">Somalia</asp:ListItem>
                    <asp:ListItem Value="South Africa">South Africa</asp:ListItem>
                    <asp:ListItem Value="Spain">Spain</asp:ListItem>
                    <asp:ListItem Value="Sri Lanka">Sri Lanka</asp:ListItem>
                    <asp:ListItem Value="Sudan">Sudan</asp:ListItem>
                    <asp:ListItem Value="Suriname">Suriname</asp:ListItem>
                    <asp:ListItem Value="Swaziland">Swaziland</asp:ListItem>
                    <asp:ListItem Value="Sweden">Sweden</asp:ListItem>
                    <asp:ListItem Value="Switzerland">Switzerland</asp:ListItem>
                    <asp:ListItem Value="Syria">Syria</asp:ListItem>
                    <asp:ListItem Value="Taiwan">Taiwan</asp:ListItem>
                    <asp:ListItem Value="Tajikistan">Tajikistan</asp:ListItem>
                    <asp:ListItem Value="Tanzania">Tanzania</asp:ListItem>
                    <asp:ListItem Value="Thailand">Thailand</asp:ListItem>
                    <asp:ListItem Value="Togo">Togo</asp:ListItem>
                    <asp:ListItem Value="Tonga">Tonga</asp:ListItem>
                    <asp:ListItem Value="Trinidad and Tobago">Trinidad and Tobago</asp:ListItem>
                    <asp:ListItem Value="Tunisia">Tunisia</asp:ListItem>
                    <asp:ListItem Value="Turkey">Turkey</asp:ListItem>
                    <asp:ListItem Value="Turkmenistan">Turkmenistan</asp:ListItem>
                    <asp:ListItem Value="Tuvalu">Tuvalu</asp:ListItem>
                    <asp:ListItem Value="Uganda">Uganda</asp:ListItem>
                    <asp:ListItem Value="Ukraine">Ukraine</asp:ListItem>
                    <asp:ListItem Value="United Arab Emirates">United Arab Emirates</asp:ListItem>
                    <asp:ListItem Value="United Kingdom">United Kingdom</asp:ListItem>
                    <asp:ListItem Value="Uruguay">Uruguay</asp:ListItem>
                    <asp:ListItem Value="Uzbekistan">Uzbekistan</asp:ListItem>
                    <asp:ListItem Value="Vanuatu">Vanuatu</asp:ListItem>
                    <asp:ListItem Value="Vatican City">Vatican City</asp:ListItem>
                    <asp:ListItem Value="Venezuela">Venezuela</asp:ListItem>
                    <asp:ListItem Value="Vietnam">Vietnam</asp:ListItem>
                    <asp:ListItem Value="Yemen">Yemen</asp:ListItem>
                    <asp:ListItem Value="Zambia">Zambia</asp:ListItem>
                    <asp:ListItem Value="Zimbabwe">Zimbabwe</asp:ListItem>
                </asp:DropDownList>

            </td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td>
                <asp:TextBox ID="txtEmail" CssClass="required email" Width="200px" runat="server" />
            </td>
        </tr>
        <tr>
            <td valign="top">Primary Phone Number</td>
            <td>
                <asp:TextBox ID="txtPhone" CssClass="required phoneUS" Width="100px" runat="server" />
                <br />
                <asp:RadioButtonList ID="rblPhone" RepeatDirection="Horizontal" runat="server">
                    <asp:ListItem Selected="True">Cell</asp:ListItem>
                    <asp:ListItem>Home</asp:ListItem>
                    <asp:ListItem>Work</asp:ListItem>
                </asp:RadioButtonList>
            </td>
        </tr>
        <tr>
            <td valign="top">Other Phone Number</td>
            <td>
                <asp:TextBox ID="txtPhoneOther" CssClass="phoneUS" Width="100px" runat="server" />
                <br />
                <asp:RadioButtonList ID="rblPhoneOther" RepeatDirection="Horizontal" runat="server">
                    <asp:ListItem Selected="True">Cell</asp:ListItem>
                    <asp:ListItem>Home</asp:ListItem>
                    <asp:ListItem>Work</asp:ListItem>
                </asp:RadioButtonList>
            </td>
        </tr>
        <tr>
            <td valign="top">KPC Affiliation <span style="font-weight:normal;">(Check all that apply)</span></td>
            <td>
                <asp:CheckBoxList ID="cblAffiliation" CssClass="affiliation" RepeatLayout="Flow" runat="server">
                    <asp:ListItem>Alumni</asp:ListItem>
                    <asp:ListItem>Faculty</asp:ListItem>
                    <asp:ListItem>Staff</asp:ListItem>
                    <asp:ListItem>Student</asp:ListItem>
                    <asp:ListItem>Parent of KPC Student</asp:ListItem>
                    <asp:ListItem>Retiree</asp:ListItem>
                    <asp:ListItem>Friend of KPC</asp:ListItem>
                </asp:CheckBoxList>
            </td>
        </tr>
        <tr>
            <td>If you're an alum, year(s) degree(s) was/were received</td>
            <td><asp:TextBox ID="txtAlumDegreeYears" TextMode="MultiLine" Width="250px" Rows="4" runat="server" /></td>
        </tr>
        <tr>
            <td>Your employer</td>
            <td><asp:TextBox ID="txtYourEmployer" Width="250px" runat="server" /></td>
        </tr>
        <tr>
            <td>Position/Title</td>
            <td><asp:TextBox ID="txtPositionTitle" Width="250px" runat="server" /></td>
        </tr>
        <tr>
            <td valign="top">I work for a matching gift company</td>
            <td>
                <asp:DropDownList ID="ddlMatch" runat="server">
                    <asp:ListItem value="No">No</asp:ListItem>
                    <asp:ListItem Value="Yes">Yes, the name of my company is:</asp:ListItem>
                </asp:DropDownList>

                <div id="pnlMatchingGift">

                    <br />

                    <asp:TextBox ID="txtMatchingCompany" Width="250px" runat="server" />
                            
                    <br />
                    <br />

                    Please mail your matching gift form to:

                    <br />
                    <br />

                    Kenai Peninsula College<br />
                    c/o Advancement Office<br />
                    156 College Rd.<br />
                    Soldotna, AK 99669

                </div>
            </td>
        </tr>
        <tr>
            <td class="amount">Amount</td>
            <td class="amount" style="padding:20px 20px 20px 0;">
                <span class="amount">$</span><asp:TextBox ID="txtAmount" CssClass="required digits" runat="server"></asp:TextBox>
            </td>
        </tr>
    </table>

    <br />

    <h1 class="heading">Please tell us about your gift</h1>

    <table class="formTbl" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="width:360px;">Where would you like us to direct your gift?</td>
            <td>
                <asp:DropDownList ID="ddlGiftDir" runat="server">
                    <asp:ListItem Value="20768" Selected="True">KPC - Area of Greatest Need</asp:ListItem>
                    <asp:ListItem Value="momentum">Annual Momentum Campaign</asp:ListItem>
                    <asp:ListItem Value="21058">Anchorage Extension Site Support</asp:ListItem>
                    <asp:ListItem Value="60684">Kachemak Bay Campus Spendable</asp:ListItem>
                    <asp:ListItem Value="20768">Kenai River Campus</asp:ListItem>
                    <asp:ListItem Value="21057">Resurrection Bay Extension Site Support</asp:ListItem>
                    <asp:ListItem Value="20287">Kenai Peninsula College - Student Scholarship Fund</asp:ListItem>
                    <asp:ListItem Value="20426">Kachemak Bay Campus - Student Scholarship Fund</asp:ListItem>
                    <asp:ListItem Value="20940">Kachemak Bay Writers' Conference</asp:ListItem>
                </asp:DropDownList>

                <div id="pnlMomentum" class="momentum">

                    <img src="momentum.png" alt="KPC Momentum Campaign" border="0" align="right" style="margin:0 0 0 15px;" />
                    Thank you for interest in participating in the KPC Momentum Campaign! Please be aware that this is an annual, internal giving campaign for KPC faculty, staff and College Council members. 
                    If you would like to continue, please choose where you would like to direct your Momentum Campaign gift below. Otherwise, please select another option in the list above.

                    <br />
                    <br />

                    <asp:DropDownList ID="ddlMomentum" runat="server">
                        <asp:ListItem></asp:ListItem>
                        <asp:ListItem Value="20342">KPC Faculty Scholarship</asp:ListItem>
                        <asp:ListItem Value="20343">KPC Staff Scholarship</asp:ListItem>
                        <asp:ListItem Value="21115">KPC College Council Scholarship</asp:ListItem>
                        <asp:ListItem Value="20768">Kenai River Campus Support</asp:ListItem>
                        <asp:ListItem Value="60684">Kachemak Bay Campus Support Spendable</asp:ListItem>
                        <asp:ListItem Value="20426">Kachemak Bay Campus Scholarship</asp:ListItem>
                    </asp:DropDownList>

                    <br />
                    <br />

                    Unfortunately, we cannot set up payroll deducations via this online form. 
                    If you are interested in this possibility, please check the box below, and we will contact you to assist in the process.

                    <br />
                    <br />
                                        
                    <asp:CheckBox ID="cbPayroll" runat="server" /> Contact me about setting up or renewing a payroll deduction.

                    <div style="clear:both;"></div>

                </div>

            </td>
        </tr>
        <tr>
            <td valign="top">Additional information or specifics about your gift. <br /><span style="font-weight:normal;">(If you would like to donate to a location other than those listed above, please specify here.)</span></td>
            <td><asp:TextBox ID="txtSpecifics" TextMode="MultiLine" Rows="6" Width="350px" runat="server" /></td>
        </tr>
        <tr>
            <td valign="top">This gift will be on a business credit card</td>
            <td>
                <asp:DropDownList ID="ddlBusinessCredit" runat="server">
                	<asp:ListItem Value="No" Selected="True">No</asp:ListItem>
                    <asp:ListItem Value="Yes">Yes, and I have the credit card name and billing address</asp:ListItem>
                </asp:DropDownList>

                <div id="pnlBusinessCredit">

                    <br />

                    Name on credit card<br />
                    <asp:TextBox ID="txtBusinessCreditCompanyName" Width="250px" runat="server" />

                    <br />
                    <br />

                    Billing address of credit card<br />
                    <asp:TextBox ID="txtBusinessCreditBilling" TextMode="MultiLine" Rows="6" Width="350px" runat="server" />

                </div>
            </td>
        </tr>
        <tr>
            <td>This gift is in HONOR/MEMORY of</td>
            <td>
                <asp:TextBox ID="txtHonor" runat="server" /> (In Honor of James Smith)
            </td>
        </tr>
    </table>

    <br />

    <h1 class="heading">We want to stay in touch with you</h1>

    <table class="formTbl" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="width:360px;"></td>
            <td>
                <asp:CheckBox ID="cbEstate" runat="server" /> Please contact me about including KPC in my estate plans.

                <br />

                <asp:CheckBox ID="cbNewsletter" runat="server" /> Please subscribe me to the KPC daily e-mail newsletter, the KPCWord.
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                We want to hear from you. Please share why you give to KPC.

                <br />
                <br />

                <asp:TextBox ID="txtHearFrom" TextMode="MultiLine" Rows="6" Width="350px" runat="server" />
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <a href="#" id="button" name="b" class="button">
                    <span class="r">
                        <span class="l">
                            Continue to step 2 of 2 <img src="arrow.png" border="0" alt="arrow" />
                        </span>
                    </span>
                </a>
            </td>
        </tr>
    </table>

    <div id="pnlModal" style="display:none;">

        <div id="modalBkgrd"></div>

        <div id="stepTwo">
            
            <div class="alert" style="margin-top:25px;">

                <img src="uaLogo.png" style="float:left;" />
                You are about to leave the Kenai Peninsula College Giving website to make a secure payment via The University of Alaska Foundation's secure payment system. 
                If you have any questions or concerns about your gift, email <a href="mailto:give2kpc@kpc.alaska.edu">give2kpc@kpc.alaska.edu</a> or call (907) 262-0320.
                
                <div style="clear:both;"></div>

                <br />
                
                <div style="display:block;text-align:center;">

                    <a id="btnBack" href="#b" class="btn"><span>Back</span></a>
                    <asp:LinkButton ID="btnNext" PostBackUrl="#" OnClick="btnNext_Click" CssClass="btn" runat="server"><span>Continue to Step 2 of 2: Payment Information</span></asp:LinkButton>

                    <div style="clear:both;"></div>
                </div>

                <div style="clear:both;"></div>

            </div>

        </div>

        <div id="loading"></div>
                                
    </div>

    <asp:Panel ID="pnlSeal" CssClass="seal" runat="server"><script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=ssl.kpc.alaska.edu&amp;size=S&amp;lang=en"></script></asp:Panel>

</asp:Content>
<asp:Content ID="Content4" ContentPlaceHolderID="ContentPlaceHolder4" Runat="Server">
</asp:Content>

