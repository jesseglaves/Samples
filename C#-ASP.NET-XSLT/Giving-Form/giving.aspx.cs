using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Net.Mail;
using System.Net;
using System.Collections.Specialized;
using System.Text;

public partial class give_default : System.Web.UI.Page
{
    StringBuilder sb = new StringBuilder();
    string token;
    string content;

    protected void Page_Init(object sender, EventArgs e)
    {
        ((templates_master_interior)this.Master).LayoutCSS = "/stylesheets/secondarylayout2.css";
    }

    protected void Page_Load(object sender, EventArgs e)
    {
        string qs = Request.Url.Query;

        if (!Request.IsLocal && !Request.IsSecureConnection)
        {
            if (qs == null || qs == "")
            {
                Response.Redirect("https://ssl.kpc.alaska.edu/give/");
            }
            else
            {
                Response.Redirect("https://ssl.kpc.alaska.edu/give/" + qs);
            }
        }
        else if (!Request.IsLocal && Request.IsSecureConnection)
        {
            if (Request.Url.ToString().Contains("www"))
            {
                if (qs == null || qs == "")
                {
                    Response.Redirect("https://ssl.kpc.alaska.edu/give/");
                }
                else
                {
                    Response.Redirect("https://ssl.kpc.alaska.edu/give/" + qs);
                }
            }
        }

        if (Request.QueryString["EXT_TRANS_ID"] == null || Request.QueryString["EXT_TRANS_ID"] == "")
        {
            token = GenerateToken();
        }
        else
        {
            token = Request.QueryString["EXT_TRANS_ID"].ToString().ToUpper();
        }   
    }

    public string GenerateToken()
    {
        var chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        var random = new Random();
        var result = new string(
            Enumerable.Repeat(chars, 10)
                      .Select(s => s[random.Next(s.Length)])
                      .ToArray());
        return result;
    }

    protected void btnNext_Click(Object sender, EventArgs e)
    {
        try {
            ComposeMail();
        } catch { }

        try {
            SendMail("KPC Giving <wskendrick@kpc.alaska.edu>", "ua-found-webgiving@alaska.edu", "wskendrick@kpc.alaska.edu", "jlglaves@kpc.alaska.edu", "argichard@alaska.edu", "cjvoss@alaska.edu", "ljlundquist@kpc.alaska.edu", "KPC Giving Form Submission", content);
            //SendMail();
        } catch { }

        content = string.Empty;

        //string url = "https://epay.alaska.edu:8443/C21563test_upay_2/web/index.jsp";
        string url = "https://epay.alaska.edu/C21563_upay/web/index.jsp";
        NameValueCollection data = new NameValueCollection();

        data.Add("EXT_TRANS_ID", token);
        data.Add("trans", token);

        if (txtBusinessCreditCompanyName.Text == "" || txtBusinessCreditCompanyName.Text == null)
        {
            data.Add("BILL_NAME", txtFirstName.Text + " " + txtLastName.Text);
        }
        else
        {
            data.Add("BILL_NAME", txtBusinessCreditCompanyName.Text);
        }

        data.Add("BILL_EMAIL_ADDRESS", txtEmail.Text);
        data.Add("UPAY_SITE_ID", "2");
        data.Add("AMT", txtAmount.Text);
        data.Add("CANCEL_LINK", "https://ssl.kpc.alaska.edu/give/");
        data.Add("SUCCESS_LINK", "http://www.kpc.alaska.edu/give/thanks/");
        data.Add("ERROR_LINK", "https://ssl.kpc.alaska.edu/give/");

        try
        {
            kpc.HttpHelper.RedirectAndPOST(this.Page, url, data);
        }
        catch { }
    }

    protected void ComposeMail()
    {
        string tkn = token;
        string fName = txtFirstName.Text;
        string lName = txtLastName.Text;
        string pName = txtPrevName.Text;
        string recog = ddlRecognizeMeAs.SelectedItem.Text;
        string recogOther = txtRecognizeOther.Text;
        string addr1 = txtAddr1.Text;
        string addr2 = txtAddr2.Text;
        string city = txtCity.Text;
        string state = ddlState.SelectedItem.Text;
        string zip = txtZip.Text;
        string country = ddlCountry.SelectedItem.Text;
        string email = txtEmail.Text;
        string phonePri = txtPhone.Text;
        string phonePriType = rblPhone.SelectedItem.Text;
        string phoneOther = txtPhoneOther.Text;
        string phoneOtherType = rblPhoneOther.SelectedItem.Text;
        List<string> kpcAffil = new List<string>();

        foreach (ListItem l in cblAffiliation.Items)
        {
            if (l.Selected)
            {
                kpcAffil.Add(l.Text);
            }
        }

        string alumDegree = txtAlumDegreeYears.Text.Replace(Environment.NewLine, "<br />");
        string employer = txtYourEmployer.Text;
        string position = txtPositionTitle.Text;
        string matchGift = ddlMatch.SelectedItem.Value;
        string matchGiftCo = txtMatchingCompany.Text;
        string amount = txtAmount.Text;
        string dirGift = ddlGiftDir.SelectedItem.Text;
        string dirGiftCode = " (" + ddlGiftDir.SelectedItem.Value + ")";
        string momentum = ddlMomentum.SelectedItem.Text;
        string payroll = cbPayroll.Checked.ToString();
        string addInfo = txtSpecifics.Text;
        string businessCard = ddlBusinessCredit.SelectedItem.Text;
        string businessName = txtBusinessCreditCompanyName.Text;
        string businessAddr = txtBusinessCreditBilling.Text.Replace(Environment.NewLine, "<br />");
        string honorOf = txtHonor.Text;
        string estate = cbEstate.Checked.ToString();
        string newsletter = cbNewsletter.Checked.ToString();
        string whyGive = txtHearFrom.Text.Replace(Environment.NewLine, "<br />");

        sb.Append("<html>");
        sb.Append("<head><style type=\"text/css\">td,table,body {font-family:Arial;font-size:13px;color:#555;} table{border-top:1px solid #ccc;border-left:1px solid #ccc;} td {border-bottom:1px solid #ccc;border-right:1px solid #ccc;}</style></head>");
        sb.Append("<body>");
        sb.Append("The following information outlines a donation sent via the Kenai Peninsula College Giving form at https://ssl.kpc.alaska.edu/give on " + DateTime.Now.Month.ToString() + "/" + DateTime.Now.Day.ToString() + "/" + DateTime.Now.Year.ToString() + ". For more information and help explaining the origin of this message, please contact the KPC webmaster at <a href=\"mailto:webmaster@kpc.alaska.edu\">webmaster@kpc.alaska.edu</a>.<br /><br />");
        sb.Append("<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\"><tbody>");
        sb.Append("<tr><td>External Transaction ID</td><td>" + tkn.ToUpper() + "</td></tr>");
        sb.Append("<tr><td colspan=\"2\" style=\"color:#fff;background:#314689;\"><strong>Gift Information</strong></td></tr>");
        sb.Append("<tr><td>First Name</td><td>" + fName + "</td></tr>");
        sb.Append("<tr><td>Last Name</td><td>" + lName + "</td></tr>");

        if (pName != null && pName != "")
        {
            sb.Append("<tr><td>Previous Name</td><td>" + pName + "</td></tr>");
        }

        if (recog != "Other")
        {
            sb.Append("<tr><td>How would you like us to recognize you?</td><td>" + recog + "</td></tr>");
        }
        else
        {
            sb.Append("<tr><td>How would you like us to recognize you?</td><td>Other: " + recogOther + "</td></tr>");
        }

        sb.Append("<tr><td>Address Line 1</td><td>" + addr1 + "</td></tr>");

        if (addr2 != null && addr2 != "")
        {
            sb.Append("<tr><td>Address Line 2</td><td>" + addr2 + "</td></tr>");
        }

        sb.Append("<tr><td>City</td><td>" + city + "</td></tr>");
        sb.Append("<tr><td>State/Province</td><td>" + state + "</td></tr>");
        sb.Append("<tr><td>Zip/Postal Code</td><td>" + zip + "</td></tr>");
        sb.Append("<tr><td>Country</td><td>" + country + "</td></tr>");
        sb.Append("<tr><td>Email Address</td><td>" + email + "</td></tr>");
        sb.Append("<tr><td>Primary Phone Number</td><td>" + phonePriType + ": " + phonePri + "</td></tr>");

        if (phoneOther != "" && phoneOther != null)
        {
            sb.Append("<tr><td>Other Phone Number</td><td>" + phoneOtherType + ": " + phoneOther + "</td></tr>");
        }

        int i = 0;
        foreach (ListItem l in cblAffiliation.Items)
        {
            if (l.Selected)
            {
                i += 1;
            }
        }

        if (i > 0)
        {
            sb.Append("<tr><td valign=\"top\">KPC Affiliation</td><td>");
            foreach (ListItem l in cblAffiliation.Items)
            {
                if (l.Selected)
                {
                    sb.Append(l.Text + "<br />");
                }
            }
            sb.Append("</td></tr>");
        }

        if (alumDegree != "" && alumDegree != null)
        {
            sb.Append("<tr><td>If you're an alum, year(s) degree(s) was/were received</td><td>" + alumDegree + "</td></tr>");
        }

        if (employer != "" && employer != null)
        {
            sb.Append("<tr><td>Your employer</td><td>" + employer + "</td></tr>");
        }

        if (position != "" && position != null)
        {
            sb.Append("<tr><td>Position/Title</td><td>" + position + "</td></tr>");
        }

        sb.Append("<tr><td>I work for a matching gift company</td><td>" + matchGift + "</td></tr>");

        if (ddlMatch.SelectedValue.ToLower() == "yes")
        {
            sb.Append("<tr><td>The name of the company is</td><td>" + matchGiftCo + "</td></tr>");
        }

        sb.Append("<tr><td><strong>Amount</strong></td><td><strong>" + amount + "</strong></td></tr>");

        sb.Append("<tr><td colspan=\"2\" style=\"color:#fff;background:#314689;\"><strong>Please tell us about your gift</strong></td></tr>");

        if (dirGiftCode == " (momentum)")
        {
            dirGiftCode = "";
        }
        else if (dirGiftCode == " (foundation)")
        {
            dirGiftCode = "";
        }

        sb.Append("<tr><td>Where would you like us to direct your gift?</td><td>" + dirGift + dirGiftCode + "</td></tr>");

        if (ddlGiftDir.SelectedValue == "momentum")
        {
            sb.Append("<tr><td>Where would you like us to direct your Momentum Campaign gift?</td><td>" + ddlMomentum.SelectedItem.Text + " (" + ddlMomentum.SelectedValue + ")</td></tr>");
            sb.Append("<tr><td>Contact me about setting up or renewing a payroll deduction.</td><td>" + payroll + "</td></tr>");
        }

        if (addInfo != "" && addInfo != null)
        {
            sb.Append("<tr><td>Additional information or specifics about your gift</td><td>" + addInfo + "</td></tr>");
        }

        sb.Append("<tr><td>This gift will be on a business card</td><td>" + businessCard + "</td></tr>");

        if (ddlBusinessCredit.SelectedValue.ToLower() == "yes")
        {
            sb.Append("<tr><td>Name on credit card</td><td>" + businessName + "</td></tr>");
            sb.Append("<tr><td>Billing Address of credit card</td><td>" + businessAddr + "</td></tr>");
        }

        if (honorOf != "" && honorOf != null)
        {
            sb.Append("<tr><td>In honor/memory of</td><td>" + honorOf + "</td></tr>");
        }

        sb.Append("<tr><td colspan=\"2\" style=\"color:#fff;background:#314689;\"><strong>We want to stay in touch with you</strong></td></tr>");

        if (estate != null && estate != "")
        {
            sb.Append("<tr><td>Please contact me about including KPC in my estate plans</td><td>" + CheckTrue(estate) + "</td></tr>");
        }

        if (newsletter != null && newsletter != "")
        {
            sb.Append("<tr><td>Please subscribe me to the kpcWORD</td><td>" + CheckTrue(newsletter) + "</td></tr>");
        }

        if (whyGive != null && whyGive != "")
        {
            sb.Append("<tr><td>Please share why you give to KPC</td><td>" + whyGive + "</td></tr>");
        }


        sb.Append("</tbody></table>");
        sb.Append("</body></html>");

        content = sb.ToString();
    }

    protected string CheckTrue(string s)
    {
        if (s.ToLower() == "true")
        {
            return "Yes";
        }
        else
        {
            return "No";
        }
    }

    protected void SendMail(string from, string to, string bcc, string cc, string cc2, string cc3, string cc4, string subject, string body)
    {
        MailMessage mMailMessage = new MailMessage();
        mMailMessage.From = new MailAddress(from);
        mMailMessage.To.Add(new MailAddress(to));

        if ((bcc != null) && (bcc != string.Empty))
        {
            mMailMessage.Bcc.Add(new MailAddress(bcc));
        }

        if ((cc != null) && (cc != string.Empty))
        {
            mMailMessage.CC.Add(new MailAddress(cc));
        }

        if ((cc2 != null) && (cc2 != string.Empty))
        {
            mMailMessage.CC.Add(new MailAddress(cc2));
        }

        if ((cc3 != null) && (cc3 != string.Empty))
        {
            mMailMessage.CC.Add(new MailAddress(cc3));
        }

        if ((cc4 != null) && (cc4 != string.Empty))
        {
            mMailMessage.CC.Add(new MailAddress(cc4));
        }

        mMailMessage.Subject = subject;
        mMailMessage.Body = body;
        mMailMessage.IsBodyHtml = true;
        mMailMessage.Priority = MailPriority.Normal;
        SmtpClient mSmtpClient = new SmtpClient("krc-exchange01.apps.ad.alaska.edu");
        mSmtpClient.UseDefaultCredentials = false;

        string user = System.Configuration.ConfigurationManager.AppSettings["giving_Smtp_User"].ToString();
        string pass = System.Configuration.ConfigurationManager.AppSettings["giving_Smtp_Pass"].ToString();

        mSmtpClient.Credentials = new NetworkCredential(user, pass);

        try
        {
            mSmtpClient.Send(mMailMessage);
        }
        catch
        {

        }
    }
}