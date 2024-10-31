<?php

/**
* Scrnshots Feed Widget
*
* @package Scrnshots Feed
* @author Matt Varone
*/

include_once(MV_SCRNSHOTS_FEED_PATH.'/inc/wph-widget-class.php');

if (!class_exists('MV_Scrnshots_Feed_Widget')) 
{
	class MV_Scrnshots_Feed_Widget extends WPH_Widget
	{
	
		function __construct()
		{
		
			$args = array(
				'label' => __('Scrnshots Feed',MV_SCRNSHOTS_FEED_DOMAIN),
				'description' => __('Displays the latest items from www.scrnshots.com',MV_SCRNSHOTS_FEED_DOMAIN),		
			);

			$args['fields'] = array(							
			
				array(		
				'name' => __('Title',MV_SCRNSHOTS_FEED_DOMAIN),		
				'desc' => __('Enter the widget title.',MV_SCRNSHOTS_FEED_DOMAIN),
				'id' => 'title',
				'type'=>'text',	
				'class' => 'widefat',	
				'std' => __('My Scrnshots',MV_SCRNSHOTS_FEED_DOMAIN),
				'validate' => 'alpha_dash',
				'filter' => 'strip_tags|esc_attr'	
				),
				
				array(		
				'name' => __('User ID Name',MV_SCRNSHOTS_FEED_DOMAIN),		
				'desc' => __('Enter the user id eg. \'sksmatt\'.',MV_SCRNSHOTS_FEED_DOMAIN),
				'id' => 'id_name',
				'type'=>'text',	
				'class' => '',	
				'validate' => 'alpha_dash',
				'filter' => 'strip_tags|esc_attr'	
				),
				
				array(
				'name' => __('Size',MV_SCRNSHOTS_FEED_DOMAIN),							
				'desc' => __('Select the sizes to show.',MV_SCRNSHOTS_FEED_DOMAIN),
				'id' => 'imagesize',							
				'type'=>'select',				
				'fields' => array(								
						array( 
							'name'  => __('Small',MV_SCRNSHOTS_FEED_DOMAIN),
							'value' => 'small' 						
						),
						array( 
							'name'  => __('Medium',MV_SCRNSHOTS_FEED_DOMAIN),			
							'value' => 'medium' 					
						),
						array( 
							'name'  => __('Large',MV_SCRNSHOTS_FEED_DOMAIN),
							'value' => 'large'	
						),
						array( 
							'name'  => __('Full Size',MV_SCRNSHOTS_FEED_DOMAIN),
							'value' => 'fullsize'	
						)
				),
				'validate' => 'validate_sizes',
				'filter' => 'strip_tags|esc_attr',
				),
				
				array(
				'name' => __('Amount',MV_SCRNSHOTS_FEED_DOMAIN),							
				'desc' => __('Number of images to show.',MV_SCRNSHOTS_FEED_DOMAIN),
				'id' => 'num_items',							
				'type'=>'text',
				'std' => 3,
				'validate' => 'numeric',
				'filter' => 'strip_tags|esc_attr',
				),
				
				array(
				'name' => __('No Follow links',MV_SCRNSHOTS_FEED_DOMAIN),							
				'desc' => __('Adds rel="nofollow" to links.',MV_SCRNSHOTS_FEED_DOMAIN),
				'id' => 'rel',							
				'type'=>'checkbox',				
				'std' => true,
				'value' => 'yes',
				'filter' => 'strip_tags|esc_attr',
				),
			
			);

			$this->create_widget( $args );
		}
		
		function validate_sizes($sizes)
		{
			
			$sizes_array = array('small','medium','large','fullsize');
			
			if ( !in_array($sizes, $sizes_array))
				return false;
			else
				return true;
		}
		
		
		function widget($args,$instance)
		{
		
			$out  = $args['before_title'];
			$out .= $instance['title'];
			$out .= $args['after_title'];

			$args = array();
			
			foreach ($instance as $key => $value) 
			{
				if ( in_array($key, array('num_items','imagesize','id_name','rel')) );
				$args[$key] = $value;
			}
			
			$out .= mv_get_scrnshots_feed($args);

			echo $out;
		}
	
	} // class

	// Register widget
	if ( !function_exists('mv_scrnshots_feed_init') )
	{
		function mv_scrnshots_feed_init()
		{
			register_widget('MV_Scrnshots_Feed_Widget');
		}
		
		add_action('init', 'mv_scrnshots_feed_init', 1);
	}
}