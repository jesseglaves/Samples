<%@ Page Language="C#" MasterPageFile="~/templates/master/interior.master" AutoEventWireup="true" CodeFile="coursesearch.aspx.cs" Inherits="templates_coursesearch" Title="Untitled Page" %>
<%@ Register Assembly="Ektron.Cms.Controls" Namespace="Ektron.Cms.Controls" TagPrefix="CMS" %>
<%@ Register Assembly="System.Web.Extensions, Version=3.5.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35" Namespace="System.Web.UI.WebControls" TagPrefix="asp" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">

<meta http-equiv="Page-Enter" content="blendTrans(Duration=0.01)" />

<link href="/stylesheets/searchlayout.css" rel="stylesheet" type="text/css" />
<link href="/stylesheets/styles.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    #menuside
    {
        margin-bottom: -40000px;
        padding-bottom: 40000px;
    }
    
    #middlerightcontent,
    #middlecontent
    {
    	background-image:none;
    	background-color:#5a6caa;
    }
    
    #middletext
    {
    	color:#fff;
    	font-size:14px;
    }
    
    .searchInfo
    {
    	font-size:12px;
    	padding:8px;
    	margin: 15px 0;
    	color:#F2DE6D;
        border-right:1px solid #9dadde;
        border-top:1px solid #3f5290;
        border-left:1px solid #3f5290;
        border-bottom:1px solid #9dadde;
    }
    
    .searchInfo ul li
    {
    	font-size:12px;
    	color:#F2DE6D;
    }
    
    .footerNote
    {
    	font-size:11px;
    	text-align:center;
    	padding:8px 0 0 0;
    }
    
    #resultTable td
    {
    	vertical-align:top;
    }
    
    #resultTable ul li
    {
    	font-size:12px;
    }
    
    .startTime
    {
    	float:left;
    	width:50%;
    	overflow:hidden;
    }
    
    .endTime
    {
    	float:right;
    	width:50%;
    	overflow:hidden;
    }
    
</style>

<script type="text/javascript">

    $(document).ready(function () {

        $('#menuside').removeClass('hide');

        $('#resultTable tr').hover(
        function () { $(this).children("td").addClass('hover'); },
        function () {
            $(this).children("td").removeClass('hover');
        });

        $('h1.bluebar').html("K<span style=\"font-size:18px;\">ENAI</span> P<span style=\"font-size:18px;\">ENINSULA</span> C<span style=\"font-size:18px;\">OLLEGE</span>");
        $('#logo').html("<a href=\"/\"><img title=\"KPC Logo\" border=\"0\" alt=\"KPC Logo\" src=\"http://www.kpc.alaska.edu/uploadedImages/KPC_Logo.png\" style=\"background-color:none;\"></a>");

        $('ul#secondaryMenu').remove();

    });

</script>

</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">

    <asp:MultiView ID="mv" runat="server">
    
        <asp:View ID="homeView"  runat="server">

            <CMS:ContentBlock ID="cbMainContent" runat="server" DynamicParameter="id" CacheInterval="600" />

        </asp:View>

        <asp:View ID="resultsView" runat="server">
    
            <h1>Search Results</h1>
    
            This schedule is a new feature designed for the convenience of students but there are a few glitches. 
            TAKE CARE to compare all information seen here to that on UAOnline—the definitive course information accessed when registering. 
            AFTER registering, double check the list of courses to be certain the resulting schedule reads as expected.
    
            <br />

            <asp:Literal ID="litResults" runat="server" />
    
        </asp:View>

        <asp:View ID="advView" runat="server">

        <script type="text/javascript">

            $(document).ready(function () {

                $('#menuside').hide();
                $('#middlerightcontent').css('width', '100%');

            });
        
        </script>
    
            <h1>Advanced Course Search</h1>

            The Advanced Search allows you to search courses offered across the Kenai Peninsula College System based on a 
            range of course details. Use the form below to submit a query for course information. It is possible, but not 
            necessary, to select more than one option within each select box. To do this hold down the CTRL key if you are 
            using a PC or the CMD key if you are on a Mac, and click the options you want to select. Note that specifying 
            MORE detail will narrow your results.
    
        </asp:View>

        <asp:View ID="detailsView" runat="server">
        
            <h1>Course Details</h1>

        </asp:View>

        

    </asp:MultiView>

    <br />

    <div class="footerNote">
        * Course titles that start with an asterisk satisfy a General Education Requirement (GER) for UAA.
    </div>

</asp:Content>

<asp:Content ID="content3" ContentPlaceHolderID="ContentPlaceHolder4" runat="server">

    <div class="filterBox">
        <span>FREE TEXT SEARCH:</span>
        <br />
        <asp:TextBox ID="txtFreeText" runat="server"></asp:TextBox>
    </div>

    <div class="filterBox">
        <span>SUBJECT:</span>
        <br />
        <asp:DropDownList ID="ddlSubject" runat="server" AppendDataBoundItems="True">
            <asp:ListItem Text="Any" Value="" />
        </asp:DropDownList>
    </div>

    <div class="filterBox">
        <span>LOCATION:</span>
        <br />
        <asp:DropDownList ID="ddlLocation" runat="server">
            <asp:ListItem Text="Any" Value="" />
            <asp:ListItem Text="Kenai River Campus" Value="I" />
            <asp:ListItem Text="Kachemak Bay Campus" Value="R" />
            <asp:ListItem Text="Resurrection Bay Extension Site" Value="S" />
            <asp:ListItem Text="Anchorage Extension Site" Value="A" />    
            <asp:ListItem Text="Online" Value="Online" />
        </asp:DropDownList>
    </div>

    <div class="filterBox">
        <span>SEMESTER:</span>
        <br />
        <%--<asp:DropDownList ID="ddlSemester" runat="server" DataSourceID="xdsTerms" DataTextField="text" DataValueField="code" />--%>
        <asp:DropDownList ID="ddlSemester" runat="server" />
    </div>

    <div class="filterBox">
        <span>COURSE LEVEL:</span>
        <br />
        <asp:DropDownList ID="ddlLevel" runat="server">
            <asp:ListItem Text="Any" Value="" />
            <asp:ListItem Text="000" Value="0" />
            <asp:ListItem Text="100" Value="1" />
            <asp:ListItem Text="200" Value="2" />
            <asp:ListItem Text="300" Value="3" />
            <asp:ListItem Text="400" Value="4" />
            <asp:ListItem Text="500" Value="5" />
            <asp:ListItem Text="600" Value="6" />
        </asp:DropDownList>
    </div>

    <div class="filterBox" style="border:none;">
        <%--<asp:Button ID="btnSubmit" runat="server" Text="Search" OnClick="btnSubmit_Click" />--%>
        <asp:Button ID="btnSubmit" runat="server" Text="Search" OnClick="btnSubmit_Click" />
        <asp:Button ID="btnClear" runat="server" Text="Clear" OnClick="btnClear_Click" />
        <br />
        <br />
        <%--<asp:LinkButton ID="btnAdvancedSearch" runat="server" CssClass="searchLink" onclick="btnAdvancedSearch_Click">ADVANCED SEARCH</asp:LinkButton>--%>
        <asp:LinkButton ID="btnAdvanced" runat="server" CssClass="searchLink" OnClick="btnAdvanced_Click">ADVANCED SEARCH</asp:LinkButton>
    </div>

</asp:Content>
