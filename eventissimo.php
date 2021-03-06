<?php

/*
Plugin Name: Eventissimo
Plugin URI: http://plugin.digitalissimoweb.it/eventissimo/
Description: Create and organize events into your site. Your events also automatically automatically created on Facebook and import FB's events.
Version: 1.4.3
Author: Digitalissimo
Author URI: http://plugin.digitalissimoweb.it
License: GPLv2 or later
*/

/*  Copyright 2014  Digitalissimo  (email : developer@digitalissimoweb.it)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
	
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define("FACEBOOOK_API_KEY",get_option("wp_fbAppId"));
define("FACEBOOK_SECRET_KEY",get_option("wp_fbprivateKey"));
define("FACEBOOK_PUBLICATEFEEDFB",get_option("wp_publicatefeedFB"));
define ("BASE_URL",dirname(__FILE__));
define ("BASE_URI_IMAGES",plugins_url("images",__FILE__));
define ("BASE_URL_NOIMAGES",plugins_url("images/no-image.png",__FILE__));
define ("BASE_URL_NOIMAGES_COVER",plugins_url("images/no-image-cover.png",__FILE__));
include ("function/wejnswpwhitespacefix.php");
include ("function/shortcode.php");
include ("function/widget.php");
include ("function/single_template.php");
include ("frontend.php");
include ("function.php");
include ("function_fb.php");
include ("backend.php");
include ("endpoint.php");
include ("meta_post/data_events.php");
include ("meta_post/location_events.php");
include ("meta_post/facebook_events.php");
include ("function/duplicatePost.php");
include ("function/repeatingEvent.php");
include ("function/eventFB.php");
include ("meta_post/images_events.php");
include ("call_function/listEvents.php");
include ("call_function/calendar.php");

load_plugin_textdomain( 'eventissimo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );



/* What to do when the plugin is activated? */
register_activation_hook(__FILE__,'eventissimo_install');
/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'eventissimo_remove');

add_action( 'admin_init', 'eventissimo_setting');
function eventissimo_setting() {
	flush_rewrite_rules();
  register_setting('eventissimo-group', 'wp_locationCityDefault');
  add_option( 'wp_locationCityDefault','Italy'); 

  register_setting('eventissimo-group', 'wp_locationAdressDefault');
  add_option( 'wp_locationAdressDefault',''); 
  
  register_setting('eventissimo-group', 'wp_locationPosition');
  add_option( 'wp_locationPosition','');
  
  register_setting('eventissimo-group', 'wp_locationUrl');
  add_option( 'wp_locationUrl','');
  
  register_setting('eventissimo-group', 'wp_locationZoom');
  add_option( 'wp_locationZoom','15'); 
  
  register_setting('eventissimo-group', 'wp_fbUser');
  add_option( 'wp_fbUser',''); 

  register_setting('eventissimo-group', 'wp_fbAppId');
  add_option( 'wp_fbAppId',''); 

  register_setting('eventissimo-group', 'wp_fbprivateKey');
  add_option( 'wp_fbprivateKey','');
  
  register_setting('eventissimo-group', 'wp_publicatefeedFB');
  add_option( 'wp_publicatefeedFB','no');
  
  register_setting('eventissimo-group', 'wp_ownerEventFB');
  add_option( 'wp_ownerEventFB','user');
  
  register_setting('eventissimo-group', 'wp_publicatePageId');
  add_option( 'wp_publicatePageId','');
  
  register_setting('eventissimo-group', 'UseSingleTemplateDefault');
  add_option( 'UseSingleTemplateDefault','YES');
  
  register_setting('eventissimo-group', 'wp_order_singleevent');
  add_option('wp_order_singleevent','evidenceimg,title,category,author,date,description,gallery,maps');


  register_setting('eventissimo-group', 'wp_view_singleevent');
  add_option('wp_view_singleevent',array('evidenceimg','title','category','author','date','description','allery','maps'));
  
} 

function eventissimo_install() {
  flush_rewrite_rules();
}
function eventissimo_remove() {
  flush_rewrite_rules();

  delete_option('wp_locationCityDefault');
  delete_option('wp_locationAdressDefault');
  delete_option('wp_locationPosition');
  delete_option('wp_locationUrl');
  
  delete_option('wp_fbUser');
  delete_option('wp_fbAppId');
  delete_option('wp_fbprivateKey');
  delete_option('wp_fbPages_publicKey');
  delete_option('wp_publicatePageId');
  
  delete_option('wp_publicatefeedFB');
  delete_option('wp_ownerEventFB');
  
  delete_option('UseSingleTemplateDefault');
  delete_option('wp_order_singleevent');
  delete_option('wp_view_singleevent');

}

//Added taxonomy Events
add_action( 'init', 'create_eventissimo_taxonomies', 0 );
function create_eventissimo_taxonomies() {
	flush_rewrite_rules();
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => __( 'Event types', 'eventissimo' ),
		'singular_name'     => __( 'Event type', 'eventissimo' ),
		'search_items'      => __( 'Search Event types', 'eventissimo' ),
		'all_items'         => __( 'All Event types', 'eventissimo' ),
		'parent_item'       => __( 'Parent Event types' , 'eventissimo'),
		'parent_item_colon' => __( 'Parent Event types:', 'eventissimo' ),
		'edit_item'         => __( 'Edit Event types', 'eventissimo' ),
		'update_item'       => __( 'Update Event types' , 'eventissimo'),
		'add_new_item'      => __( 'Add New Event types' , 'eventissimo'),
		'new_item_name'     => __( 'New Types Event types', 'eventissimo' ),
		'menu_name'         => __( 'Event types' , 'eventissimo'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
	);

	register_taxonomy( 'typeEvents', array( 'event' ), $args );
	
	$labels = array(
		'name'              => __( 'Event categories', 'eventissimo' ),
		'singular_name'     => __( 'Event category', 'eventissimo' ),
		'search_items'      => __( 'Search Event categories', 'eventissimo' ),
		'all_items'         => __( 'All Event categories', 'eventissimo' ),
		'parent_item'       => __( 'Parent Event categories' , 'eventissimo'),
		'parent_item_colon' => __( 'Parent Event categories:', 'eventissimo' ),
		'edit_item'         => __( 'Edit Event categories', 'eventissimo' ),
		'update_item'       => __( 'Update Event categories' , 'eventissimo'),
		'add_new_item'      => __( 'Add New Event categories' , 'eventissimo'),
		'new_item_name'     => __( 'New Event categories', 'eventissimo' ),
		'menu_name'         => __( 'Event categories' , 'eventissimo'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true
	);

	register_taxonomy( 'eventscategories', array( 'event' ), $args );
}

//Post type
add_action( 'init', 'eventissimo_post_type' );
function eventissimo_post_type() {

	register_post_type( 'events',
		array(
			'labels' => array(
				'name' =>  __('Events','eventissimo') ,
				'singular_name' =>  __('Event','eventissimo') ,
				'add_new_item' => __('Add new Event','eventissimo') ,
				'edit_item' =>	__('Edit Event','eventissimo') ,
				'new_item' => 	__('New Event','eventissimo') ,
				'view_item' => __('View Event','eventissimo') ,
				'search_items' => __('Search Event','eventissimo') ,
				'not_found' => __('No events found.','eventissimo') ,
				'not_found_in_trash' => __('No events found in Trash.','eventissimo')
			),
			'public' => true,
			'taxonomies' => array('typeEvents', 'eventscategories','rolesRepeatWeek'), 
			'has_archive' => false,
		
			'rewrite' => array( 'slug' => 'event','with_front' => FALSE),
			'menu_icon' => plugins_url("/images/icon_menu.png",__FILE__),
			'supports' => array('title','thumbnail')
		)
	);
		flush_rewrite_rules();
}

function events_menu(){
    add_submenu_page( 'edit.php?post_type=events', __("Settings","eventissimo"), __("Settings","eventissimo"), 'manage_options', 'eventissimo_setting', 'eventissimo_view_settings' );
	
	add_submenu_page( 'edit.php?post_type=events', __("Import FB events","eventissimo"), __("Import FB events","eventissimo"), 'publish_posts', 'eventissimo_fbupdating', 'eventissimo_fbupdating' );
	
	add_action( 'admin_init', 'eventissimo_setting');
}
add_action( 'admin_menu', 'events_menu' );



add_filter( 'manage_events_posts_columns', 'eventissimo_columns' );
add_action( 'manage_events_posts_custom_column' , 'eventissimo_custom_column', 10, 2 );
add_filter( 'manage_edit-events_sortable_columns' , 'eventissimo_columns_sort');

function eventissimo_columns($columns) {

	unset ($columns['date']);
	
	if (is_admin()){
    	$columns['author'] = __( 'Author');
	}
	
	$columns['date_events'] = __( 'Date Event',"eventissimo");
	$columns['location_events'] = __( 'Location',"eventissimo");
    return $columns;
}

function eventissimo_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'date_events' :
			$dataInizio = get_post_meta( $post_id , 'data_inizio' , true );
			$dataFine = get_post_meta( $post_id , 'data_fine' , true );
			if ($dataInizio!=""){
			
				// if EveryYear or dayRepeat
				$everyYearRepeat = get_post_meta( $post_id , 'EveryYear' , true );
				$dayRepeat = get_post_meta( $post_id , 'dayRepeat' , true );
				$alldate = get_post_meta( $post_id , 'allDateRepeat' , true );
				$dayRepeatDate = !is_array(get_post_meta( $post_id , 'dayRepeatSelect' , true )) ? unserialize(get_post_meta( $post_id , 'dayRepeatSelect' , true )) : "";
				$dayRepeatMon = !is_array(get_post_meta( $post_id , 'dayRepeatMount' , true )) ? unserialize(get_post_meta( $post_id , 'dayRepeatMount' , true )) : "";
				
				$util_repleat = "";
				if (($everyYearRepeat) || ($dayRepeat)){
					$nameEvent = get_the_title($post_id);
					eventissimo_first_date_repeat($dataInizio,$dataFine,unserialize($alldate));
					
					$dataUntil = date_i18n("Y-m-d" ,get_post_meta( $post_id , 'untilRepeat' , true ));
					$typeRepeating = "onlyDay";
					if ($everyYearRepeat=="") {
						$typeRepeating = "manyDays";
					}
					
					$dayRepeatSelect =  implode(",",$dayRepeatDate);	
					$dayRepeatMount = implode(",",$dayRepeatMon);					
					$util_repleat .= "<br/>";
					$data_inizio = date_i18n("Y-m-d" ,get_post_meta( $post_id , 'data_inizio' , true ));
					$util_repleat .= '<a href="javascript:void(0);" onClick="viewCalendarColorbox(\'' . $nameEvent . '\',\'' . $data_inizio . '\',\'' . $dataUntil . '\',\'' . $typeRepeating . '\',\'' . $dayRepeatSelect . '\',\'' . $dayRepeatMount . '\',\'\')">';
					$util_repleat .= __("View all days repeat until","eventissimo");
					$util_repleat .= " ";
					$util_repleat .= date_i18n(get_option('date_format'),get_post_meta( $post_id , 'untilRepeat' , true ));
					$util_repleat .= '</a>';
				}
				
			}
			
			echo date_i18n(get_option('date_format') ,$dataInizio) ; 
			echo " ";
			echo get_post_meta( $post_id , 'ora_inizio' , true ); 
			echo "<br/>";
			echo date_i18n(get_option('date_format') ,$dataFine) ; 
			echo " ";
			echo get_post_meta( $post_id , 'ora_fine' , true ); 
			
			echo $util_repleat;
        break;
		
		case 'location_events' :
            echo get_post_meta( $post_id , 'address' , true ); 
			echo "<br/>";
			echo get_post_meta( $post_id , 'city' , true ); 
			
				
            break;

    }
}

// Sort admin cols
function eventissimo_columns_sort($columns) {
	$custom = array(
		'date_events'    => 'data_inizio',
		'location_events'    => 'address'
		);
	return wp_parse_args($custom, $columns);
}



//Jquery and Css
add_action('init', 'eventissimo_install_jquery');
function eventissimo_install_jquery() {
	global $post, $wp_locale;
	wp_enqueue_script('jquery');
	
	if (is_admin()){
		wp_enqueue_script( 'jquery-eventissimo', plugins_url('js/eventissimo.js', __FILE__));
	} else {
		wp_enqueue_script( 'jquery-eventissimo', plugins_url('js/eventissimo_public.js', __FILE__));
	}
	
	//Facebook
	if (is_admin()){
		wp_enqueue_script( 'jquery-scriptPf',"http://connect.facebook.net/en_US/all.js");
		wp_enqueue_script( 'jquery-eventissimoFB', plugins_url('js/eventissimo_fb.js', __FILE__));
	}
	
	//Slideshow Cycle2
	if (!is_admin()){
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery-cycle2', plugins_url('plugin/cycle/jquery.cycle2.min.js', __FILE__));
		wp_enqueue_script( 'jquery-cycle2-autoheight', plugins_url('plugin/cycle/jquery.cycle2.autoheight.min.js', __FILE__));
	}
	//Pagination
	if (!is_admin()){
		wp_enqueue_script( 'bootstrap-pagination',plugins_url('plugin/pagination/bootstrap.min.js', __FILE__));
		wp_enqueue_style('bootstrap-style-pagination', plugins_url('plugin/pagination/bootstrap.css', __FILE__));
		wp_enqueue_script( 'bootstrap-stype-pagination2', plugins_url('plugin/pagination/bootstrap-paginator.js', __FILE__));
	}
	
	//MAPS
	wp_enqueue_script( 'jquery-apigoogle', 'http://maps.google.com/maps/api/js?sensor=true&amp;language=' . __("en","eventissimo"));
	wp_enqueue_script( 'jquery-maps', plugins_url('plugin/maps/gmap3.min.js', __FILE__));

	//JQUERY UI (FOR DATEPICKER)	
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-sortable');
	wp_register_style('wimtvproCssCore',plugins_url('plugin/css/redmond/jquery-ui-1.8.21.custom.css', __FILE__));
    wp_enqueue_style('wimtvproCssCore');
	
	wp_enqueue_script( 'jquery-sortableFixed', plugins_url('js/fixedSortable.js', __FILE__));
	
	if (is_admin()){	
		//TIME PICKER
		wp_enqueue_script( 'jquery-timepicker', plugins_url('plugin/timepicker/jquery.timepicker.js', __FILE__));
		wp_enqueue_style('jquery-style-timepicker', plugins_url('plugin/timepicker/jquery.timepicker.css', __FILE__));
	}

	//COLORBOX

	wp_enqueue_script( 'jquery-colorbox', plugins_url('plugin/colorbox/jquery.colorbox.js', __FILE__));
	wp_enqueue_style('jquery-style-colorbox', plugins_url('plugin/colorbox/jquery.colorbox.css', __FILE__));

	//CALENDAR
	wp_enqueue_script( 'jquery-fullcalendar', plugins_url('plugin/calendarajax/fullcalendar.js', __FILE__));
	if (is_admin())
		wp_enqueue_style('jquery-style-fullcalendar', plugins_url('plugin/calendarajax/fullcalendar.css', __FILE__));
	else
		wp_enqueue_style('jquery-style-fullcalendar', plugins_url('plugin/calendarajax/fullcalendar.css', __FILE__));
	
	
		
    //add our instantiator js
	if (is_admin()){

		$pageGet = isset($_GET["page"]) ? $_GET["page"] : "";
		
		if ($pageGet!="eventissimo_fbupdating"){
			wp_enqueue_script( 'jquery-eventissimo-admin', plugins_url('js/dateL10n.js', __FILE__));

			//localize our js
			$aryArgs = array(
			'closeText'         => __( 'Done', 'eventissimo'),
			'currentText'       => __( 'Today', 'eventissimo'),
			'monthNames'        => eventissimo_strip_array_indices( $wp_locale->month ),
			'monthNamesShort'   => eventissimo_strip_array_indices( $wp_locale->month_abbrev ),
			'monthStatus'       => __( 'Show a different month','eventissimo'),
			'dayNames'          => eventissimo_strip_array_indices( $wp_locale->weekday ),
			'dayNamesShort'     => eventissimo_strip_array_indices( $wp_locale->weekday_abbrev ),
			'dayNamesMin'       => eventissimo_strip_array_indices( $wp_locale->weekday_initial ),
			// set the date format to match the WP general date settings
			'dateFormat'        => eventissimo_date_format_wp_to_js( get_option( 'date_format' ) ),
			'timeFormat'        => eventissimo_time_format_wp_to_js( get_option( 'time_format' ) ),
			);
 
			// Pass the array to the enqueued JS
			wp_localize_script( 'jquery-eventissimo-admin', 'objectL10n', $aryArgs );
		}
	}
	
	//EVENTISSIMO STYLE
	if (is_admin()){
		wp_register_style( 'eventsCss', plugins_url('css/eventissimo.css', __FILE__) );
		wp_enqueue_style('eventsCss');
	} else {
		wp_register_style( 'eventsCss', plugins_url('css/eventissimo_public.css', __FILE__) );
		wp_enqueue_style('eventsCss');
		
		
		wp_register_style( 'fontawesome', plugins_url('plugin/fontawesome/css/font-awesome.min.css', __FILE__) );
		wp_enqueue_style('fontawesome');
		
	}
}
function eventissimo_jsInline() {
    echo '<script type="text/javascript">
	var maxZoom ="' . get_option('wp_locationZoom') . '";
	var iconMarker = "' . plugins_url("images/pin.png",__FILE__) . '";
	var url_pathPlugin ="' . plugin_dir_url(__FILE__) . '";
	var admin_ajax ="' . admin_url('admin-ajax.php') . '";
	</script>';
}
add_action('wp_head', 'eventissimo_jsInline');
add_action('admin_head', 'eventissimo_jsInline');


function eventissimo_view_settings(){
 echo '<div class="wrap">';
 screen_icon(); 
 echo '<h2>' . __ ("Settings Plugin Eventissimo","eventissimo") . '</h2>';
 
 echo '<form method="post" action="options.php">';
	settings_fields('eventissimo-group'); 
	do_settings_sections('eventissimo-group'); 
	
	$valueMapsDefault = get_option('wp_locationCityDefault') . " " . get_option('wp_locationAdressDefault'); 
	
	
	
 ?>
 
		<script>
			jQuery(document).ready(function() {
				
				var address = jQuery("#city").val() + " " + jQuery("#address").val();
				getLangLat(address,"",maxZoom);

				
				jQuery("#updateMaps").click(function(){
					var address = jQuery("#city").val() + " " + jQuery("#address").val();
					getLangLat(address,'',jQuery("#zoom").val());
				});
				
    			jQuery( "#sortable" ).sortable({
					deactivate: function( event, ui ) {
						sortedIDs=jQuery( "#sortable" ).sortable("toArray");
						//console.log(sortedIDs);
						jQuery("#wp_order_singleevent").val(sortedIDs);
					}
				});
				


				
			});
		</script>

				<h3 class="hndle" id="menu"><?php _e("Config map","eventissimo")?></h3>
				
					<table class="form-table">
                        <tr valign="top">
                        
							<td>
							
								<table>
							
									<tr valign="top">
									<th scope="row"><?php _e("Location Default","eventissimo");?></th>
									<td><input type="text" id="city" name="wp_locationCityDefault" value="<?php echo get_option('wp_locationCityDefault'); ?>" /></td>
									</tr>
									 
									<tr valign="top">
									<th scope="row"><?php _e("Address Default","eventissimo");?></th>
									<td><textarea id="address" name="wp_locationAdressDefault"><?php echo get_option('wp_locationAdressDefault'); ?></textarea>
                                    
                                    </td>
									</tr>
                                    
                                    
									
									<tr valign="top">
									<th scope="row"><?php _e("Zoom default","eventissimo");?></th>
									<td><input type="text" id="zoom" name="wp_locationZoom" value="<?php echo get_option('wp_locationZoom'); ?>" />
                                    <br/><strong id="updateMaps"><?php _e("Update Maps","eventissimo")?></strong>
                                    
                                    </td>
									</tr>
								
								</table>
								
                                
							</td>
						
							<td>
							
								<h5><?php _e("Move the placemark in the desired location on the map","eventissimo");?></h5>
							
								<input type="hidden" name="latlongMaps" id="latlongMaps" value="<?php echo $latlongMaps; ?>"/>
		    			
								<div id="maps"></div>
						
							</td>
						
						</tr>
						
                    </table>
           
           <h3><?php _e("Single Events Configuration","eventissimo")?></h3>
		   
           <table class="form-table">
                    
                    <tr>
                    	<td width="50%">
                        	<label><?php _e("Use Single Template Default of Plugin","eventissimo")?></label>
                       		
                        <?php
                        	$usesingle =get_option('UseSingleTemplateDefault');
                        ?>
                            <select name="UseSingleTemplateDefault">
                                <option value="YES" <?php 
                                if ($usesingle=="YES") echo "selected='selected'";
                                ?>><?php echo __("Yes","eventissimo")?></option>
                                
                                 <option value="NO" <?php 
                                if ($usesingle=="NO") echo "selected='selected'";
                                ?>><?php echo __("No","eventissimo")?></option>
                              </select>
                              
                              <p><?php _e("If you do not use the default page of the plugin creates single-events.php in the theme directory and copy this php code","eventissimo");?></p>
							<code>$orders_single= explode(",",get_option('wp_order_singleevent'));</code><br/>
                            <code>while ( have_posts() ) : the_post();</code><br/>							
                            <code>eventissimo_get_template_single($orders_single);</code><br/>
                           
                            <code>endwhile;</code><br/>
                              
						</td>
                    </tr>
                    <tr>    
                        <td width="100%">
                       	 <label><?php _e("Change order of arrangement of the elements of the page","eventissimo")?> (single-events.php)</label>
                         <input type="hidden" value="<?php echo get_option('wp_order_singleevent');?>" id="wp_order_singleevent" name="wp_order_singleevent">;
                         <ul id="sortable">
                         <?php
							
						 	$orderArray = explode(",",get_option('wp_order_singleevent'));
							
							$orderArrayView = get_option('wp_view_singleevent');
							
							foreach ($orderArray as $key){
								
								if ($key=="description")
									$text = __("Description","eventissimo");
								elseif ($key=="maps")
									$text = __("Google Maps","eventissimo");
								elseif ($key=="evidenceimg")
									$text = __("Cover image","eventissimo");
								else
									$text= __(ucfirst($key),"eventissimo");
								
								echo "<li id='" . $key . "' class='ui-state-default'>";
								
								echo "<input type='checkbox' value='" . $key . "' name='wp_view_singleevent[]'";
								if (in_array($key, $orderArrayView))  echo "checked='checked'";
								echo ">";
								echo "<span class='ui-icon ui-icon-arrowthick-2-n-s'></span>" . __(ucfirst($text),"eventissimo");
								
								echo "</li>";
							}
						 ?>
                         
                        </ul></td>
                        
                    </tr>
				</table>
            <h3><?php _e("Config Facebook","eventissimo")?></h3>
            
            <p>
			<?php _e("Create your App and get Id and Secret at","eventissimo");?> 
            <a href='https://developers.facebook.com/apps' target="_new">https://developers.facebook.com/apps</a></p>
            
          
                <table class="form-table">
                    
                    <tr>
                    	<td width="50%">
                            <table>
                               
                                 
                                 <tr valign="top">
                                    <th scope="row">
                                        <?php _e("Self-publish on FB profile of the user who creates the event?","eventissimo");?>    
                                        
                                        
                                    </th>
                                    <td>
                                    <?php 
									$autopublicate =get_option('wp_publicatefeedFB');
									?>
                                    <select name="wp_publicatefeedFB">
                                    	<option value="user" <?php 
										if ($autopublicate=="user") echo "selected='selected'";
										?>><?php echo __("Yes","eventissimo")?>
                                        
                                         <option value="no" <?php 
										if ($autopublicate=="no") echo "selected='selected'";
										?>><?php echo __("No","eventissimo")?>
                                    </select>
                                    
                                    </td>
                                    
                                 </tr>
                                  
                                  <tr valign="top">
                                    <th scope="row">
                                        <?php _e("Self-publish with a FB page (if you are the permission). Id Page (numeric):","eventissimo");?>    
                                        
                                        
                                    </th>
                                   
                                    <td ><input type="text" id="publicatePageId" name="wp_publicatePageId" value="<?php echo get_option('wp_publicatePageId'); ?>" width="100%"/>
                                            </td>
                                 </tr>
                                    
                                 </tr>
                                             
                                 <tr valign="top">
                                            <th scope="row"><?php _e("App ID","eventissimo");?></th>
                                            <td ><input type="text" id="fbAppId" name="wp_fbAppId" value="<?php echo get_option('wp_fbAppId'); ?>" width="100%"/>
                                            </td>
                                 </tr>
                                 
                                 <tr valign="top">
                                            <th scope="row"><?php _e("App Secret","eventissimo");?></th>
                                            <td ><input type="text" id="wp_fbprivateKey" name="wp_fbprivateKey" value="<?php echo get_option('wp_fbprivateKey'); ?>" width="100%"/>
                                            </td>
                                 </tr>
        
                              </table>
						</td>
                        
                        <td>
                        
                        	<img src="<?php echo plugins_url("images/exfb.png",__FILE__);?>" alt="<?php echo __("Example pages App Facebook","eventissimo")?>" height="80%">
                        
                        </td>
                     
                </table>
           

	  <?php submit_button();    ?> 
</form>
<?php

 echo '</form></div>';
 
}


function eventissimo_delete_function($postid){
    // We check if the global post type isn't ours and just return
    global $post_type;   
    if ( $post_type != 'events' ) return;
	$idEventFfb = get_post_meta( get_the_ID(), 'idEventFfb', true );
	// check if the custom field has a value
	if( ! empty($idEventFfb) ) {
	  deleteEventsFacebook($idEventFfb);
	} 
    // My custom stuff for deleting my custom post type here
}

add_action('before_delete_post', 'eventissimo_delete_function');

function eventissimo_sizes_images() {
    add_image_size( 'fb_cover_image', 720, 300, true );
}
add_action( 'init', 'eventissimo_sizes_images' );
 
function eventissimo_sizes_choose($sizes) {
    $sizes['fb_cover_image'] = __( 'Facebook Cover', 'eventissimo' );
    return $sizes;
}
add_filter('image_size_names_choose', 'eventissimo_sizes_choose');

