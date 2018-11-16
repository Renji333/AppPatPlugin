<?php

class SyncLink {

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'check'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    // Création d'un sous-menu dans l'interface admin.
    public function add_admin_menu(){
        add_menu_page('Synchronisation', 'Synchronisation', 'manage_options', 'sync', array($this, 'display_home'), 'dashicons-hammer');
    }

    // Affichage de la vue pour cette interface du plugin.
    public function display_home(){
        echo "<h1>Synchronisation des Liens : </h1>";
        include_once plugin_dir_path( __FILE__ ).'../views/SyncLink.php';
    }

    // Function qui s'exécute après clique sur le bouton "Synchroniser".
    public function check(){

        if (isset($_GET['actionPLUGIN']) && $_GET['actionPLUGIN'] == 'SyncLink') {

            global $wpdb;

            // Ici ont récupère les liens de tous les articles du site.
            $results = $wpdb->get_results("SELECT a.*, b.guid FROM {$wpdb->prefix}titles_and_links as a , {$wpdb->prefix}posts as b WHERE a.idPost = b.id");

            foreach ($results as $result) {

                // Ici, pour chaque lien récupéré avec la requete précédente. On corrige chaque lien contenue dans chaque article.
                // Et cela, avec plusieurs format : http://www.patritheque.fr/../../lien, http://www.patritheque.fr/../lien etc...

                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, 'http://www.patritheque.fr/../../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, 'http://www.patritheque.fr/../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, 'http://www.patritheque.fr/".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '../../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '../".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '/".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET  post_content = REPLACE(post_content, '".$result->file."', '".$result->guid."') WHERE post_type = 'post' ");

                // Ici, on supprime les liens qui ont été corrigé dans la table "titles_and_links". Et cela, pour éviter que le script cherche encore à les corriger.
                $wpdb->delete("{$wpdb->prefix}titles_and_links" , array( 'idPost' => $result->idPost ));

            }

        }

    }

}

new SyncLink();