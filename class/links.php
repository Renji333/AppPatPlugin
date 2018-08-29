<?php

class links
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    public function add_admin_menu()
    {
        add_menu_page('Gestion des liens', 'Gestion des liens', 'edit_posts', 'links', array($this, 'display_home_link'), 'dashicons-admin-links');
    }

    public function display_home_link()
    {
        $results = $this->getArticles();
        $resultsToAdd = $this->getArticles();
        include_once plugin_dir_path( __FILE__ ).'../views/links.php';
    }

    public function getArticles(){
        global $wpdb;
        $results = $wpdb->get_results("SELECT `ID`,`post_title`,`guid`,`post_date` FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'post'  ORDER BY `post_date` DESC");
        return $results;
    }

}