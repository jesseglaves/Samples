<%@ Control Debug="False" Language="C#" AutoEventWireup="true" CodeFile="UpcomingEvents.ascx.cs" Inherits="usercontrols_UpcomingEvents" %>
<%@ Register Assembly="Ektron.Cms.Controls" Namespace="Ektron.Cms.Controls" TagPrefix="CMS" %>
<%@ Register Assembly="Telerik.Web.UI" Namespace="Telerik.Web.UI" TagPrefix="telerik" %> 

<cms:WebCalendar ID="WebCalendar" runat="server" DisplayType="All" SuppressWrapperTags="True" Hide="true">
</cms:WebCalendar>
<telerik:RadToolTip  runat="server"  ID="RadToolTip1" Width="1px" Height="1px"  >
</telerik:RadToolTip>

<asp:Literal ID="UpcomingEvents" runat="server" />

