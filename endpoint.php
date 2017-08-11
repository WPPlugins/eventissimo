<?php
define('RESTFUL_WEB_SERVICES_DIR', dirname(__FILE__));
define('RESTFUL_WEB_SERVICES_URL_PATTERN','apievent(/?.*)?');
$abspath = trim(str_replace('\\','/',ABSPATH),'/');
$rest_services_dir = str_replace('\\','/',RESTFUL_WEB_SERVICES_DIR);
$rest_services_path = trim(str_replace($abspath,'',$rest_services_dir),'/');
define('RESTFUL_WEB_SERVICES_PATH', $rest_services_path);

// NOTE, See: http://codex.wordpress.org/Custom_Queries#Permalinks_for_Custom_Archives
add_action('init', 'restful_web_services_flush_rewrite_rules');
add_filter('generate_rewrite_rules', 'restful_web_services_add_rewrite_rules');
add_action('template_redirect', 'restful_web_services_exec_service');
 
add_action('init', 'restful_web_services_flush_rewrite_rules');
function restful_web_services_flush_rewrite_rules() {
	flush_rewrite_rules();
 global $wp_rewrite;
 $wp_rewrite->flush_rules();
}
 
add_filter('generate_rewrite_rules', 'restful_web_services_add_rewrite_rules');
function restful_web_services_add_rewrite_rules( $wp_rewrite ) {
  $new_rules = array(
    RESTFUL_WEB_SERVICES_URL_PATTERN => null,
  );
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
 
function restful_web_services_exec_service() {
  global $wp;
  if ($wp->matched_rule==RESTFUL_WEB_SERVICES_URL_PATTERN) {
    if ($wp->request == 'apievent') {

      echo __('This is correct url','eventissimo');
	  echo "<ul><li>";
	  echo __('List All Events Feed Rss','eventissimo') . " " . get_bloginfo('wpurl') . '/apievent/get?type=rss';
	 echo " <li>";
	  echo __('List All Events Json','eventissimo') . " " . get_bloginfo('wpurl') . '/apievent/get?type=json';
	  echo "</li>";
	  
	  echo "</li></ul>";
	  
	  echo "<h2>" . __("Parameters","eventissimo") . "</h2>";
	  
	  echo "<table width='100%' border='1'>";
	  echo "<tr><td width='25%'><b>" . __("Parameters","eventissimo") . "</b></td><td width='25%'><b>" . __("Allowed values","eventissimo") . "</b></td><td ><b>" . __("Example","eventissimo") . "</b></td></tr>";
	  
	  echo "<tr><td>type</td><td>JSON | RSS</td><td>" . get_bloginfo('wpurl') . "/apievent/get?type=rss</td></tr>";
	  
	  echo "<tr>
	  			<td>search</td>
				<td>id_author | description | title</td>
				<td rowspan='2'>" . get_bloginfo('wpurl') . "/apievent/get?type=rss&search=id_author&value=1</td>
			</tr>
			<tr>
				<td>value</td>
				<td>" . __("Term of search","eventissimo") . "</td>
			</tr>

		</td></tr>";
			
	  
	  	echo "<tr><td>defined</td><td>TODAY | MONTH</td><td>" . get_bloginfo('wpurl') . "/apievent/get?type=rss&defined=TODAY</td></tr>";
		
		echo "<tr><td>view</td><td>OLD | NEXT</td><td>" . get_bloginfo('wpurl') . "/apievent/get?type=rss&view=NEXT</td></tr>";
	  
	  echo "</table>";
	  
	  echo "<h2>" . __("Structure Json","eventissimo") . "</h2>";
	  echo '<code>[<br/>
    	{<br/>
        "id": 123,<br/>
        "classname": 123,<br/>
        "randomColor": "#C913D5",<br/>
        "id_author": "1",<br/>
        "description": "test events for json",<br/>
        "title": "Test event",<br/>
        "url": "http://www.example.it/events/tests",<br/>
        "thumbs": ' . htmlspecialchars("<img width=\"150\" height=\"150\" src=\"http://www.example.it/wp-content/uploads/2014/02/example150x150.jpg\" class=\"attachment-thumbnail wp-post-image\" alt=\"Example\" />") . ',<br/>
        "cover":' . htmlspecialchars("<img width=\"690\" height=\"300\" src=\"http://www.example.it/wp-content/uploads/2014/02/example90x300.jpg\" class=\"attachment-fb_cover_image wp-post-image\" alt=\"Example\" />") . ',<br/>
        "coverBig":' . htmlspecialchars("<img width=\"690\" height=\"500\" src=\"http://www.example.it/wp-content/uploads/2014/02/example.jpg\" class=\"attachment-large wp-post-image\" alt=\"Example\" />") . ',<br/>
        "types": [<br/>
            {<br/>
                "term_id": 1,<br/>
                "name": "test1",<br/>
                "slug": "test1",<br/>
                "term_group": 0,<br/>
                "term_taxonomy_id": 1,<br/>
                "taxonomy": "typeEvents",<br/>
                "description": "",<br/>
                "parent": 0,<br/>
                "count": 1,<br/>
                "filter": "raw"<br/>
            }<br/>
        ],<br/>
        "categories": [<br/>
            {<br/>
                "term_id": 2,<br/>
                "name": "test2",<br/>
                "slug": "test2",<br/>
				"term_group": 0,<br/>
                "term_taxonomy_id": 2,<br/>
                "taxonomy": "eventscategories",<br/>
                "description": "",<br/>
                "parent": 0,<br/>
                "count": 1,<br/>
                "filter": "raw"<br/>
            },<br/>
            {<br/>
                "term_id": 3,<br/>
                "name": "test3",<br/>
                "slug": "test3",<br/>
                "term_group": 0,<br/>
                "term_taxonomy_id": 3,<br/>
                "taxonomy": "eventscategories",<br/>
                "description": "",<br/>
                "parent": 0,<br/>
                "count": 1,<br/>
                "filter": "raw"<br/>
            }<br/>
        ],<br/>
        "date_begin": "1391904000",<br/>
        "date_end": "1391904000",<br/>
        "hour_begin": "00:00",<br/>
        "hour_end": "03:00"<br/>
    },<br/> {..}<br/>]</code>';
	  
	  
	  
    } else {
      list($dummy,$service_name) = explode('/',$wp->request);
      if (file_exists($service_php = (RESTFUL_WEB_SERVICES_DIR . '/apievent/' . $service_name . '.php'))) {
        include_once $service_php;
      } else {
      
        status_header(404);
        echo __('404 - Service not found.','eventissimo');
		echo '<br/>';
		echo __('To read','eventissimo') .  ": " .  get_bloginfo('wpurl') . '/apievent/';
      }
    }
    exit;
  }
}