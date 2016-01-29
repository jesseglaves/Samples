using System;
using System.Collections;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.IO;
using System.Text;
using System.Text.RegularExpressions;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Xml;
using System.Xml.XPath;
using Ektron.Cms;
using Ektron.Cms.Controls;
using Ektron.Cms.Common;


public partial class usercontrols_UpcomingEvents : System.Web.UI.UserControl
{
   private int _maxResults = 10 ;
   private int _daysAhead = 90 ;
   private int _daysBehind = 0 ;
   private string _eventFormat = "{3:dddd}, {3:MM}/{3:dd} - <B>{0}</B>" ;
   private string _headerFormat = "{0} Events from {1:m} to {2:m}" ;
   private bool _suppressWrapperTags = false ;
   private List<CalendarDataSource> _calendarsource = new List<CalendarDataSource>();

   public int MaxResults { get { return _maxResults; } set { _maxResults = value; } }
   public int DaysAhead  { get { return _daysAhead;  } set { _daysAhead = value; } }
   public int DaysBehind { get { return _daysBehind; } set { _daysBehind = value; } }
   public string EventFormat { get { return _eventFormat; } set { _eventFormat = value; } }
   public string HeaderFormat { get { return _headerFormat; } set { _headerFormat = value; } }
   public bool SuppressWrapperTags { get { return _suppressWrapperTags; } set { _suppressWrapperTags = value; } }

   public List<CalendarDataSource> DataSource { 
      get { return _calendarsource; } 
      set { _calendarsource = value; } 
   }

   static string[] backColorCategories = { 
      "LightBlue",
      "Red",
      "Orange",
      "Green",
      "Yellow",                                    
      "Blue",
      "Pink",
      "Violet",
      "DarkGreen",
      "DarkRed",
      "DarkBlue" } ;


   private class MyWebEventData : Ektron.Cms.Common.Calendar.WebEventData
   {
      EventColor backcolor = EventColor.AutoSelect;

      public MyWebEventData(Ektron.Cms.Common.Calendar.WebEventData e)
      {
         CopyEventData( e, (Ektron.Cms.Common.Calendar.WebEventData) this) ;
         this.Quicklink = e.Quicklink ;
      }
      public MyWebEventData(Ektron.Cms.Common.Calendar.WebEventData e, EventColor bc )
      {
         CopyEventData( e, (Ektron.Cms.Common.Calendar.WebEventData) this) ;
         //(Ektron.Cms.Common.Calendar.WebEventData) this = e.Clone() ;
         this.Quicklink = e.Quicklink ;
         backcolor = bc ;
      }

      public EventColor backColor {
         get { return backcolor; }
         set { backcolor = value; }
      }
   }

   public void Fill()
   {
      Page_Load( null, null ) ;
   }

   protected void Page_Load(object sender, EventArgs e)
   {
      int nextColor = 0 ;
      int nrOfEvents = 0 ;
      EventColor bc ;

      System.Collections.Generic.List<Ektron.Cms.Common.Calendar.WebEventData> eventList = 
         new System.Collections.Generic.List<Ektron.Cms.Common.Calendar.WebEventData>();

      System.Collections.Generic.List<MyWebEventData> MyEventList = 
         new System.Collections.Generic.List<MyWebEventData>();

      Ektron.Cms.Framework.Calendar.WebEvent weAPI = 
         new Ektron.Cms.Framework.Calendar.WebEvent();

      System.DateTime st = DateTime.Now.AddDays( 0 - _daysBehind);
      System.DateTime et = DateTime.Now.AddDays(_daysAhead);
      System.DateTime firstEvent = et ; 
      System.DateTime lastEvent = st ; 

      foreach (CalendarDataSource cds in _calendarsource)
      {
         if( cds.backColor == EventColor.AutoSelect )
         {
            bc = (EventColor) ((nextColor % 7 /* 11 */) +1) ;
            nextColor ++ ;
         }
         else
            bc = cds.backColor ;

         eventList = weAPI.GetEventOccurrenceList( cds.defaultId, st, et) ;
         foreach( Ektron.Cms.Common.Calendar.WebEventData wed in eventList )
         {
            MyEventList.Insert( 0, new MyWebEventData( wed, bc ) );
         }
      }

      MyEventList.Sort(delegate(MyWebEventData we1, MyWebEventData we2){ return we1.EventStartUtc.CompareTo(we2.EventStartUtc);});

      StringBuilder sb = new StringBuilder();
      StringBuilder ss = new StringBuilder();
      string eventTime = "";
      string tooltip = "" ;
      string header = "" ;

      string eventTitle = "" ;

      if( !_suppressWrapperTags )
         ss.Append( "\n<SCRIPT type=\"text/javascript\">\n" ) ;

      foreach (MyWebEventData webEventData in MyEventList)
      {

         if( (DateTime.Compare(webEventData.EventStartUtc, System.DateTime.Now)) > 0 )
         {
            if( lastEvent < webEventData.EventStart )
               lastEvent = webEventData.EventStart ;
            if( firstEvent > webEventData.EventStart )
               firstEvent = webEventData.EventStart ;

            eventTime = ((DateTime.Compare(webEventData.EventStart.AddDays(1), webEventData.EventEnd)) == 0) ? "All Day Event" : webEventData.EventStart.ToShortTimeString() + " to " + webEventData.EventEnd.ToShortTimeString();
            string strDescription = (webEventData.Description.Length <= 30) ? webEventData.Description : webEventData.Description.Substring(0, 30) + "... ";

            eventTitle = String.Format( _eventFormat, 
                                        webEventData.Title,
                                        webEventData.Description,
                                        webEventData.Location,
                                        webEventData.EventStart,
                                        webEventData.EventEnd,
                                        eventTime,
                                        webEventData.Quicklink.ToString(),
                                        backColorCategories[ ((int) webEventData.backColor) -1 ]
                                      ) ;

            if( _suppressWrapperTags )
            {
               sb.Append( eventTitle ) ;
            }
            else
            {
               sb.Append(
"                              <tr class=\"rsAllDayRow\">\n" +
"                                 <td class=\"rsLastCell\">\n" +
"                                    <div class=\"rsWrap\" style=\"z-index:20;\">\n" +
"                                       <div class=\"rsApt rsCategory" +backColorCategories[ ((int) webEventData.backColor) -1 ] + "\" style=\"width:100%;position:relative; z-index:25;\">\n" +
"                                          <div class=\"rsAptOut\">\n" +
"                                             <div class=\"rsAptMid\">\n" +
"                                                <div class=\"rsAptIn\">\n" +
"                                                   <div ID=\"" +this.ClientID +"_Event_" +nrOfEvents.ToString() +"_AptContent\" class=\"rsAptContent\" >\n" +
"                                                      <span  id=\"" + this.ClientID +"_Event_" +nrOfEvents.ToString() +"_title\" class=\'UpcomingEventsDesc\'>" +eventTitle +"</span>\n" +
"                                                      <div id=\"" + this.ClientID +"_Event_" +nrOfEvents.ToString() +"_description\" style=\"display:none;\">\n" +
"                                                         <input id=\"" + this.ClientID +"_Event_" +nrOfEvents.ToString() +"_description_ClientState\" name=\"" + this.ClientID +"_Event_" +nrOfEvents.ToString() +"_description_ClientState\" type=\"hidden\" />\n" +
"                                                      </div>\n" +
"                                                   </div>\n" +
"                                                </div>\n" +
"                                             </div>\n" +
"                                          </div>\n" +
"                                       </div>\n" +
"                                    </div>\n" +
"                                 </td>\n" +
"                              </tr>\n"
                  ) ;
   
               tooltip = String.Format( "\\u003cdiv style=\\\"padding:10px;\\\"\\u003e{0}{1}Time: {2}\\u003cbr /\\u003e\\u003ca style=\\\"float:right;\\\" href=\\\"{3}\\\"\\u003emore\\u003c/a\\u003e\\u003c/div\\u003e",
                          (webEventData.Description.Length > 0 ? "Description: " + webEventData.Description.Replace( "\n", " " ).Replace( "\r", " " ) + "\\u003cbr /\\u003e" : ""),
                          (webEventData.Location.Length > 0 ? "Location: " + webEventData.Location + "\\u003cbr /\\u003e" : "" ),
                          eventTime,
                          webEventData.Quicklink.ToString()
                          ) ;
   
   
               ss.Append( 
                          "Sys.Application.add_init(function() {\n" +
                          "    $create(Telerik.Web.UI.RadToolTip, {\"clientStateFieldID\":" +
                          "\"" +this.ClientID +"_Event_" +nrOfEvents.ToString() +"_description_ClientState\"" +
                          ",\"formID\":\"aspnetForm\",\"skin\":\"Default\",\"sticky\":true,\"targetControlID\":" +
                          "\"" +this.ClientID +"_Event_" +nrOfEvents.ToString() +"_AptContent\"" +
                          ",\"text\":\"" +tooltip +"\",\"title\":\"" + webEventData.DisplayTitle +"\"}, null, null, $get(" +
                          "\"" +this.ClientID +"_Event_" +nrOfEvents.ToString() +"_description\"" +
                          "));\n" +
                          "});\n"
                          ) ;
            }

            nrOfEvents ++ ;

            if( nrOfEvents >= _maxResults )
               break;
         }
      }

      header = String.Format( _headerFormat, nrOfEvents, firstEvent, lastEvent, st, et ) ;

      if( _suppressWrapperTags )
      {
         sb.Insert( 0, header ) ;
      }
      else
      {
         sb.Insert( 0,
"   <div id=\"" +this.ClientID +"_InnerSchedulerPanel\">\n" +
"      <div id=\"" +this.ClientID +"_InnerScheduler\" class=\"RadScheduler RadScheduler_Vista \" style=\"overflow-y:visible;\">\n" +
"         <div class=\"rsTopWrap rsOverflowExpand\">\n" +
"            <div class=\"rsHeader\">\n" +
"               <h2 style=\"text-indent:5px;\">" +header +"</h2>\n" +
"            </div>\n" +
"            <div class=\"rsContent rsDayView\">\n" +
"               <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:100%;\">\n" +
"                  <tr>\n" +
"                     <td class=\"rsContentWrapper\" style=\"width:100%;\">\n" +
"                        <div class=\"rsContentScrollArea\" style=\"overflow:auto;position:relative;overflow-x:visible;\">\n" +
"                           <table class=\"rsContentTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:100%;\">\n" 
      ) ;


         sb.Append(                 
"                           </table>\n" +
"                        </div>\n" +
"                     </td>\n" +
"                  </tr>\n" +
"               </table>\n" +
"            </div>\n" +
"         </div>\n" +
"      </div>\n" +
"   </div>\n" ) ;

         ss.Append( "</SCRIPT>\n" ) ;
      }

      UpcomingEvents.Text = sb.ToString();
      UpcomingEvents.Text += ss.ToString();

   }
}

