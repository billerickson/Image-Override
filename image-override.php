<?php
/*
Plugin Name: Image Override
Plugin URI: http://www.billerickson.net/image-override-plugin
Description: Allows you to override WordPress' auto generated thumbnails. 
Version: 1.2.1
Author: Bill Erickson
Author URI: http://www.billerickson.net
License: GPLv2
*/



class BE_Image_Override {
	var $instance;
	
	public function __construct() {
		$this->instance =& $this;
		add_action( 'init', array( $this, 'init' ) );	
	}
	
	public function init() {
		// Translations
		load_plugin_textdomain( 'image-override', false, basename( dirname( __FILE__ ) ) . '/languages' );
		
		// Setup Metabox
		add_filter( 'cmb_meta_boxes', array( $this, 'create_metaboxes' ) );
		add_action( 'init', array( $this, 'initialize_cmb_meta_boxes' ), 50 );
		
		// Image Override
		add_filter( 'image_downsize', array( $this, 'image_override' ), 10, 3 );
	}
	
	public function create_metaboxes( $meta_boxes ) {
		
		// Use 'image_override_post_types' filter to change what post types it applies to
		$post_types = apply_filters( 'image_override_post_types', array_keys( get_post_types( array('show_ui' => true ) ) ) );
		$all_image_sizes = $this->get_all_image_sizes();
		$sizes = array();
		foreach ($all_image_sizes as $size => $attr )
			$sizes[] = $size;
			
		// Use 'image_override_sizes' filter to change what sizes are used
		$sizes = apply_filters( 'image_override_sizes', $sizes );
		
		if ( !empty( $sizes ) ) {
			
			$prefix = 'image_override_';
			$fields = array();
			foreach( $sizes as $size ) {
			
				$fields[] = array(
					'name' => ucwords( $size ), 
					'desc' => __( 'This image size should be', 'image-override' ) . ' ' . $all_image_sizes[$size]['width'] . 'x' . $all_image_sizes[$size]['height'] . ( isset( $all_image_sizes[$size]['crop'] ) ? ' ' . __('exactly', 'image-override' ) : '' ) . '.',
		            'id' => $prefix.$size,
		            'type' => 'file'			
				);
	
			}
		
			$meta_boxes[] = array(
		    	'id' => 'image-override',
			    'title' => __( 'Image Override', 'image-override' ),
			    'pages' => $post_types,
				'context' => 'normal',
				'priority' => 'high',
				'show_names' => true, 
				'fields' => $fields
			);
		}
		
		return $meta_boxes;
	}

	public function initialize_cmb_meta_boxes() {
		$sizes = apply_filters( 'image-override-sizes', array( 'thumbnail', 'medium', 'large' ) );
	    if ( !class_exists('cmb_Meta_Box') && !empty( $sizes ) ) {
	        require_once( dirname( __FILE__) . '/lib/metabox/init.php' );
	    }
	}
	
	public function image_override( $output, $id, $size ) {
		$parent = get_post_ancestors( $id );
		if( empty( $parent ) ) return $output;
		
		// Only resize if size is a registered image size (string)
		if( is_array( $size ) )
			return $output;
		
		$override = esc_url( get_post_meta( $parent[0], 'image_override_' . $size, true ) );
		if (empty( $override) ) return $output;
		
		list($width, $height, $type) = @getimagesize($override);
		return array( $override, $width, $height );
	}
	
	public function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$builtin_sizes = array(
			'large'     => array(
	            'width' => get_option('large_size_w'),
	            'height' => get_option('large_size_h')
	        ),
	        'medium'    => array(
	            'width' => get_option('medium_size_w'),
	            'height' => get_option('medium_size_h')
	        ),
	        'thumbnail' => array(
	            'width' => get_option('thumbnail_size_w'),
	            'height' => get_option('thumbnail_size_h'),
	            'crop' => get_option('thumbnail_crop')
	        )
	    );
	    
	    $all_image_sizes = array();
	    if ( isset( $builtin_sizes ) && isset( $_wp_additional_image_sizes ) )
			$all_image_sizes = array_merge( $builtin_sizes, $_wp_additional_image_sizes );		
		else $all_image_sizes = $builtin_sizes;
		
		return $all_image_sizes;
	}
}

new BE_Image_Override;