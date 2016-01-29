using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Data;
using System.Data.SqlClient;
using System.Text;
using System.Text.RegularExpressions;

public class Photo
{
    public int id { get; set; }
    public string ident { get; set; }
    public string coll { get; set; }
    public string desc { get; set; }
    public string indiv { get; set; }
    public string loc { get; set; }
    public string date { get; set; }
    public string photog { get; set; }
    public string imgType { get; set; }
    public string dimensions { get; set; }
    public string cond { get; set; }
    public string identified { get; set; }
    public DateTime catDate { get; set; }
    public string archLoc { get; set; }
    public string remarks { get; set; }
    public string photoID { get; set; }
    public string comb { get; set; }
}

public partial class hp_default : System.Web.UI.Page
{
    DataTable dt = new DataTable();

    protected void Page_Load(object sender, EventArgs e)
    {
        GetResults();
    }

    protected void btnSearch_Click(object sender, EventArgs e)
    {
        Response.Redirect(BaseSiteUrl() + "?key=" + txtKeyword.Text);
    }

    protected void btnClear_Click(object sender, EventArgs e)
    {
        Response.Redirect(BaseSiteUrl());
    }

    protected void btnAgree_Click(object sender, EventArgs e)
    {
        Session["agreed"] = "true";

        if (Request.QueryString["pg"] != null || Request.QueryString["key"] != null || Request.QueryString["id"] != null)
        {
            string pg = Request.QueryString["pg"];
            string key = Request.QueryString["key"];
            string id = Request.QueryString["id"];

            StringBuilder sb = new StringBuilder();

            if (key != null)
            {
                sb.Append("?key=" + key);

                if (pg != null)
                {
                    sb.Append("&pg=" + pg);
                }
            }
            else if (pg != null)
            {
                sb.Append("?pg=" + pg);
            }
            else if (id != null)
            {
                sb.Append("?id=" + id);
            }
            else
            {
                sb.Append("?pg=1");
            }


            Response.Redirect("./default.aspx" + sb.ToString());
        }
        else
        {
            Response.Redirect("./default.aspx?pg=1");
        }
    }

    protected void btnDetail_Click(object sender, EventArgs e)
    {
        GetDetailPage();
    }

    private DataTable GetDataTable()
    {
        SqlConnection conn = new SqlConnection(System.Web.Configuration.WebConfigurationManager.ConnectionStrings["photoConn"].ConnectionString);
        SqlDataAdapter da = new SqlDataAdapter("select * from dbo.histview", conn);

        da.Fill(dt);
        dt.TableName = "photos";

        return dt;
    }

    private List<Photo> GetPhotoList()
    {
        DataView dv = new DataView(GetDataTable());
        List<Photo> ph = new List<Photo>();

        string key = Request.QueryString["key"];

        dv.RowFilter = "comb like '%" + key + "%'";

        foreach (DataRowView row in dv)
        {
            ph.Add(
                new Photo
                {
                    id = Convert.ToInt32(row[0].ToString()),
                    ident = row[1].ToString(),
                    coll = row[2].ToString(),
                    desc = row[3].ToString(),
                    indiv = row[4].ToString(),
                    loc = row[5].ToString(),
                    date = row[6].ToString(),
                    photog = row[7].ToString(),
                    imgType = row[8].ToString(),
                    dimensions = row[9].ToString(),
                    cond = row[10].ToString(),
                    identified = row[11].ToString(),
                    catDate = Convert.ToDateTime(row[12]),
                    archLoc = row[13].ToString(),
                    remarks = row[14].ToString(),
                    photoID = row[15].ToString(),
                    comb = row[16].ToString()
                }
            );
        }

        return ph;
    }

    private List<Photo> GetPhotos(int? page, int? pageSize)
    {
        // Default to Page 1 if no page is supplied
        int _page = (page.HasValue) ? page.Value : 1;

        List<Photo> lst = GetPhotoList();

        int _pageSize = (pageSize.HasValue) ? pageSize.Value : lst.Count();

        double count = lst.Count();
        double ps = Convert.ToDouble(pageSize);
        double totalPages = Math.Ceiling(count/ps);
        int pageCount = Convert.ToInt32(totalPages);
        StringBuilder sb = new StringBuilder();

        if (Request.QueryString["key"] != null)
        {
            litSearchInfo.Text = "<br /><div class=\"searchInfo\">Your search for <strong><em>" + Request.QueryString["key"] + "</em></strong> returned " + count.ToString() + " results.</div><div style=\"clear:both;\"></div><br />";
        }
        else
        {
            litSearchInfo.Text = "<br />";
        }

        sb.Append("<ul id=\"paging\">");

        for (int i = 1; i <= pageCount; i++)
        {
            if (i == page)
            {
                sb.Append("<li class=\"selected\"><a href=\"#\">" + i + "</a></li>");
            }
            else
            {
                if (Request.QueryString["key"] != null)
                {
                    string[] urlArray = Request.RawUrl.ToString().Split('&');
                    string url = urlArray[0].ToString();

                    sb.Append("<li><a href=\"" + url + "&pg=" + i + "\">" + i + "</a></li>");
                }
                else
                {
                    sb.Append("<li><a href=\"" + BaseSiteUrl() + "?pg=" + i + "\">" + i + "</a></li>");
                }
            }
        }

        sb.Append("</ul>");

        litPaging.Text = sb.ToString();
        litPagingBtm.Text = sb.ToString();

        return lst.Skip((_page - 1) * _pageSize).Take(_pageSize).ToList();
            
    }

    private void GetResults()
    {
        if (Session["agreed"] == "true")
        {
            if (Request.QueryString["id"] != null)
            {

                pnlDetail.Visible = true;
                pnlHome.Visible = false;
                pnlSearch.Visible = false;
                pnlFilter.Visible = true;

                DataView dv = new DataView(GetDataTable());
                List<Photo> photos = new List<Photo>();
                StringBuilder sb = new StringBuilder();

                dv.RowFilter = "ident like '%" + Request.QueryString["id"] + "%'";

                foreach (DataRowView row in dv)
                {
                    photos.Add(
                        new Photo
                        {
                            id = Convert.ToInt32(row[0].ToString()),
                            ident = row[1].ToString(),
                            coll = row[2].ToString(),
                            desc = row[3].ToString(),
                            indiv = row[4].ToString(),
                            loc = row[5].ToString(),
                            date = row[6].ToString(),
                            photog = row[7].ToString(),
                            imgType = row[8].ToString(),
                            dimensions = row[9].ToString(),
                            cond = row[10].ToString(),
                            identified = row[11].ToString(),
                            catDate = Convert.ToDateTime(row[12]),
                            archLoc = row[13].ToString(),
                            remarks = row[14].ToString(),
                            photoID = row[15].ToString()
                        }
                    );
                }

                foreach (Photo ph in photos)
                {
                    string time = ph.catDate.ToString("D");

                    sb.Append("<div id=\"over\" style=\"visibility:hidden;\">");
                    sb.Append("<div id=\"enlarge\"><div id=\"close\">Close X</div><img src=\"ph/lg/" + ph.ident.Replace("KPCPC", "KPC-PC-") + ".jpg\" onerror=\"ImgErrorLg(this);\" oncontextmenu=\"return false;\" /></div>");
                    sb.Append("</div>");
                    sb.Append("<div id=\"details\" class=\"col detail\">");
                    sb.Append("<div class=\"inner\">");
                    sb.Append("<div id=\"imageM\" style=\"position:relative;\"><a href=\"javascript:history.go(-1)\" style=\"text-decoration:none;\"><< Back</a><div style=\"float:right;max-width:470px;text-align:center;\"><img id=\"wat\" src=\"ph/m/" + ph.ident.Replace("KPCPC", "KPC-PC-") + ".jpg\" onerror=\"ImgErrorMed(this);\" style=\"margin:40px 0 10px 20px;border:2px solid #fff;\" oncontextmenu=\"return false;\" /><div style=\"width:140px;padding:3px;margin:auto;background-color:#eee;text-align:center;font-size:11px;-moz-border-radius: 15px;border-radius: 15px;\">Click image to enlarge</div></div></div>");
                    sb.Append("<div class=\"data\"><br /><h2>" + CheckTitle(ph.desc) + "</h2><br />" + CheckDesc(ph.remarks) + "<br /><br /><h3>Individuals</h3>" + CheckNull(ph.indiv) + "<br /><h3>Collection</h3>" + CheckNull(ph.coll) + "<br /><h3>Location</h3>" + CheckNull(ph.loc) + "<br /><h3>Date</h3>" + CheckNull(ph.date) + "<br /><h3>Photographer</h3>" + CheckNull(ph.photog) + "<br /><h3>Image Type</h3>" + CheckNull(ph.imgType) + "<br /><h3>Dimensions</h3>" + CheckNull(ph.dimensions) + "<br /><h3>Condition</h3>" + CheckNull(ph.cond) + "<br /><h3>Identified By</h3>" + CheckNull(ph.identified) + "<br /><h3>Catalogue Date</h3>" + CheckNull(time) + "<br /><h3>Archive Location</h3>" + CheckNull(ph.archLoc) + "</div>");
                    sb.Append("<div style=\"clear:both;\"></div><br /><br /><h3>Use Agreement ansd Restrictions</h3><br />All of the photographs in this collection are for personal, educational, or research use only. These images cannot be used for political, commercial, or advertising purposes.<br /><br />At the present time the college does not have the capabilities to reproduce prints from the image scans.<br /><br />For technical inquiries, or to report errors, please contact <a href=\"mailto:webmaster@kpc.alaska.edu\">webmaster@kpc.alaska.edu</a>.");
                    sb.Append("</div>");
                    sb.Append("</div>");
                }

                litDetail.Text = sb.ToString();

            }
            else
            {
                pnlHome.Visible = false;
                pnlSearch.Visible = true;
                pnlFilter.Visible = true;

                int pg = Convert.ToInt32(Request.QueryString["pg"]);

                List<Photo> photos = GetPhotos(pg, 30);
                StringBuilder sb = new StringBuilder();

                sb.Append("<ul id=\"photos\">");

                foreach (Photo ph in photos)
                {
                    string pho;
                    string dat;

                    if (ph.photog != "")
                    {
                        pho = "By: " + ph.photog;
                    }
                    else
                    {
                        pho = "";
                    }

                    if (ph.date != "")
                    {
                        dat = "Date: " + ph.date;
                    }
                    else
                    {
                        dat = "";
                    }

                    sb.Append("<li><div class=\"img\"><a href=\"" + BaseSiteUrl() + "?id=" + ph.ident.Replace("KPCPC", "") + "" + GetTerms() + "\" onclick=\"javascript:ClickDetails()\"><img src=\"ph/th/" + ph.ident.Replace("KPCPC", "KPC-PC-") + ".jpg\" onerror=\"ImgError(this);\" oncontextmenu=\"return false;\" border=\"0\" /></a></div><div class=\"caption\">ID: " + ph.ident.Replace("KPCPC", "") + "<br /><div style=\"height:12px;overflow:hidden;color:#aaa;font-size:10px;\">" + pho + "</div><div style=\"height:12px;overflow:hidden;color:#aaa;font-size:10px;\">" + dat + "</div></div></li>");
                }

                sb.Append("</ul>");

                litResults.Text = sb.ToString();
            }
        }
    }

    private string BaseSiteUrl()
    {
        HttpContext context = HttpContext.Current;
        string baseUrl = context.Request.Url.Scheme + "://" + context.Request.Url.Authority + context.Request.ApplicationPath + "hp/default.aspx";
        return baseUrl;
    }

    private void GetDetailPage()
    {
        Response.Redirect("./default.aspx?id=" + Request.QueryString["id"]);
    }

    private string CheckNull(string text)
    {
        if (text == "" || text == "NULL")
        {
            return "n/a";
        }
        else
        {
            return text;
        }
    }

    private string CheckTitle(string text)
    {
        if (text == "" || text == "NULL")
        {
            return "Title Unavailable";
        }
        else
        {
            return text;
        }
    }

    private string CheckDesc(string text)
    {
        if (text == "" || text == "NULL")
        {
            return "Description Unavailable";
        }
        else
        {
            return text;
        }
    }

    protected string highlightText()
    {
        string term = Request.QueryString["term"];

        if (term != null)
        {
            return "$('div.data').highlight('" + term.ToLower() + "');";
        }
        else
        {
            return "";
        }
    }

    private string GetTerms()
    {
        string key = Request.QueryString["key"];

        if (key != null)
        {
            return "&term=" + Request.QueryString["key"].ToLower();
        }
        else
        {
            return "";
        }
    }
}