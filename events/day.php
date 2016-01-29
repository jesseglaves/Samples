<?
	$day = str_replace(",","-",$_GET["d"]);
	$events = $eventsMod->getEventsByDate($day, false);
	$date = explode(",", $_GET["d"]);
	$year = $date[0];
	$month = $date[1] - 1;
	$date = $date[2];
	$monthName = date('F', mktime(0, 0, 0, ($month+1), 10));
	$page_title = "Events by Day";
?>

<div class="page">

	<? include "../templates/layouts/_topshelf.php" ?>
    
	<div class="row outercontent">
    
		<? include "../templates/layouts/_breadcrumbs.php" ?>
        
        <? if ($teaser) { ?>
            <blockquote><?=$teaser?></blockquote>
        <? } ?>
            
        <div class="events desktop-8 tablet-4 mobile-full padded">
        
        	<h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit;text-align:left;">Events on: <?=$monthName." ".$date.", ".$year?></h2>
            <hr style="margin-top:0px;" />
            
            <?
            	$prev_date = date('Y,n,j', strtotime($day .' -1 day'));
				$next_date = date('Y,n,j', strtotime($day .' +1 day'));    
        	?>
            
            <div class="pagination_container">
                <a href="?d=<?=$prev_date?>" id="previous-number" title="Previous" class="pagearrow left">←</a>
                <a href="?d=<?=$next_date?>" id="next-number" title="Next" class="pagearrow right">→</a>
                <div class="clear"></div>
            </div>
            
          
        	<? if (count($events)) { ?>
                <?
					$count = 0;
					foreach ($events as $item)
					{
				?>
                <div class="row">
                
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
                                $endtime = date("g:i A", strtotime($item["end_time"]));
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
                                <p class="datetime"><strong><?=$startend?></strong>, <span style="text-transform:none;"><?=$starttime." - ".$endtime?></span></p>
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
            <?
				} else {
			?>
            	
                No events on this day.
                
            <? } ?>
            
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
            
            <a class="btn" href="/about/events/" style="width:100%;">Back to List of Events</a>
            
            <div class="margin_m"></div>
            
            <h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit; text-align:left;">Events by Category</h2>
            <hr style="margin-top:0px;" />
            
            <div class="eventcategories">
                
                <?
                    $list = array();
                    $q = sqlquery("SELECT * FROM btx_events_categories ORDER BY name");
                    while ($f = sqlfetch($q)) {
						if ($f["route"] == $route) {
							echo "<a href=\"/about/events/category/".$f["route"]."\" class=\"btn active\">".$f["name"]."</a>";
						} else {
                        	echo "<a href=\"/about/events/category/".$f["route"]."\" class=\"btn\">".$f["name"]."</a>";
						}
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