<?php
ob_start();
/*

Viewver Shortcode:

1 - Calendar

2 - List Events

3 - Events filter

*/

add_shortcode( 'eventissimo', 'eventissimo_shortcode' );

function eventissimo_shortcode($atts) {

	$sh_meta = shortcode_atts(

		array(

			'type'=>'',

			'limit'=>'',

			'date'=>'false',

			'paginate'=>'false',

			'view'=>'',

			'defined'=>'',

			'backcolorhrex'=>'#069C88',

			'textcolorhex'=>'#FFFFFF',
			
			'search'=>'FALSE'

		), $atts);

	

	$type = strtoupper($atts['type']);

	$date = strtoupper($atts['date'])=='TRUE'? TRUE : FALSE;

	$paginate = strtoupper($atts['paginate'])=='TRUE'? TRUE : FALSE;

	$defined = strtoupper($atts['defined']);

	$post_per_page = $atts['limit'];

	$view = strtoupper($atts['view']);

	$backcolorHEX = strtoupper($atts['backcolorhex']);

	$textcolorHEX = strtoupper($atts['textcolorhex']);


	$search = strtoupper($atts['search'])=='TRUE'? TRUE : FALSE;

	if (($paginate==TRUE) && ($post_per_page=="")) $post_per_page=10;

	

	switch ($type){

		case "CALENDAR":

		  	return eventissimo_frontend_calendar($backcolorHEX,$textcolorHEX,$search);

		break;

		case "LIST":

		  	return eventissimo_frontend_list($post_per_page,$date,$view,$paginate,"LIST",$defined);

		break;

		case "BLOCK":

		  	return eventissimo_frontend_list($post_per_page,$date,$view,$paginate,"BLOCK",$defined);

		break;

		case "CYCLE":

		  	return eventissimo_frontend_cycle($post_per_page,$view,$defined);

		break;

		default:

			return __("Invalid Shortcode","eventissimo");	

	}

}



?>
