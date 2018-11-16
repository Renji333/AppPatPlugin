<?php

class links
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    // Création d'un sous-menu dans l'interface admin.
    public function add_admin_menu()
    {
        add_menu_page('Gestion des liens', 'Gestion des liens', 'edit_posts', 'links', array($this, 'display_home_link'), 'dashicons-admin-links');
        add_submenu_page( 'links', 'vers la Patrithèque', 'vers la Patrithèque', 'edit_posts', 'links', array($this, 'display_home_link'), 'dashicons-portfolio');
    }

    // Affichage de la vue pour cette interface du plugin.
    public function display_home_link()
    {
        $results = $this->getArticles(null);
        $resultsNlp = $this->getArticles("NLP");
        $resultsPat = $this->getArticles("PAT");
        include_once plugin_dir_path( __FILE__ ).'../views/links.php';
    }

    public function getArticles($e){

        global $wpdb;

        if($e == null){

            // Récupération de tous les articles pour la première liste déroulante de l'interface "Gestions des liens".
            $results = $wpdb->get_results("SELECT `ID`,`post_title`,`guid`,`post_date` FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'post'  ORDER BY `post_date` DESC");

        }else{

            if($e == "NLP"){
                $catSearched = "NLP";
            }else{
                $catSearched = "PAT";
            }

            // Récupération des articles (Patrithèque ou Newsletter) pour les listes déroulante (Patrithèque, Newsletter) de l'interface "Gestion des liens".
            $results = $wpdb->get_results("SELECT DISTINCT p.`ID`,p.`post_title`,p.`guid`,p.`post_date` FROM `{$wpdb->prefix}posts` as p, `{$wpdb->prefix}terms` as t, `{$wpdb->prefix}term_relationships` as r WHERE p.`post_type` = 'post' AND p.`ID` = r.`object_id` AND r.`term_taxonomy_id` IN (SELECT `term_id` FROM `{$wpdb->prefix}terms` WHERE `name` LIKE '%$catSearched%')  ORDER BY `post_date` DESC");

        }

        return $results;

    }

}

new links();