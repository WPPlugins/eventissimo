<?php /*
Filename: get.php
Service Name: Get events
*/
ob_start();
$type = isset($_GET["type"])?strtoupper($_GET["type"]):"RSS";
$search = isset($_GET["search"])?strtolower($_GET["search"]):"";
$value = isset($_GET["value"])?$_GET["value"]:"";
$defined = isset($_GET["defined"])?strtoupper($_GET["defined"]):"";
$view = isset($_GET["view"])?strtoupper($_GET["view"]):"null";

$search_allow = array("id_author", "description", "title");

$defined_allow = array("TODAY", "MONTH");
$view_permis = array("OLD", "NEXT");

$events = eventissimo_json_events("0",$view,$defined);


if (($search!="") && ($value!="") && (in_array($search,$search_allow))){
	$listEvents = eventissimo_searchJson($events, $search, $value);
	$listEvents=json_encode($listEvents);
} else {
	$listEvents = $events;
}
switch ($type){
	case "RSS":
		header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
		$html = '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
		$html .= '<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"><channel>';
 		$html .= '<title>' . get_bloginfo_rss('name') . '|' . __("Events","eventissimo") .'</title>';
		$html .= '<atom:link href="' . get_bloginfo('wpurl') . '/apievent/all?type=' . $type . '" rel="self" type="application/rss+xml" />';
		$html .= '<link>' . get_bloginfo_rss('url') . '</link>';
		$html .= '<description>' . get_bloginfo_rss('description') .'</description>';
		$html .= '<lastBuildDate>' . mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false) . '</lastBuildDate>';
		
		$html .= '<language>' .  get_bloginfo_rss( 'language' ) . '</language>';
		$html .= '<sy:updatePeriod>' . apply_filters( 'rss_update_period', 'hourly' ) . '</sy:updatePeriod>';
		$html .= '<sy:updateFrequency>' . apply_filters( 'rss_update_frequency', '1')  . '</sy:updateFrequency>';
		
		//foreach $events
		$response = json_decode($listEvents);
		if (count($response)>0){
			foreach ($response as $event){
				
				$title = $event->title;
				$url = $event->url;
				$authorname = get_the_author_meta( "display_name",$event->id_author );
				$start = date_i18n('Y-m-d',$event->date_begin) . " " . $event->hour_begin . ":00";
				$end = date_i18n('Y-m-d',$event->date_end) . " " . $event->hour_end . ":00";
				
				$messageTime = eventissimo_text_date(date_i18n(get_option('date_format'),$event->date_begin),date_i18n(get_option('date_format'),$event->date_end),$event->hour_begin,$event->hour_end);
				
				$description = $event->description;
				$cover = ($event->cover!="")? $event->cover : "";
				$id = $event->id;
				$idbase = $event->className;
				$html .="<item><title>" . $title . "</title>";
				$html .="<link>" . esc_url($url) . "</link>";
				
				$html .="<pubDate>" . get_the_time('D, d M Y H:i:s +0000', $idbase) . "</pubDate>";
				
				$html .="<dc:creator><![CDATA[" . $authorname  . "]]></dc:creator>";
				$html .="<guid isPermaLink='false'>" . $id . "</guid>";
				
				$html .="<description><![CDATA[" . $messageTime . " - " . $description . "]]></description>";
				
				
				$html .= "</item>";
				
			}
		}
 		$html .= '</channel></rss>';
	break;
	case "JSON":
		header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);
		$html = $listEvents;
	break;
	default:
		$html = __("Please, add type parameters, example ?type=rss","eventissimo");
}
print $html;