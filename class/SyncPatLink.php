<?php

class SyncPatLink
{

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'check'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    // Création d'un sous-menu dans l'interface admin.
    public function add_admin_menu()
    {
        add_menu_page('Synchronisation du sommaire de la Patrithèque', 'Synchronisation du sommaire de la Patrithèque', 'manage_options', 'syncPat', array($this, 'display_home'), 'dashicons-hammer');
    }

    // Affichage de la vue pour cette interface du plugin.
    public function display_home()
    {
        echo "<h1>Synchronisation des Liens du sommaire de la Patrithèque : </h1>";
        include_once plugin_dir_path(__FILE__) . '../views/SyncPatLink.php';
    }

    // Function qui s'exécute après clique sur le bouton "Synchroniser".
    public function check()
    {

        if (isset($_GET['actionPLUGIN']) && $_GET['actionPLUGIN'] == 'SyncPatLink') {

            global $wpdb;

            // Ici ont récupère les liens de tous les articles du site.

            $results = $wpdb->get_results("SELECT a.*, b.guid FROM {$wpdb->prefix}titles_and_links as a , {$wpdb->prefix}posts as b WHERE a.idPost = b.id");

            foreach ($results as $result) {

                // Ici, on corrige les liens du sommaire avec les urls récupérées des articles du site.
                $wpdb->query("UPDATE {$wpdb->prefix}tocs SET content = REPLACE(content, 'html\\\\" . $result->file . "', '" . $result->guid . "')");

            }

            // Pour les deux lignes qui suivent, on corrige les liens annexes (post_to_nlp_links, post_to_pat_links) avec les liens des articles du site.
            $wpdb->query("UPDATE `{$wpdb->prefix}post_to_nlp_links` as a SET a.`idPostInLink` = (SELECT b.idPost FROM `{$wpdb->prefix}titles_and_links` as b WHERE a.`file`= b.`file`) WHERE 1");
            $wpdb->query("UPDATE `{$wpdb->prefix}post_to_pat_links` as a SET a.`idPostInLink` = (SELECT b.idPost FROM `{$wpdb->prefix}titles_and_links` as b WHERE a.`file`= b.`file`) WHERE 1");

        }

    }

}

new SyncPatLink();