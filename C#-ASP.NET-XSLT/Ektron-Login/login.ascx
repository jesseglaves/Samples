<%@ Control Language="C#" AutoEventWireup="true" CodeFile="login.ascx.cs" Inherits="usercontrols_login" %>


<asp:PlaceHolder ID="phLogin" Visible="false" runat="server">
    <div id="modalBkgrd"></div>

    <div id="loginParts">

        <div id="loginHeader">
            <h1 class="headerBar">Login to KPC</h1>
            <asp:ImageButton ID="ibClose" ImageUrl="~/images/close.png" AlternateText="Close login window" OnClick="ibClose_Click" CssClass="closeBtn" runat="server" />
        </div>

        <script type="text/javascript">

            $(document).ready(function () {

                $('#loginParts').draggable();

            });
        
        </script>
        
        <div id="log">
        
            <asp:Login ID="aspLogin" MembershipProvider="EktronMembershipProvider" runat="server">
                <LayoutTemplate>

                    <asp:Panel ID="pnlLogin" DefaultButton="LoginButton" runat="server">

                        <p>User Name 
                        <asp:RequiredFieldValidator ID="UserNameRequired"
                            ControlToValidate="UserName"
                            ErrorMessage="User Name is required."
                            ToolTip="User Name is required."
                            ValidationGroup="ctl00$aspLogin"
                            runat="server">*</asp:RequiredFieldValidator></p>
                        <asp:TextBox ID="UserName" runat="server"></asp:TextBox>

                        <p style="margin:.8em 0 0 0;">Password 
                        <asp:RequiredFieldValidator ID="PasswordRequired"
                            ControlToValidate="Password"
                            ErrorMessage="Password is required."
                            ToolTip="Password is required."
                            ValidationGroup="ctl00$aspLogin"
                            runat="server">*</asp:RequiredFieldValidator></p>
                        <asp:TextBox ID="Password" runat="server" TextMode="Password"></asp:TextBox>
                        
                        <asp:ValidationSummary ID="ValidationSummary1" ValidationGroup="ctl00$aspLogin" runat="server" />
                            
                        <div class="failure"><asp:Literal ID="FailureText" runat="server" EnableViewState="False"></asp:Literal></div>
                        <asp:Button ID="LoginButton" runat="server" CommandName="Login" Text="Log In" CssClass="login" ValidationGroup="ctl00$aspLogin" />

                    </asp:Panel>

                </LayoutTemplate>
            </asp:Login>

        </div>

        <div class="clear"></div>

    </div>
</asp:PlaceHolder>