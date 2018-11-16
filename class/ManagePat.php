<?php

class ManagePat
{

    public $bool = false;

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'checkUpdatePat'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    // Création d'un sous-menu dans l'interface admin.
    public function add_admin_menu()
    {
        add_menu_page(  'Gestion des aides patrithèque', 'Gestion des aides patrithèque', 'manage_options', 'aides',  array($this, 'display_home_link'), 'dashicons-category');
    }

    // Affichage de la vue pour cette interface du plugin.
    public function display_home_link()
    {
        $v['integrale'] = get_option( 'integrale' );
        $bool = $this->bool;
        include_once plugin_dir_path( __FILE__ ).'../views/ManagePat.php';
    }

    // Insertion du nom du fichier d'origine et de son ID pour l'article créé lors de l'import.
    // Ex : ban_acp.htm => id : 50. Et cela parce que lors de l'import de cette page.
    // Un article a été créé avec pour id 50.
    public function insertTitleAndLinks($link,$id){
        global $wpdb;
        $id = htmlspecialchars($id);
        $wpdb->insert("{$wpdb->prefix}titles_and_links", array('file' => $link,'idPost' => $id));
    }

    // Insertion d'un sommaire pour une aide donnée.
    public function insertToc($key,$toc){
        global $wpdb;
        $key = htmlspecialchars($key);
        $wpdb->delete("{$wpdb->prefix}tocs", array('aides' => $key ));
        $wpdb->insert("{$wpdb->prefix}tocs", array('aides' => $key, 'content' => $toc));
    }

    // Import des images.
    public function importImgs($dir,$e,$key){

        global $wpdb;

        $pathImg = "$dir/$e/";
        // Listing des images dans le fichier $pathImg.
        $results = scandir($pathImg);

        foreach ($results as $result) {

            // Ici, pour chaque fichier trouver s'il s'agit bien d'une image, on fait le traitement.
            if($result === '..' || $result === '.' || pathinfo($result, PATHINFO_EXTENSION) == 'js' || pathinfo($result, PATHINFO_EXTENSION) == NULL || pathinfo($result, PATHINFO_EXTENSION) == '' ) {
                continue;
            } else {

                $img = $pathImg. '' .$result;
                $image_data = file_get_contents($img);
                $filename = basename($img);

                // correction du nom, titre de l'image.
                $content = str_replace('_', ' ',$filename);
                $content = str_replace('-', ' ',$content);
                $content = str_replace('.', ' ',$content);
                $content = str_replace('gif', ' ',$content);
                $content = str_replace('png', ' ',$content);
                $content = str_replace('jpg', ' ',$content);
                $content = str_replace('jpeg', ' ',$content);

                $upload_file = wp_upload_bits($filename, null, $image_data);

                if (!$upload_file['error']) {

                    $wp_filetype = wp_check_filetype($filename, null );

                    // Insertion des infos pour l'image.
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', ' ', $filename),
                        'post_content' => $content,
                        'post_status' => 'inherit',
                        'post_date' => current_time('mysql'),
                    );

                    // Insertion de l'image dans WordPress.
                    $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );

                    if (!is_wp_error($attachment_id)) {

                        // Si tout est bon, on peut conserver cette image dans l'historique des fichiers importés.

                        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                        wp_update_attachment_metadata( $attachment_id,  $attachment_data );

                        // Insertion de l'image dans la table, d'historique des fichiers importés.

                        $wpdb->insert("{$wpdb->prefix}imported_files", array('aides' => $key,'idPost' => $attachment_id));

                    }else{
                        // Ici, il y a eu une erreure. De ce fait, on affiche une alerte.
                        echo '<div class="alert alert-warning" role="alert">'.$result.'</div>';
                        $wpdb->insert("{$wpdb->prefix}logs", array('file' => $result));
                    }

                }else{
                    // Ici, il y a eu une erreure. De ce fait, on affiche une alerte.
                    echo '<div class="alert alert-danger" role="alert">'.$result.'</div>';
                    $wpdb->insert("{$wpdb->prefix}logs", array('file' => $result));
                }

            }

        }

    }

    // checkUpdatePat() s'éxécute lors de la mise à jour ou suppression d'une aide.
    public function checkUpdatePat(){

        if (isset($_GET['page']) && $_GET['page'] == "aides") {

            global $wpdb;

            // Si l'action est la suppression de l'aide, cette condition sera correcte.
            if (isset($_REQUEST["action"]) == 'delete' && isset($_REQUEST["action"]) && isset($_REQUEST["key"])){

                // Ici, on recherche les fichiers importés pour l'aide à supprimer
                $results = $wpdb->get_results("SELECT idPost FROM {$wpdb->prefix}imported_files as a, {$wpdb->prefix}posts as b WHERE a.idPost = b.id AND aides = '".$_REQUEST['key']."'");

                foreach ($results as $result) {

                    // Ici, pour chaque fichier trouvé, on le supprime et aussi on supprime les résidues dans les tables que l'on a créé pour ce plugin.
                    wp_delete_post( $result->idPost, true );
                    $wpdb->delete("{$wpdb->prefix}imported_files", array('idPost' => $result->idPost ));
                    $wpdb->delete("{$wpdb->prefix}titles_and_links", array('idPost' => $result->idPost ));
                    $wpdb->delete("{$wpdb->prefix}post_to_pat_links", array('idPost' => $result->idPost ));
                    $wpdb->delete("{$wpdb->prefix}post_to_pat_links", array('idPostInLink' => $result->idPost ));

                }

            }

            // Si l'action est la mise à jour de l'aide, cette condition sera correcte.
            if (isset($_REQUEST["action"]) && isset($_REQUEST["action"]) == 'update' && isset($_REQUEST["key"]) && isset($_REQUEST["v"]))  {

                $dir =  plugin_dir_path( __FILE__ ).'../aides/'.$_REQUEST["key"];

                // Si le dossier aides/nom_de_l'aide existe, on le supprime.
                if(is_dir($dir)){
                    chmod($dir, 0777) ;
                    rrmdir($dir);
                }

                //Si le dossier aides/nom_de_l'aide existe pas, on le crée et on rentre dans la condition.
                if(mkdir($dir, 0777, true)){

                    // Déplacement du .zip envoyé dans le dossier aide/nom_de_laide
                    $tmp_name = $_FILES["zip"]["tmp_name"];
                    $name = $_FILES["zip"]["name"];
                    move_uploaded_file($tmp_name, $dir."".$name);

                    // Déplacement et suppréssion du .zip envoyé
                    $zip = new ZipArchive;
                    if ($zip->open($dir."".$name) === TRUE) {
                        $zip->extractTo($dir);
                        $zip->close();
                        unlink($dir."".$name);
                    }

                    // Import des images contenu dans les dossiers "images" et "schémas" du .zip envoyé.
                    $this->importImgs($dir,'images',$_REQUEST["key"]);
                    $this->importImgs($dir,'schemas',$_REQUEST["key"]);

                    // Analyse des fichiers contenus dans le dossier html de l'aide envoyé.
                    $pathHtm = $dir.'/html/';
                    $results = scandir($pathHtm);

                    foreach ($results as $result) {

                        if($result === '..' || $result === '.' || pathinfo($result, PATHINFO_EXTENSION) == NULL || pathinfo($result, PATHINFO_EXTENSION) == '' ) {
                            continue;
                        } else {

                            // Ouvertude du fichier trouvé et récupération du contenu.
                            $myfile = fopen($pathHtm."".$result, "r") or die("Unable to open file!");
                            $content = fread($myfile,filesize($pathHtm."".$result));
                            fclose($myfile);

                            $CatId = [];
                            $UploadPath = wp_upload_dir();

                            $corps = str_replace(array("\n", "\r","&nbsp;"), '', $content);

                            preg_match('/<div(.*?)id="content"(.*?)>(.*?)<\/div><\/body>/m', $corps, $matches );

                            $corps = $matches[3];

                            // Correction des url pour les balises <img>.
                            $corps = preg_replace('/src="..\/images\//','src="'.$UploadPath['url'].'/',$corps);
                            $corps = preg_replace('/src="..\/schemas\//','src="'.$UploadPath['url'].'/',$corps);

                            // Récupération du titre de la page.
                            preg_match('/<p class="titre-rubrique">(.*?)<a class="sous-titre-rubrique">(.*?)<\/a><\/p>/m', $corps, $matches);
                            $title = $matches[2];

                            //$exercpt = preg_replace("/\<fieldset id=\"cadre-resume\"\>(.*?)<\/fieldset\>/m","",$corps);
                            //$exercpt = preg_replace("/<p class=\"encart-nouv\">(.*?)<\/p>/m","",$exercpt);

                            //Ajout de la catégorie PAT
                            $pat = term_exists( 'PAT', 'category' );
                            if (is_wp_error($pat) || $pat == NULL ) {
                                $pat = wp_insert_term('PAT', 'category', [ 'slug' => 'PAT']);
                            }
                            array_push($CatId, $pat['term_id']);

                            //Ajout de la catégorie de l'aide comme Integrale, Essentiels
                            $aideCat = term_exists($_REQUEST["key"], 'category');
                            if (is_wp_error($aideCat) || $aideCat == NULL ) {
                                $aideCat = wp_insert_term($_REQUEST["key"], 'category', ['parent' => $pat['term_id']]);
                            }
                            array_push($CatId, $aideCat['term_id']);

                            //Ajout des catégories basées sur le préfixe de la page .htm comme fiscalité.
                            include_once plugin_dir_path( __FILE__ ).'../tools/PatArrayCat.php';
                            $prefixe = explode("_", $result)[0].'_';

                            $i = null;
                            $themes = array('Fiscalité', 'Placements', 'Immobilier', 'Retraite et prévoyance', 'Famille et transmission', 'Dirigeant');
                            $themeSlug = array('fiscalite', 'placements', 'immobilier', 'retraite', 'famille', 'dirigeants');

                            //variables $epargne_placements etc... issues du fichier tools/PatArrayCat.php
                            if (in_array($prefixe, $fiscalite)) {
                                $i = 0;
                            }   else if (in_array($prefixe, $epargne_placements)) {
                                $i = 1;
                            }   else if (in_array($prefixe, $immobilier)) {
                                $i = 2;
                            }   else if (in_array($prefixe, $retraite_prevoyance)) {
                                $i = 3;
                            }   else if (in_array($prefixe, $famille_transmission)) {
                                $i = 4;
                            }   else if (in_array($prefixe, $dirigeant)) {
                                $i = 5;
                            }

                            if(is_int($i)){

                                $patCat = term_exists($themes[$i], 'category', $aideCat['term_id'] );

                                if (is_wp_error($patCat) || $patCat == NULL ) {
                                    $patCat = wp_insert_term($themes[$i], 'category', [ 'slug' => $themeSlug[$i], 'parent' => $aideCat['term_id'] ]);
                                }

                                array_push($CatId, $patCat['term_id']);

                            }

                            // Insertion des informations de la page actuelle.
                            $new_post = array(
                                'post_title' => trim($title),
                                'post_content' => $corps,
                                'post_status' => 'publish',
                                'post_author' => 1,
                                'post_category' => $CatId
                            );

                            // Insertion de l'article (page actuelle) en base de données.
                            $postsId = wp_insert_post( $new_post );

                            // Insertion de l'id et du titre de l'article (page actuelle) dans la table "titles_and_links".
                            $this->insertTitleAndLinks($result,$postsId);

                            // Insertion de l'id de l'article (page actuelle) dans la table "imported_files".
                            $wpdb->insert("{$wpdb->prefix}imported_files", array('aides' => $_REQUEST['key'],'idPost' => $postsId));

                        }

                    }

                    // Récupération du hhc pour le sommaire.
                    $hhc = new SimpleXMLElement(file_get_contents($dir.'/master/patritec.hhc', FILE_USE_INCLUDE_PATH));
                    // Création du sommaire.
                    createToc($hhc,$dir.'/toc.txt');
                    // Insertion du sommaire en base.
                    $this->insertToc($_REQUEST["key"],file_get_contents($dir.'/toc.txt', FILE_USE_INCLUDE_PATH));

                }

                update_option( $_REQUEST["key"], htmlspecialchars($_REQUEST["v"]));
                $this->bool = true;

                chmod($dir, 0777) ;
                rrmdir($dir);

            }

        }

    }

}

// Function pour supprimer un dossier
function rrmdir($dir) {
    foreach(glob($dir . '/' . '*') as $file) {
        if(is_dir($file)){
            rrmdir($file);
        }else{
            unlink($file);
        }
    }
    rmdir($dir);
}

// Function pour ouvrir un fichier
function utf8_fopen_read($fileName) {
    $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
    $handle=fopen("php://memory", "rw");
    fwrite($handle, $fc);
    fseek($handle, 0);
    return $handle;
}

// Function pour écrire le sommaire
function createToc($xml,$file){

    $panel = array("fiscalite","epargne","immobilier","famille","retraite","dirigeants","outils");
    $i = 0;
    $_SESSION['pageHtmId'] = 0;

    foreach ($xml as $key) {

        if($key["name"] != ''){

            writeInTocFile($file, "<div id=\"panel-$panel[$i]\"><ul>");

            foreach ($key->item as $item) {
                writeFolder($item,$file);
            }

            writeInTocFile($file, "</ul></div>");

            $i = $i + 1;

        }

    }

}

// Function pour écrire des dossiers ou lien dans le sommaire
function writeFolder($e,$file){

    $name = $e["name"];

    // Ici, si il s'agit d'un dossier
    if(!$e["link"]){

        writeInTocFile($file, "<li><span>$name</span><ul>");

        foreach ($e->item as $item) {

            writeFolder($item,$file);

        }

        writeInTocFile($file, "</ul></li>");

    }else{
        // Ici, si il s'agit d'une fiche
        $link = $e["link"];
        writeInTocFile($file, '<li><a href="'.$link.'" page-htm-id="'.$_SESSION['pageHtmId'].'">'.$name.'</a></li>');
        $_SESSION['pageHtmId'] = $_SESSION['pageHtmId'] + 1 ;
    }

}

// Function pour écrire dans un fichier
function writeInTocFile($path,$msg){
    $f = fopen($path, "a+");
    fwrite($f, $msg);
    fclose($f);
    chmod($path, 0777);
}

new ManagePat();