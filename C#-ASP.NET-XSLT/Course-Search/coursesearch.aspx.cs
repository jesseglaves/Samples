using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Data;
using System.Data.SqlClient;
using System.Text;

public partial class templates_coursesearch : System.Web.UI.Page
{
    DataTable dt = new DataTable();

    protected void Page_Load(object sender, EventArgs e)
    {
        if (!Page.IsPostBack)
        {
            PopulateSemester();
            PopulateSubject();
        }

        this.Form.DefaultButton = btnSubmit.UniqueID;
        mv.SetActiveView(this.homeView);
    }

    protected void btnClear_Click(object sender, EventArgs e)
    {
        ddlLevel.SelectedIndex = 0;
        ddlLocation.SelectedIndex = 0;
        ddlSemester.SelectedIndex = 0;
        ddlSubject.SelectedIndex = 0;
        txtFreeText.Text = "";

        PopulateResults();
        mv.SetActiveView(this.homeView);
    }

    protected void btnSubmit_Click(object sender, EventArgs e)
    {
        PopulateResults();

        mv.SetActiveView(this.resultsView);
    }

    protected void btnAdvanced_Click(object sender, EventArgs e)
    {
        mv.SetActiveView(this.advView);
    }

    private void PopulateResults()
    {
        DataView dataview = new DataView(LoadDataTable());
        DataView dv = dataview.Table.DefaultView;

        dv.Sort = "sort_title, section_sort_title";

        dv.RowFilter = SearchFilter();

        if (dv.Table.Rows.Count > 0)
        {
            StringBuilder sb = new StringBuilder();

            sb.Append("<div class=\"searchInfo\">");
            sb.Append(dv.Table.DefaultView.Count + " records matched your search request for:<br />");

            sb.Append("<ul style=\"margin:5px 0 0 0;\">");

            if (txtFreeText.Text != "")
            {
                sb.Append("<li>Free Text: " + txtFreeText.Text + "</li>");
            }
            
            if (ddlSubject.SelectedValue != "")
            {
                sb.Append("<li>Subject: " + ddlSubject.SelectedItem.Text + "</li>");
            }
            
            if (ddlLocation.SelectedValue != "")
            {
                sb.Append("<li>Location: " + ddlLocation.SelectedItem.Text + "</li>");
            }

            if (ddlSemester.SelectedValue != "")
            {
                sb.Append("<li>Semester: " + ddlSemester.SelectedItem.Text + "</li>");
            }

            if (ddlLevel.SelectedValue != "")
            {
                sb.Append("<li>Level: " + ddlLevel.SelectedItem.Text + "</li>");
            }

            sb.Append("</ul>");

            sb.Append("</div>");

            sb.Append("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"resultTable\"><tr>");
            sb.Append("<th>Course Title</th>");
            sb.Append("<th>Course #</th>");
            sb.Append("<th>Sem</th>");
            sb.Append("<th>Day(s)</th>");
            sb.Append("<th>Start</th>");
            sb.Append("<th>End</th>");
            sb.Append("<th>Seats</th>");
            sb.Append("</tr>");

            foreach (DataRowView r in dv)
            {
                sb.Append("<tr><td>" + GetCourseTitle(r["course_title"].ToString(), r["section_title"].ToString()) + "</td>");
                sb.Append("<td>" + r["subject_code"].ToString() + " " + r["level"].ToString() + " " + r["section"].ToString() + "</td>");
                sb.Append("<td>" + MapSemesterCode(r["semester_code"].ToString()) + "" + FormatShortYear(r["year"].ToString()) + "</td>");
                sb.Append("<td>" + GetDays(r["sundays"].ToString(), r["mondays"].ToString(), r["tuesdays"].ToString(), r["wednesdays"].ToString(), r["thursdays"].ToString(), r["fridays"].ToString(), r["saturdays"].ToString()) + "</td>");
                sb.Append("<td colspan=\"2\">" + GetTimes(r["start_times"].ToString(), r["end_times"].ToString()) + "</td>");
                sb.Append("<td>" + r["seats_available"].ToString() + "</td>");
                sb.Append("</tr>");
                //  <%#Eval("subject_code") + " " + Eval("level") + " " + Eval("section")%>
            }

            sb.Append("</tr></table>");

            litResults.Text = sb.ToString();
            litResults.DataBind();
        }
    }

    private string GetCourseTitle(string course, string section)
    {
        if (section != "")
        {
            return section;
        }
        else
        {
            return course;
        }
    }

    private DataTable LoadDataTable()
    {
        SqlConnection conn = new SqlConnection(System.Configuration.ConfigurationManager.ConnectionStrings["KPC_Banner.DBConnection"].ToString());
        SqlDataAdapter da = new SqlDataAdapter();

        da.SelectCommand = new SqlCommand("SELECT REPLACE(course_title, '*', '') as sort_title, REPLACE(section_title, '*', '') as section_sort_title, term_code, crn, course_title, course_description, section_title, seats_available, SUBSTRING([level],2,1) as course_level, subject_code, subject, term, level, section, sundays, mondays, tuesdays, wednesdays, thursdays, fridays, saturdays, start_times, end_times, SUBSTRING([section],1,1) AS campus, SUBSTRING([TERM_code],5,2) as semester_code, SUBSTRING([TERM_code],1,4) as year FROM test WHERE term_code >= '" + DateTime.Now.Year + "" + GetCurrentTerm() + "' and term_code <= '" + GetNextTerm() + "'", conn);

        // define the connection
        using (conn)
        {
            // execute the query
            da.SelectCommand.Connection.Open();

            da.Fill(dt);
        }

        return dt;
    }

    private void PopulateSemester()
    {
        DataView dataview = new DataView(LoadDataTable());
        DataView dv = dataview;

        List<string> terms = new List<string>();

        foreach (DataRowView r in dv)
        {
            terms.Add(r["term"].ToString());   
        }

        var a = (from t in terms select t).Distinct();

        foreach (var t in a)
        {
            ddlSemester.Items.Add(new ListItem(t, t));
        }

        ddlSemester.SelectedIndex = 0;
    }

    private void PopulateSubject()
    {
        DataView dataview = new DataView(LoadDataTable());
        DataView dv = dataview;

        List<string> subjects = new List<string>();

        foreach (DataRowView r in dv)
        {
            subjects.Add(r["subject_code"].ToString() + "," + r["subject"].ToString());
        }

        var b = (from t in subjects select t).Distinct().OrderBy(i => i);

        foreach (var t in b)
        {
            string[] array = t.Split(',');
            string subjCode = array[0].ToString();
            string subj = array[1].ToString();

            ddlSubject.Items.Add(new ListItem(subjCode + " - " + subj, subjCode));
        }

        ddlSubject.SelectedIndex = 0;
    }

    protected string MapSemesterCode(string code)
    {
        string returnString = String.Empty;

        switch (code)
        {
            case "01":
                returnString = "SP";
                break;
            case "02":
                returnString = "SM";
                break;
            case "03":
                returnString = "FL";
                break;
            default:
                break;
        }

        return returnString;
    }

    protected string GetCurrentTerm()
    {
        DateTime cur = DateTime.Now;
        DateTime springStart = Convert.ToDateTime("October 08 " + cur.Year);
        DateTime springEnd = Convert.ToDateTime("January 20 " + (cur.Year + 1));
        DateTime summerStart = Convert.ToDateTime("January 21 " + cur.Year);
        DateTime summerEnd = Convert.ToDateTime("March 13 " + cur.Year);
        DateTime fallStart = Convert.ToDateTime("March 14 " + cur.Year);
        DateTime fallEnd = Convert.ToDateTime("October 07 " + cur.Year);

        if ((springStart <= cur) && (cur <= springEnd))
        {
            //It's fall, start displaying spring registration dates 30 days before registration begins
            return "03";
        }
        else if ((summerStart <= cur) && (cur <= summerEnd))
        {
            //It's spring, start displaying summer registration dates 30 days before registration begins
            return "01";
        }
        else
        {
            //It's summer, start displaying fall regisration dates 30 days before registration begins
            return "02";
        }
    }

    private string GetNextTerm()
    {
        string curTerm = GetCurrentTerm();
        int curYear = Convert.ToInt32(DateTime.Now.Year);

        switch (curTerm)
        {
            case "01":
                // It's Spring
                return curYear.ToString() + "" + "02";
            case "02":
                //It's Summer
                return curYear.ToString() + "" + "02";
            case "03":
                //It's Fall
                return (curYear + 1).ToString() + "01";
            default:
                return "%";
        }

    }

    protected string FormatShortYear(string year)
    {
        DateTime dt = Convert.ToDateTime("Jan 1, " + year);

        return dt.ToString("yy");
    }

    private string SearchFilter()
    {
        StringBuilder sb = new StringBuilder();

        sb.Append("term = '" + ddlSemester.SelectedValue + "'");

        if (txtFreeText.Text != "")
        {
            string clean = txtFreeText.Text.Replace("'", "''");

            sb.Append(" and (course_title like '%" + clean + "%' or section_title like '%" + clean + "%' or course_description like '%" + clean + "%')");
        }

        if (ddlLocation.SelectedIndex != 0)
        {
            sb.Append(CheckCampusCode(ddlLocation.SelectedValue));
        }

        if (ddlSubject.SelectedIndex != 0)
        {
            sb.Append(" and subject_code = '" + ddlSubject.SelectedValue + "'");
        }

        if (ddlLevel.SelectedIndex != 0)
        {
            sb.Append(" and course_level = '" + ddlLevel.SelectedValue + "'");
        }

        return sb.ToString();
    }

    private string CheckCampusCode(string code)
    {
        if (code == "Online")
        {
            return " and (section like '%W%' or section like '%E%')";
        }
        else
        {
            return "and section like '" + code + "%'";
        }
    }

    private string GetDays(string u,string m,string t,string w,string r,string f, string s)
    {
        StringBuilder sb = new StringBuilder();

        if (u.Contains(','))
        {
            string[] su = u.Split(',');
            string[] mo = m.Split(',');
            string[] tu = t.Split(',');
            string[] we = w.Split(',');
            string[] tr = r.Split(',');
            string[] fr = f.Split(',');
            string[] sa = s.Split(',');

            sb.Append("<ul style=\"list-style:none;margin:0;padding:0;\">");

            int count = su.Length;

            for (int i = 0; i < count; i++)
            {
                sb.Append("<li>" + su[i].Replace("0", "") + "" + mo[i].Replace("0", "") + "" + tu[i].Replace("0", "") + "" + we[i].Replace("0", "") + "" + tr[i].Replace("0", "") + "" + fr[i].Replace("0", "") + "" + sa[i].Replace("0", "") + "</li>");
            }

            sb.Append("</ul>");

            return sb.ToString();
        }
        else
        {
            return u.Replace("0", "") + "" + m.Replace("0", "") + "" + t.Replace("0", "") + "" + w.Replace("0", "") + "" + r.Replace("0", "") + "" + f.Replace("0", "") + "" + s.Replace("0", "");
        }
    }

    private string GetTimes(string start, string end)
    {
        StringBuilder sb = new StringBuilder();

        if (start.Contains(','))
        {
            string[] starts = start.Split(',');
            string[] ends = end.Split(',');

            sb.Append("<ul style=\"list-style:none;margin:0;padding:0;\">");

            int count = starts.Length;

            for (int i = 0; i < count; i++)
            {
                sb.Append("<li><div class=\"startTime\">" + FormatTime(starts[i]) + "</div><div class=\"endTime\">" + FormatTime(ends[i]) + "</li>");
            }

            sb.Append("</ul>");

            return sb.ToString();
        }
        else
        {
            return "<div class=\"startTime\">" + FormatTime(start) + "</div><div class=\"endTime\">" + FormatTime(end);
        }
    }

    protected string FormatTime(string time)
    {
        //string format = "HHmm";

        if (time != "0")
        {
            DateTime dt = DateTime.Parse("Mon, 22 Mar 2010 " + time);
            return dt.ToString("h:mmt");
        }
        else
        {
            return "";
        }
    }
}