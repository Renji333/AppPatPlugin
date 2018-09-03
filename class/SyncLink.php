<?php

class SyncLink {

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'check'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    public function add_admin_menu(){
        add_menu_page('Synchronisation', 'Synchronisation', 'manage_options', 'sync', array($this, 'display_home'), 'dashicons-hammer');
    }

    public function display_home(){
        echo "<h1>Synchronisation des Liens : </h1>";
        include_once plugin_dir_path( __FILE__ ).'../views/SyncLink.php';
    }

    public function check(){

        if (isset($_GET['actionPLUGIN']) && $_GET['actionPLUGIN'] == 'SyncLink') {

            global $wpdb;
            $results = $wpdb->get_results("SELECT a.*, b.guid FROM {$wpdb->prefix}titles_and_links as a , {$wpdb->prefix}posts as b WHERE a.idPost = b.id");

            foreach ($results as $result) {

                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, 'http://www.patritheque.fr/../../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, 'http://www.patritheque.fr/../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, 'http://www.patritheque.fr/".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '../../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '/".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");

                $wpdb->delete( "{$wpdb->prefix}titles_and_links" , array( 'idPost' => $result->idPost ) );

            }

        }

    }

}

new SyncLink();