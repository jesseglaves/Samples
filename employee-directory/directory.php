<div class="page people" style="min-height:550px;">

	<? include "../templates/layouts/_topshelf.php" ?>
    
    <div class="row">
    
        <?
			if (!$is404) {
		?>
        
        <? include "../templates/layouts/_sidemenu.php" ?>
        
        
		<? if (count($featured) > 0) { ?>
        
            <div class="desktop-<? if ((count($subnav)) > 0) { echo '9'; } else { echo 'full'; } ?> tablet full mobile-full">
            
                <? include "../templates/layouts/_breadcrumbs.php" ?>
            
                <? if ($teaser !== "") { ?>
                    <blockquote><?=$teaser?></blockquote>
                <? } ?>
                
                <div class="row">
                    <div class="desktop-8 tablet-full mobile-full content">
                        <?=$content?>
                    </div>
                    <? include "../templates/callouts/featured.php" ?>
                </div>
            
            </div>

        <? } else { ?>
        
            <div class="desktop-<? if ((count($subnav)) > 0 && (count($subnav)) != 4) { echo '9'; } else { echo 'full'; } ?> tablet-full mobile-full">
                
                <? include "../templates/layouts/_breadcrumbs.php" ?>
                
                <? if ($teaser) { ?>
                    <blockquote><?=$teaser?></blockquote>
                <? } ?>
            
                <div class="content">
                
                	<div class="row">
                        <div class="desktop-6 tablet-full mobile-full contained searchbox">
                        
                            <form name="form" class="dirsearchform" method="get" action="/search/people-search/">
                                <div class="row contained">
                                    <div class="desktop-8 tablet-4 mobile-full searchbox">
                                        <input type="text" class="dirsearchbox" name="emp" value="<?= $_GET['q']; ?>" style="width:100%;" placeholder="Search content, people..." />
                                    </div>
                               
                                    <div class="desktop-4 tablet-2 mobile-full searchbox">
                                        <input type="submit" id="btnsubmit" class="dirsearchbutton" value="Submit"  />
                                    </div>
                                </div>
                            </form>
                            
                            <div class="clear"></div>
                        
                        </div>
                    </div>
                    
                    <hr />
                            
                    <?

						//$query = $_GET['query'];
						if (!empty($_GET['emp'])) {
							$query = $_GET['emp'];
							
							set_time_limit(30);
							
							// config
							$ldapserver = '';
							$ldapuser      = '';
							$ldappass     = '';
							$ldaptree    = "";
							
							// connect 
							$ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
							
							if($ldapconn) {
								// binding to ldap server
								$ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
								// verify binding
								if ($ldapbind) {
	
									$result = ldap_search($ldapconn,$ldaptree, "(&(objectCategory=CN=Person,CN=Schema,CN=Configuration,DC=ad,DC=alaska,DC=edu)(!(userAccountControl:1.2.840.113556.1.4.803:=2))(|(mail=*kpc*)(|(company=Kenai River Campus)(company=Kachemak Bay Campus)(company=Anchorage Extension Site)(company=Resurrection Bay Extension Site)))(memberOf=CN=UAA_Employees,OU=UAA,DC=ua,DC=ad,DC=alaska,DC=edu)(objectCategory=inetorgperson)(mail=*) (|(anr=".$query.")(uaidentifier=*".$query."*)))") or die ("Error in search query: ".ldap_error($ldapconn));
									$data = ldap_get_entries($ldapconn, $result);
								
									// iterate over array and print data for each entry
									// print number of entries found
									echo "<strong>Number of entries found: " . ldap_count_entries($ldapconn, $result) . "</strong><hr />";
									
									for ($i=0; $i<$data["count"]; $i++) {
										if(isset($data[$i]["thumbnailphoto"][0])) {
											$tempFile = tempnam(sys_get_temp_dir(), 'image');
											file_put_contents($tempFile, $data[$i]["thumbnailphoto"][0]);
											$finfo = new finfo(FILEINFO_MIME_TYPE);
											$mime  = explode(';', $finfo->file($tempFile));
											echo '<div class="circle" style="float:left;margin-right:30px;margin-bottom:70px;"><img style="width:150px;height:auto;" src="data:' . $mime[0] . ';base64,' . base64_encode($data[$i]["thumbnailphoto"][0]) . '"/></div>';	
										} else {
											echo '<div class="circle" style="float:left;margin-right:30px;margin-bottom:70px;width:150px;height:150px;text-align:center;color:#ccc;line-height:150px;">No photo.</div>';
										}
										if(isset($data[$i]["displayname"][0])) {
											echo "Name: ". $data[$i]["displayname"][0] ."<br />";
										}
										if(isset($data[$i]["title"][0])) {
											echo "Title: ". $data[$i]["title"][0] ."<br />";
										}
										if(isset($data[$i]["mail"][0])) {
											echo 'Email: <a href="mailto:'.$data[$i]["mail"][0].'">'.$data[$i]["mail"][0].'</a><br />';
										}
										if(isset($data[$i]["telephonenumber"][0])) {
											echo "Phone: ". $data[$i]["telephonenumber"][0].'<br />';
										}
										if(isset($data[$i]["physicaldeliveryofficename"][0])) {
											echo "Location: ". $data[$i]["physicaldeliveryofficename"][0].'<br />';
										}
										if(isset($data[$i]["description"][0])) {
											echo "Employee Type: ". $data[$i]["description"][0].'<br />';
										}
										if(isset($data[$i]["company"][0])) {
											echo "Campus: ". $data[$i]["company"][0] ."<br />";
										}
										
										echo '<div style="display:block;height:18px;"></div>';
										
										if(isset($data[$i]["displayname"][0])) {
											echo '<a class="btn" href="/employees/'.$data[$i]["cn"][0].'"><span class="glyphicon glyphicon-link"></span> Web page</a>';
										}
										
										echo '<div style="clear:both;"></div>';
										
										echo '<hr />';
										
									}
								} else {
									echo "LDAP bind failed...";
								}
							
							}
							
							ldap_close($ldapconn);
						
						}
					?>
                </div>
            </div>
        <? } ?>
        
    <? } ?>
	</div>

</div>