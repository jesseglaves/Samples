<?
	$events = $eventsMod->getUpcomingEvents(20);
	$page_title = "Events";
	$day = $_GET["d"];
	
	if ($day != "") {
	
		$date = explode(",", $day);
		
		$year = $date[0];
		$month = $date[1] - 1;
		$date = $date[2] - 1;
	}
?>

<div class="page">

	<? include "../templates/layouts/_topshelf.php" ?>
    
    <div class="row outercontent">
    
    	    <? include "../templates/layouts/_breadcrumbs.php" ?>
            
			<? if ($teaser) { ?>
                <blockquote><?=$teaser?></blockquote>
            <? } ?>
            
        <div class="events desktop-8 tablet-4 mobile-full padded">
        
        	<h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit;text-align:left;">Upcoming Events</h2>
            <hr style="margin-top:0px;" />
            <?
				$count = 0;
				foreach ($events as $item)
				{
			?>
            <div class="row outercontent">
            
                <div class="event main desktop-full tablet-full mobile-full all <?=$f["route"];?>" style="position:relative;">
                
                    <? if ($item["featured"] == "on") { ?>
                        <span class="glyphicon glyphicon-bookmark" style="font-size:24px;position:absolute;top:-2px;left:6px;z-index:1;color:#f2de6d;"></span>
                    <? } ?>
                    
                    <div class="details">
                    
                        <?
							
							// Start Date
                            $startdate = DateTime::createFromFormat('Y-m-d', $item["date_route"]);
                            $startmonth = $startdate->format('M');
                            $startday = $startdate->format('j');
							$enddate = DateTime::createFromFormat('Y-m-d', $item["end_date"]);
                            if ($enddate) {
								$endmonth = $enddate->format('M');
								$endday = $enddate->format('j');
							}
                            $starttime = date("g:i A", strtotime($item["start_time"]));
                            if (empty($item["end_time"]) == true) {
								$endtime = '';
							} else {
								$endtime = ' - '.date("g:i A", strtotime($item["end_time"]));	
							}
							$startend = "";
							
							if (empty($enddate)) {
								$startend = $startmonth." ".$startday;
							} else {
								$startend = $startmonth." ".$startday." - ".$endmonth." ".$endday;
							}
							
                        ?>
                        
                        <div class="image"><a href="/events/detail/<?=$item["route"]?>/<?=$item["date_route"]?>/"><img src="<?=$item["image"]?>" style="width:130px;height:auto;float:left;z-index:3; margin:0 25px 20px 0;" /></a></div>
                    
                        <h3><a href="/events/detail/<?=$item["route"]?>/<?=$item["date_route"]?>/"><?=$item["title"]?></a></h3>
                        
                        <? if ($item["all_day"] == "on") { ?>
                            <p class="datetime"><strong><?=$startend?></strong>, <span style="text-transform:none;">All Day</span></p>
                        <? } else { ?>
                            <p class="datetime"><strong><?=$startend?></strong>, <span style="text-transform:none;"><?=$starttime?><?=$endtime ?></span></p>
                        <?
                            }
                        ?>
                        <p><?=strip_tags(character_limiter($item["description"]))?></p>
                    </div>
                    
                    <div class="clear"></div>
                    <hr />
                
                </div>
            </div>
                
            <?	
                    $count += 1;
                }
            ?>
            
        </div>
        
        <div class="events desktop-4 tablet-2 mobile-full padded" style="padding-bottom:40px;">
        
			<script type="text/javascript" src="/js/lib/jquery-1.10.2.min.js"></script>
			<script type="text/javascript">
                $(document).ready(function(){
                    
                    var $input = $('.picker').pickadate({
                        'today': '',
                        'clear': '',
                        'close': '',
                        format: 'yyyy,m,d',
                        formatSubmit: 'yyyy,m,d',
                        onSet: function(context) {
							var v1 = "";
							<? if ($year && $month && $date) { ?>
								var v1 = '<?=$year.','.($month + 1).','.$date?>';
							<? } ?>
							var v2 = picker.get('select', 'yyyy,m,d');
							
							if (v1 != v2)
							{
								window.location.href = "/about/events/day/?d="+v2;
							}
						}
                    });
					
					var picker = $input.pickadate('picker');
					<? if ($year && $month && $date) { ?>
						picker.set('select', [<?=$year.','.$month.','.$date?>]);
					<? } ?>
                    
                    
                     
                });
            </script>
            
            <h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit; text-align:left;">Events by Category</h2>
            <hr style="margin-top:0px;" />
            
            <div class="eventcategories">
                
                <?
                    $list = array();
                    $q = sqlquery("SELECT * FROM btx_events_categories ORDER BY name");
                    while ($f = sqlfetch($q)) {
                        echo "<a href=\"/about/events/category/".$f["route"]."\" class=\"btn\">".$f["name"]."</a>";
                    }
                ?>
            
            </div>
            
            <h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit; text-align:left;">Events by Day</h2>
            <hr style="margin-top:0px;" />

            <form name="form" class="eventpicker" method="get" action="/about/events/day/">
                <input type="text" name="d" class="picker" style="display:none;" /> 
            </form>
        
        </div>
        
    </div>

</div>