<?php

/*
Plugin Name: Patrithèque Plugins
Plugin URI: http://patritheque.fr
Description: Un plugin...
Version: 0.1
Author: FCH
License: GPL2
*/

class AppPatPlugin {

	public function __construct() {

		add_action('admin_enqueue_scripts', 'pat_back_scripts', home_url());

		// IMPORT DES FICHIERS POUR LA RECUPERATION
        include_once plugin_dir_path( __FILE__ ).'/class/pat.php';
        include_once plugin_dir_path( __FILE__ ).'/class/SyncLink.php';
        include_once plugin_dir_path( __FILE__ ).'/class/SyncPatLink.php';

		// IMPORT DE LA GESTION DES LIENS
		include_once plugin_dir_path( __FILE__ ).'/class/links.php';

		// INITIALISATION DES CLASS
        new pat();
        new SyncLink();
        new SyncPatLink();

		// INITIALISATION DE LA GESTION DES LIENS
		new links();


		// GESTION DE L'ACTIVATION DU Plugin
		register_activation_hook(__FILE__, array( 'AppPatPlugin', 'install' ) );
		register_uninstall_hook(__FILE__, array( 'AppPatPlugin', 'uninstall' ) );

		// GESTION DES EVENEMENTS DU Plugin

		add_action( 'wp_default_scripts', function( $scripts ) {
			if ( ! empty( $scripts->registered['jquery'] ) ) {
				$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
			}
		});

	}

	public static function install()
	{
	    global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}titles_and_links (id INT AUTO_INCREMENT PRIMARY KEY, idPost INT NOT NULL, file TEXT);");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tocs (id INT AUTO_INCREMENT PRIMARY KEY, aides VARCHAR(50), content LONGTEXT);");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}imported_files (id INT AUTO_INCREMENT PRIMARY KEY, aides VARCHAR(50), idPost INT NOT NULL);");
	}

	public static function uninstall()
	{
	    global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}titles_and_links;");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tocs;");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}imported_files;");
	}

}

function pat_back_scripts($url){

    wp_enqueue_style( 'bootstrap-pat',plugins_url().'/AppPatPlugin/css/bootstrap.css' );
    wp_enqueue_style( 'bootstrap-pat-select',plugins_url().'/AppPatPlugin/css/bootstrap-select.min.css' );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-pat',plugins_url() . '/AppPatPlugin/js/bootstrap.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'bootstrap-pat-select',plugins_url() . '/AppPatPlugin/js/bootstrap-select.min.js', array( 'jquery' ), '', true );
    
}

new AppPatPlugin();