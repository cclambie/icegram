<?php
if ( !defined( 'ABSPATH' ) ) exit;
if ( class_exists( 'Icegram_Message_Type' ) ) return;

/**
* Class Icegram_Message_Type
*/
abstract class Icegram_Message_Type {

	var $type;
	var $name;
	var $basedir;
	var $baseurl;
	var $settings;

	function __construct( $basedir = '', $baseurl = '' ) {
		$class_name = get_class($this);
		$base = 'Icegram_Message_Type_';
		if ( strpos($class_name, $base) === 0 ) {
			$class_name = str_replace($base, '', $class_name);
			$this->type = str_replace( '_', '-', strtolower($class_name) );
			$this->name = ucwords( str_replace( "-", ' ', $this->type ) );
			$this->basedir = $basedir;
			$this->baseurl = $baseurl;

			add_filter('icegram_message_types', array( $this, 'init') );
		}
	}

	function meets_guidelines() {
		if (empty($this->type) || empty($this->name) || !is_dir($this->basedir) ) {
			return false;
		}
		if (!is_file( $this->basedir . '/default.css') || !is_file( $this->basedir . '/main.js') ) {
			return false;
		}
		return true;
	}

	function init( $message_types = array() ) {

		if (! $this->meets_guidelines() ) {
			return $message_types;
		}

		// Load themes
		$theme_files = (array) glob( $this->basedir . '/themes/*.css' );
		// Allow other plugins to add themes
		$theme_files = apply_filters( 'icegram_message_type_themes_' + $this->type,  $theme_files );

		if( empty( $theme_files ) ) {
			$theme_files[] = $this->basedir . '/default.css';
		}

		$themes = array();
		if( !empty( $theme_files ) ) {
			foreach ( $theme_files as $file ) {
				if (is_file ( $file )) {
					$theme = str_replace( ".css", "", basename( $file ) );
					$themes[ $theme ] = array( 
												'name' 		=> ucwords( str_replace( "-", ' ', $theme ) ),
												'type' 		=> $theme,
												'basedir' 	=> $this->basedir . '/themes/',
												'baseurl'	=> $this->baseurl . '/themes/'							
												);
				}
			}
		}

		$this->define_settings();

		$params = array( 
				'name' 	  	=> $this->name,
				'type' 	  	=> $this->type,
				'basedir' 	=> $this->basedir,
				'baseurl' 	=> $this->baseurl,
				'themes'  	=> $themes,
				'settings' 	=> $this->settings
				);

		$params = apply_filters( 'icegram_message_type_params_' . $this->type,  $params );

		$message_types[ $this->type ] = $params;
		return $message_types;
	}

	function define_settings() {

		$this->settings = array(
						    'animation' 	=> array( 'type' => 'select' ),
						    'theme' 		=> array( 'type' => 'select' ),
						    'headline' 		=> array( 'type' => 'text' ),
						    'message' 		=> array( 'type' => 'editor' ),
						    'label' 		=> array( 'type' => 'text' ),
						    'link' 			=> array( 'type' => 'text' ),
						    'icon' 			=> array( 'type' => 'text' ),
						    'bg_color' 		=> array( 'type' => 'color' ),
						    'text_color' 	=> array( 'type' => 'color' ),
						    'position' 		=> array( 'type' => 'position' )
						    );

	}
}