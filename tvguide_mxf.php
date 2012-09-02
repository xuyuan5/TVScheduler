<?php
/* 
 * @author Yuan Xu
 * for detailed information about Windows Media Center Guide file, see:
 *   http://msdn.microsoft.com/en-us/library/windows/desktop/dd776338.aspx 
*/

error_reporting(E_ALL);
//error_reporting(E_ERROR);
ini_set('display_errors', true);

if(!date_default_timezone_set("America/Toronto"))
{
	// TODO-L: print error
}
if(!file_exists('config.php'))
{
    die("ERROR: database is not setup correctly");
}

require_once('config.php');
require_once('lib/classes.php');
require_once('database/channels.php');
require_once('schedules/schedule.php');
require_once('lib/date_time.php');

echo '<?xml version="1.0" encoding="UTF-8"?>';
print "\n";
gather_schedule_info_from_db();
foreach($channels as $channel)
{
	$channel->rearrange();
}
?>
<MXF xmlns:sql="urn:schemas-microsoft-com:XML-sql" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <Assembly name="mcepg" version="6.0.6000.0" cultureInfo="" publicKey="0024000004800000940000000602000000240000525341310004000001000100B5FC90E7027F67871E773A8FDE8938C81DD402BA65B9201D60593E96C492651E889CC13F1415EBB53FAC1131AE0BD333C5EE6021672D9718EA31A8AEBD0DA0072F25D87DBA6FC90FFD598ED4DA35E44C398C454307E8E33B8426143DAEC9F596836F97C8F74750E5975C64E2189F45DEF46B2A2B1247ADC3652BF5C308055DA9">
    <NameSpace name="Microsoft.MediaCenter.Guide">
      <Type name="Lineup" />
      <Type name="Channel" parentFieldName="lineup" />
      <Type name="Service" />
      <Type name="ScheduleEntry" groupName="ScheduleEntries" />
      <Type name="Program" />
      <Type name="Keyword" />
      <Type name="KeywordGroup" />
      <Type name="Person" groupName="People" />
      <Type name="ActorRole" parentFieldName="program" />
      <Type name="DirectorRole" parentFieldName="program" />
      <Type name="WriterRole" parentFieldName="program" />
      <Type name="HostRole" parentFieldName="program" />
      <Type name="GuestActorRole" parentFieldName="program" />
      <Type name="ProducerRole" parentFieldName="program" />
      <Type name="GuideImage" />
      <Type name="Affiliate" />
      <Type name="SeriesInfo" />
      <Type name="Season" />
    </NameSpace>
  </Assembly>
  <Assembly name="mcstore" version="6.0.6000.0" cultureInfo="" publicKey="0024000004800000940000000602000000240000525341310004000001000100B5FC90E7027F67871E773A8FDE8938C81DD402BA65B9201D60593E96C492651E889CC13F1415EBB53FAC1131AE0BD333C5EE6021672D9718EA31A8AEBD0DA0072F25D87DBA6FC90FFD598ED4DA35E44C398C454307E8E33B8426143DAEC9F596836F97C8F74750E5975C64E2189F45DEF46B2A2B1247ADC3652BF5C308055DA9">
    <NameSpace name="Microsoft.MediaCenter.Store">
      <Type name="Provider" />
      <Type name="UId" parentFieldName="target" />
    </NameSpace>
  </Assembly>
  <Providers>
    <Provider id="xuyuan" name="MXGuideListings" displayName="MX Guide Listings" copyright="" />
  </Providers>
  <With provider="xuyuan">
    <Keywords>
      <Keyword id="k1"   word="General" />
      <Keyword id="k100" word="All" />
      <Keyword id="k101" word="Action/Adventure" />
      <Keyword id="k102" word="Comedy" />
      <Keyword id="k103" word="Documentary/Bio" />
      <Keyword id="k104" word="Drama" />
      <Keyword id="k105" word="Educational" />
      <Keyword id="k106" word="Family/Children" />
      <Keyword id="k107" word="Movies" />
      <Keyword id="k108" word="Music" />
      <Keyword id="k109" word="News" />
      <Keyword id="k110" word="Sci-Fi/Fantasy" />
      <Keyword id="k111" word="Soap" />
      <Keyword id="k112" word="Sports" />
      <Keyword id="k113" word="Other" />

      <Keyword id="k2"   word="Educational" />
      <Keyword id="k200" word="All" />
      <Keyword id="k201" word="Arts" />
      <Keyword id="k202" word="Biography" />
      <Keyword id="k203" word="Documentary" />
      <Keyword id="k204" word="How-to" />
      <Keyword id="k205" word="Science" />
      <Keyword id="k206" word="Other" />
    </Keywords>
    <KeywordGroups>
      <KeywordGroup uid="!KeywordGroup!k1" groupName="k1" keywords="k100,k101,k102,k103,k104,k105,k106,k107,k108,k109,k110,k111,k112,k113" />
      <KeywordGroup uid="!KeywordGroup!k2" groupName="k2" keywords="k200,k201,k202,k203,k204,k205,k206" />
    </KeywordGroups>
    <GuideImages>
      <!-- TODO: Generate some image using the icon field already present in the database -->
      <!-- <GuideImage id="i1" uid="!Image!NoImage" imageUrl="" /> -->
    </GuideImages>
    <SeriesInfos>
      <!-- TODO: Generate some series information -->
      <!--<SeriesInfo id="si1" uid="!Series!NoSeries" title="No Series Info" shortTitle="Empty" description="No Description" shortDescription="NoDesc" startAirdate="2000-01-01T00:00:00" endAirdate="1900-01-01T00:00:00" guideImage="i1" />-->
    </SeriesInfos>
    <Programs>
<?php
$programID = 1;
$serviceID = 1;
foreach($channels as &$channel)
{
    $channel->serviceID = "s".$serviceID;
	foreach($channel->programmes as $time => &$p)
    {
        $p->ID = $programID;
        printf('      <Program id="%d" uid="%s" title="%s" episodeTitle="%s">', $programID,  "!Program!".$programID, str_replace("&", "&amp;", $p->title), str_replace("&", "&amp;", trim($p->sub_title, '"')));
        print "\n";
?>
      </Program>
<?php
        $programID = $programID + 1;
    }
	unset($p);
    $serviceID = $serviceID + 1;
}
unset($channel);
?>
    </Programs>
    <Services>
<?php
foreach($channels as $channel)
{
    printf('      <Service id="%s" uid="%s" name="%s" callSign="%s" />', $channel->serviceID, "!Service!".$channel->serviceID, $channel->id, $channel->id);
    print "\n";
}
?>
    </Services>
<?php
foreach($channels as $channel)
{
    printf('    <ScheduleEntries service="%s">', $channel->serviceID);
    print "\n";
    $is_first = true;
	foreach($channel->programmes as $time => $p)
    {
        printf('      <ScheduleEntry program="%d" ', $p->ID);
        if($is_first)
        {
            printf('startTime="%s" ', gmdate("Y-m-d\TH:i:s", $p->start));
            $is_first = false;
        }
        printf('duration="%d" />', $p->end - $p->start);
        print "\n";
    }
?>
    </ScheduleEntries>
<?php
}
?>
    <Lineups>
      <Lineup id="l1" uid="!Lineup!Markham" name="Markham, Ontario" primaryProvider="!MCLineup!MainLineup" >
        <channels>
<?php
foreach($channels as $channel)
{
    // TODO: may wish to specify number attribute
    printf('          <Channel uid="!Channel!Markham!%d" lineup="l1" service="%s" number="%d"/>', $channel->channel_number, $channel->serviceID, $channel->channel_number);
    print "\n";
}
?>
        </channels>
      </Lineup>
    </Lineups>
  </With>
</MXF>
