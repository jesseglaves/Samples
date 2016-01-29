using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Ektron.Cms;

public partial class usercontrols_login : System.Web.UI.UserControl
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (kpc.utils.GetUserName() != "0")
        {
            phLogin.Visible = false;
        }
		else
		{
			TextBox txt = ((TextBox)aspLogin.FindControl("UserName"));
			
			txt.Focus();
		}
	}

    protected void ibClose_Click(object sender, EventArgs e)
    {
        phLogin.Visible = false;
    }
}