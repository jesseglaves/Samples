<?
	$clean = rtrim($_SERVER['REQUEST_URI'], "/");
	$uri = explode('/', $clean);
	$title_route = $uri[4];
	
	$date_route = date('Y-m-d', strtotime($uri[5]));
	
	$date = explode(",", date('Y,n,j', strtotime($uri[5])));
	$year = $date[0];
	$month = $date[1] - 1;
	$date = $date[2];
	
	$item = $eventsMod->getInstanceByRoute($title_route, $date_route);
	$page_title = "Event Details";
?>

<div class="page">

	<? include "../templates/layouts/_topshelf.php" ?>
    
    <div class="row outercontent">
    
    	<? include "../templates/layouts/_breadcrumbs.php" ?>
            
		<? if ($teaser) { ?>
            <blockquote><?=$teaser?></blockquote>
        <? } ?>
            
        <div class="events desktop-8 tablet-4 mobile-full padded">
        
        	<h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit;text-align:left;">Details for: <?=$item["title"]?></h2>
            <hr style="margin-top:0px;" />
            
            <div class="row">
            
                <div class="event main desktop-full tablet-full mobile-full all <?=$f["route"];?>" style="position:relative;">
                
						<div class="content">
                            
							<? if ($item["featured"] == "on") { ?>
                                <span class="glyphicon glyphicon-bookmark" style="font-size:24px;position:absolute;top:-4px;left:8px;z-index:1;color:#f2de6d;"></span>
                            <? } ?>
                            
                            <div class="details">
                            
                                <a href="<?=BigTree::prefixFile($item["image"], "boxer_")?>" class="boxer" title="<?=$item["title"]?>">
                                    <img src="<?=$item["image"]?>" style="width:300px;height:auto;float:left;margin:0 25px 20px 0;" />
                                </a>
                            
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
                                
                                <? if ($item["all_day"] == "on") { ?>
                                    <h3 class="datetime"><strong><?=$startend?></strong>, <span style="text-transform:none;">All Day</span></h3>
                                <? } else { ?>
                                    <p class="datetime"><strong><?=$startend?></strong>, <span style="text-transform:none;"><?= $starttime?><?= $endtime ?></span></p>
                                <?
                                    }
                                ?>
                                <p><?=$item["description"]?></p>
                            </div>
                            
                            <div class="clear"></div>
                        
                        </div>
                
                </div>
            </div>
            
        </div>
        
        <div class="events side desktop-4 tablet-2 mobile-full padded" style="padding-bottom:40px;">
        
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
            
            <a class="btn prev" href="/about/events/" style="width:100%;background:#004687;">Back to List of Events</a>
            
            <div class="margin_m"></div>
            
            <h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit; text-align:left;">Events by Category</h2>
            <hr style="margin-top:0px;" />
            
            <div class="eventcategories">
                
                <?
                    $list = array();
                    $q = sqlquery("SELECT * FROM btx_events_categories ORDER BY name");
					$qu = sqlquery("SELECT btx_events_events.*, btx_events_event_categories.category FROM btx_events_events, btx_events_event_categories WHERE ". $item["id"] ." = btx_events_event_categories.event");
					$fe = sqlfetch($qu);
					$category =	$fe["category"];
					
					$qu = sqlquery("SELECT btx_events_categories.name, btx_events_event_categories.* FROM btx_events_categories, btx_events_event_categories WHERE ". $category ." = btx_events_categories.id");
                    $fe = sqlfetch($qu);
                    $name = $fe["name"];
					
                    while ($f = sqlfetch($q)) { 
						
						if ($f["name"] == $name) {
							echo "<a href=\"/about/events/category/".$f["route"]."\" class=\"btn active\">".$f["name"]."</a>";
						} else {
                        	echo "<a href=\"/about/events/category/".$f["route"]."\" class=\"btn\">".$f["name"]."</a>";
						}
                    }
					
                ?>
            
            </div>
            
            <h2 class="roller-heading" style="border:none;margin-bottom:0 !important;padding:0;line-height:normal;height:inherit;text-align:left;">Events by Day</h2>
            <hr style="margin-top:0px;" />

            <input type="text" name="d" class="picker" style="display:none;" /> 
        
        </div>
        
    </div>

</div>