<%@ Page Language="C#" AutoEventWireup="true" CodeFile="default.aspx.cs" Inherits="hp_default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
<title>KPC Historical Photo Repository</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>

    <script src="util/js/highlight.js" type="text/javascript"></script>
    <link href="util/css/styles.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">

        function ImgError(src) {
            src.src = 'img/noImage.png';
            src.onerror = '';
            return true;
        }
        
        function ImgErrorMed(src) {
            src.src = 'img/noImageMed.png';
            src.onerror = '';
            return true;
        }

        function ImgErrorLg(src) {
            src.src = 'img/noImageLg.png';
            src.onerror = '';
            return true;
        }

        function ClickDetails() {
            document.getElementById('<%= btnDetail.ClientID %>').click();
        }

        $(document).ready(function () {

            <%=highlightText()%>
            
            $('img#wat').click(function () {

                $('#enlarge').css('visibility', 'visible');
                $('#over').css('visibility', 'visible');

            });

            $('img#wat').hover(function () {

                $(this).css('border', '2px solid #ccc');

            },
            function () {

                $(this).css('border', '2px solid #fff');

            });

            $('#over, #close').click(function () {

                $('#enlarge').css('visibility', 'hidden');
                $('#over').css('visibility', 'hidden');

            });
        });
    
    </script>

</head>
<body>
<form id="form1" runat="server">

    <div class="container">
        
        <div class="header">

            <div class="logo">
                <a href="/hp"><img src="img/kpcLogo.png" border="0" alt="Kenai Peninsula College" /></a>
            </div>
                
            <h1 class="title"><a href="/hp">Kenai Peninsula Historical Photo Repository</a></h1>

            <div id="shadow"></div>

        </div>
            
        <div id="body" class="group">

            <asp:Panel ID="pnlDetail" Visible="false" runat="server">
                
                <asp:Literal ID="litDetail" runat="server" />
            
            </asp:Panel>

            <asp:Panel ID="pnlHome" runat="server">

                <div id="photoBtns" class="col photo">
                    
                    <div class="inner">
                
                        <img src="img/kenaiPenPhoto.jpg" height="420" width="360" oncontextmenu="return false;" border="0" />

                        <br />
                        <br />

                        <h2>Use Agreement and Restrictions</h2>
                        <br />
	                
                        <div style="background-color:#f3f1d9;padding:15px;">
                        
                            All of the photographs in this collection are for personal, educational, or research use only. These images cannot be used for political, commercial, or advertising purposes.  
	                        <br />
                            <br />
                            At the present time the college does not have the capabilities to reproduce prints from the image scans.

                            <br />
                            <br />

                            <asp:Button ID="btnAgree" Text="I Agree (Enter the Site)" style="width:330px;height:45px;" onclick="btnAgree_Click" runat="server" />

                        </div>

                        <br />

                        <h2>Documents</h2>
                        <br />

                        <ul id="docs">
                            <li><a href="doc/Central Kenai Peninsula Historic Photograph Collection.pdf">Central Kenai Peninsula Historic Photograph Collection</a></li>
                            <li><a href="doc/Kenai Peninsula Timeline.pdf">Kenai Peninsula Timeline</a></li>
                        </ul>

                    </div>
                
                </div>
            
                <div id="welcome" class="col welcome">

                    <div class="inner">
                
                        <h2>Purpose</h2>
                        <br />

	                    The purpose of the Central Kenai Peninsula Photo Collection is make available to the general public, educators, and researchers images which document the emergence of the communities of Soldotna, Kenai, Nikiski, Sterling, Ninilchik, Kasilof, and Clam Gulch, Alaska during the mid to late 20th century.  Many of the images will prove nostalgic to long-term residents, taking them back to an earlier time. But nostalgia is not the sole purpose of this collection. Through the photo record residents and others can understand the trajectory of change that led to the current state of affairs. Roads, schools, churches, were built, businesses were started, clubs, organizations, and polities were formed, and what emerged were the Alaskan towns of today.  Behind the infrastructure and organizations are decisions people made either individually, as families, or in groups. Some were good decisions, some not so good, and some bad.  One way or another, we are the collective of the decisions made in the recent history of this part of Alaska.

                        <br />
                        <br />
                        <h2>History of the Project</h2>
                        <br />

	                    This collection of photographs began in 1998 when Sterling homesteader Virgil Dahler passed away and a relative donated his photographs to Marge mullen. mullen enlisted the help of Jean Brockel and together they examined the photographs which proved to be a rich portrayal of the Soldotna area in the 1950s, 60s, and 70s. As mullen and Brockel worked in the Anthropology Lab at Kenai Peninsula College, it became apparent that the present time would be a critical window to create a collection of Kenai Peninsula photographs focusing on the mid-twentieth century while individuals who could identify people, places, and events were still alive. Many photo collections have already been lost because heirs have neither the knowledge nor the inclination to archive the photographs and they are often disposed of or dispersed.  Other collections were solicited such as those of Celeste Egan, Dick Mumsen, and Marge mullen herself. When some of the photo files of the defunct Cheechako News became available and the project that became this on-line photo archive was born.
                        <br />
                        <br />
                        The photos were archived placing them in acid-free envelopes, giving each an accession number. The originals are now stored in a fireproof cabinet in an archival room at the Anthropology Lab at Kenai Peninsula College with additional protection from the  college’s fire-suppressant system.
                        <br />
                        <br />
                        Alan Boraas developed a Microsoft Access database that included information such as who was in the picture, where it was taken, what type of image it was (slide, Polaroid, black and white print, etc.) and other pertinent information. Relying on her vast memory, Marge mullen entered almost all the information into the database frequently consulting with others of her generation to identify a building or a name from fifty years ago. 
	                    <br />
                        <br />
                        The collection quickly grew to over 1000 photos and another component of the project was added to digitize the photographs.  Marge Mullen scanned all of the photos into digital files which are stored and backed up on Anthropology Lab computers. Not only was this a form of photo backup should something happen to the originals, but it opened the possibility of creating a searchable Internet database. 
	                    <br />
                        <br />
                        In 2009 and 2010 Clark Fair edited the Access database correcting the inevitable accessioning errors that occur in so complicated a project and added information to some of the descriptions. Fair and Boraas also scanned additional photographs and created descriptive entries. Kenai Peninsula College student assistant Kluane Pootjes aided in this work.
	                    <br />
                        <br />
                        In 2010 Kenai Peninsula College installed software that made the online publication of the Central Kenai Peninsula Photograph Collection as a searchable database possible.  Jesse Glaves developed the website and the Access database and digital photo scans were imported into the website. 
                        <br />
                        <br />
                        The intent of the collection is to provide residents and non-residents free, searchable access to the photos and descriptions for personal, educational, and research purposes. (See use restrictions to the left).

                        <br />
                        <br />
                        <h2>Photo Removal and Editing</h2>
                        <br />
	                
                        If you detect an error in a photo description or other information contact Alan Boraas at ifasb@uaa.alaska.edu. Please identify the photo accession number and give as detailed a correction as possible.  Where there is conflicting information, Boraas reserves the right to determine the accuracy
                        <br />
                        <br />
                        If you object to a photograph of yourself on this website contact Alan Boraas, ifasb@uaa.alaska.edu. Identify the photo accession number (KPC-PC-xxx) with your request.

                        <br />
                        <br />
                        <h2>Contribute Photos</h2>
                        <br />

                        In development.
                    </div>

                </div>
            
            </asp:Panel>

            <asp:Panel ID="pnlFilter" Visible="false" DefaultButton="btnSearch" runat="server">

                <div class="col left">
                    
                    <div class="inner">

                        Search Filter(s)
                        <br />
                        <br />
                        <asp:TextBox ID="txtKeyword" style="margin:0 0 5px 0;padding:3px;width:147px;border:1px solid #ccc;" runat="server" />
                        <asp:Button ID="btnSearch" Text="Search" runat="server" onclick="btnSearch_Click" />
                        <asp:Button ID="btnClear" Text="Clear" runat="server" onclick="btnClear_Click" />

                    </div>

                </div>

            </asp:Panel>

            <asp:Panel ID="pnlSearch" Visible="false" runat="server">
	            
            <div class="col right">
                    
                <div class="inner">
                        
                    <asp:Literal ID="litPaging" runat="server" />

                    <div class="clear"></div>

                    <asp:Literal ID="litSearchInfo" runat="server" />

                    <asp:Literal ID="litResults" runat="server" />

                    <div class="clear"></div>

                    <asp:Literal ID="litPagingBtm" runat="server" />

                    <div style="clear:both;"></div>

                    <br />
                    <br />
                    
                    <h3>Use Agreement and Restrictions</h3>
                    <br />
                        
                    All of the photographs in this collection are for personal, educational, or research use only. These images cannot be used for political, commercial, or advertising purposes.  
	                
                    <br />
                    <br />
                    
                    At the present time the college does not have the capabilities to reproduce prints from the image scans.

                    <br />
                    <br />

                    For technical inquiries, or to report errors, please contact <a href="mailto:webmaster@kpc.alaska.edu">webmaster@kpc.alaska.edu</a>.

                </div>

            </div>

            </asp:Panel>
		
        </div>
        
    </div>

    <div style="display:block;height:1px;width:1px;overflow:hidden;"><asp:Button ID="btnDetail" onclick="btnDetail_Click" runat="server" /></div>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-15692794-3']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>

</form>
</body>
</html>
