<?php
/*
Plugin Name: Scrnshots Feed Widget
Plugin URI: http://www.mattvarone.com/wordpress/scrnshots-plugin-for-wordpress/
Description: Allows you to integrate the screenshots from a ScrnShots.com into your site.
Version: 1.0
License: GPLv2
Author: Matt Varone
Author URI: http://www.mattvarone.com

Copyright 2011  (email: contact@mattvarone.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
* Scrnshots Feed Initialize
*
* @package Scrnshots Feed
* @author Matt Varone
*/
		
/*
|--------------------------------------------------------------------------
| SCRNSHOTS FEED WIDGET CONSTANTS
|--------------------------------------------------------------------------
*/

define('MV_SCRNSHOTS_FEED_BASENAME', plugin_basename(__FILE__));
define('MV_SCRNSHOTS_FEED_URL', plugins_url('',__FILE__));
define('MV_SCRNSHOTS_FEED_PATH', plugin_dir_path(__FILE__));
define('MV_SCRNSHOTS_FEED_VERSION', '1.0');
define('MV_SCRNSHOTS_FEED_FOLDER', basename(dirname(__FILE__)));
define('MV_SCRNSHOTS_FEED_DOMAIN', 'scrnshots-feed');
define('MV_SCRNSHOTS_FEED_TRANSIENT','mv.scrnshots.feed.json');

/*
|--------------------------------------------------------------------------
| SCRNSHOTS FEED INTERNALIZATION
|--------------------------------------------------------------------------
*/

load_plugin_textdomain( MV_SCRNSHOTS_FEED_DOMAIN, false, '/'.MV_SCRNSHOTS_FEED_FOLDER.'/lan' );

/*
|--------------------------------------------------------------------------
| SCRNSHOTS FEED INCLUDES
|--------------------------------------------------------------------------
*/

require_once(MV_SCRNSHOTS_FEED_PATH.'inc/scrnshots-feed-widget.php');

/*
|--------------------------------------------------------------------------
| SCRNSHOTS FEED FUNCTIONS
|--------------------------------------------------------------------------
*/


function mv_get_scrnshots_feed($args=array())
{
	// set defaults
	$defaults = array(
		'num_items' => 3,
		'imagesize' => 'medium',
		'id_name' => 'sksmatt',
		'rel'=>'',
		'list'=>'',
	);
	
	// parse with supplied options
	$options = wp_parse_args( $args, $defaults );
	
	// get transient
	$scrnshots = get_transient(MV_SCRNSHOTS_FEED_TRANSIENT.$options['id_name']);
		
	// if no transient
	if (!$scrnshots)
	{
		
		// get the feed
		$scrnshots = wp_remote_get('http://www.scrnshots.com/users/' . $options['id_name'] . '/screenshots.json');

		// check for errors
		if (!is_wp_error($scrnshots))
			set_transient(MV_SCRNSHOTS_FEED_TRANSIENT.$options['id_name'],$scrnshots);
		else {
			   $error_string = $scrnshots->get_error_message();
			   return '<div id="message" class="error"><p>' . $error_string . '</p></div>';
		}
	}

	// decode json content
	$json_scrnshots = json_decode($scrnshots['body']);	
	// count results
	$json_total = count( $json_scrnshots );
	// set the max num of items
	$options['num_items'] = ($options['num_items']>$json_total) ? $json_total : $options['num_items'];
	// set a default title 
	$default_title = __('Screenshot from ScrnShots.com',MV_SCRNSHOTS_FEED_DOMAIN);
	// check rel attr
	$rel = ($options['rel'] ) ? ' rel="nofollow" ' : '' ;
	// initialize output
	$out = "";
	
	// check first there is enough items
	if ( $json_total > 0 && $options['num_items'] > 0 ) 
	{
		$out .= "<ul class=\"scrnshots-feed\">";
			
		// loop trough items
		for ( $i=0; $i < $options['num_items'] ; $i++ )
		{
			// get the image url
			$imgurl = $json_scrnshots[$i]->images->$options['imagesize'];
			// get the image link
			$url = $json_scrnshots[$i]->url;
			// get the image title
			$title = ( $json_scrnshots[$i]->description == null ) ? $title = $default_title : $title = str_replace( "\"","'", $json_scrnshots[$i]->description );
			// add the image to the output
			$out .=  "<li><a href=\"$url\" title=\"$title\" $rel ><img src=\"$imgurl\" alt=\"$title\" /></a></li>";    

		}
		
		$out .= "</ul>";
	}
	
	return $out;
}