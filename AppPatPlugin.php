<?php

/*
Plugin Name: Patrithèque Plugins
Plugin URI: http://patritheque.fr
Description: Un plugin...
Version: 0.3.1
Author: Francis CHRISTANVAL.
Author URI: https://github.com/Renji333
License: GPL2
*/

class AppPatPlugin {

    public function __construct(){

        add_action('admin_enqueue_scripts', 'pat_back_scripts', home_url());

        add_action( 'delete_post', array( 'AppPatPlugin', 'check_post_del'  ));

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
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}logs (id INT AUTO_INCREMENT PRIMARY KEY, file TEXT);");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}titles_and_links (id INT AUTO_INCREMENT PRIMARY KEY, idPost INT NOT NULL, file TEXT);");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tocs (id INT AUTO_INCREMENT PRIMARY KEY, aides VARCHAR(50), content LONGTEXT);");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}imported_files (id INT AUTO_INCREMENT PRIMARY KEY, aides VARCHAR(50), idPost INT NOT NULL);");

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}post_to_nlp_links (id INT AUTO_INCREMENT PRIMARY KEY, idPost INT NOT NULL,  idPostInLink INT, title TEXT, file VARCHAR(255));");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}post_to_pat_links (id INT AUTO_INCREMENT PRIMARY KEY, idPost INT NOT NULL,  idPostInLink INT, title TEXT, file VARCHAR(255));");

    }

    public static function uninstall()
    {

        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}logs;");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}titles_and_links;");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tocs;");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}imported_files;");

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}post_to_nlp_links;");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}post_to_pat_links;");

    }

    public static function check_post_del( $post_id ){
        global $wpdb;

        if(get_post_type($post_id) == "post"){
            $wpdb->delete( "{$wpdb->prefix}imported_files" , array( 'idPost' => $post_id ) );
            $wpdb->delete( "{$wpdb->prefix}post_to_nlp_links" , array( 'idPost' => $post_id ) );
            $wpdb->delete( "{$wpdb->prefix}post_to_nlp_links" , array( 'idPostInLink' => $post_id ) );
            $wpdb->delete( "{$wpdb->prefix}post_to_pat_links" , array( 'idPost' => $post_id ) );
            $wpdb->delete( "{$wpdb->prefix}post_to_pat_links" , array( 'idPostInLink' => $post_id ) );
            $wpdb->delete( "{$wpdb->prefix}titles_and_links" , array( 'idPost' => $post_id ) );
        }

    }

}

function pat_back_scripts($url){

    wp_enqueue_style( 'bootstrap-pat',plugins_url().'/AppPatPlugin/css/bootstrap.css' );
    wp_enqueue_style( 'bootstrap-pat-select',plugins_url().'/AppPatPlugin/css/bootstrap-select.min.css' );

    wp_enqueue_style( 'bootstrap-pat-link',plugins_url().'/AppPatPlugin/css/linkCss.css' );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-pat',plugins_url() . '/AppPatPlugin/js/bootstrap.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'bootstrap-pat-select',plugins_url() . '/AppPatPlugin/js/bootstrap-select.min.js', array( 'jquery' ), '', true );

    wp_enqueue_script( 'bootstrap-pat-select-link',plugins_url() . '/AppPatPlugin/js/linkSelect.js', array( 'jquery' ), '', true );
    wp_localize_script( 'bootstrap-pat-select-link', 'urlSite', array( 'siteurl' => get_option('siteurl') ) );

}

foreach (glob(plugin_dir_path( __FILE__ )."class/*.php") as $filename) {
    include($filename);
}

require_once('ajax/getLinks.php');

new AppPatPlugin();