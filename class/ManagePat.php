<?php

class ManagePat
{

    public $bool = false;

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'checkUpdatePat'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    public function add_admin_menu()
    {
        add_menu_page(  'Gestion des aides patrithèque', 'Gestion des aides patrithèque', 'manage_options', 'aides',  array($this, 'display_home_link'), 'dashicons-category');
    }

    public function display_home_link()
    {
        $v['integrale'] = get_option( 'integrale' );
        $bool = $this->bool;
        include_once plugin_dir_path( __FILE__ ).'../views/ManagePat.php';
    }

    public function insertTitleAndLinks($link,$id){
        global $wpdb;
        $id = htmlspecialchars($id);
        $wpdb->insert("{$wpdb->prefix}titles_and_links", array('file' => $link,'idPost' => $id));
    }

    public function insertToc($key,$toc){
        global $wpdb;
        $key = htmlspecialchars($key);
        $wpdb->delete("{$wpdb->prefix}tocs", array('aides' => $key ));
        $wpdb->insert("{$wpdb->prefix}tocs", array('aides' => $key, 'content' => $toc));
    }

    public function importImgs($dir,$e,$key){

        global $wpdb;

        $pathImg = "$dir/$e/";
        $results = scandir($pathImg);

        foreach ($results as $result) {

            if($result === '..' || $result === '.' || pathinfo($result, PATHINFO_EXTENSION) == 'js' || pathinfo($result, PATHINFO_EXTENSION) == NULL || pathinfo($result, PATHINFO_EXTENSION) == '' ) {
                continue;
            } else {

                $img = $pathImg. '' .$result;
                $image_data = file_get_contents($img);
                $filename = basename($img);

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

                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', ' ', $filename),
                        'post_content' => $content,
                        'post_status' => 'inherit',
                        'post_date' => current_time('mysql'),
                    );

                    $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );

                    if (!is_wp_error($attachment_id)) {

                        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                        wp_update_attachment_metadata( $attachment_id,  $attachment_data );

                        $wpdb->insert("{$wpdb->prefix}imported_files", array('aides' => $key,'idPost' => $attachment_id));

                    }else{
                        echo '<div class="alert alert-warning" role="alert">'.$result.'</div>';
                        $wpdb->insert("{$wpdb->prefix}logs", array('file' => $result));
                    }

                }else{
                    echo '<div class="alert alert-danger" role="alert">'.$result.'</div>';
                    $wpdb->insert("{$wpdb->prefix}logs", array('file' => $result));
                }

            }

        }

    }

    public function checkUpdatePat(){

        if (isset($_GET['page']) && $_GET['page'] == "aides") {

            global $wpdb;

            if (isset($_REQUEST["action"]) == 'delete' && isset($_REQUEST["action"]) && isset($_REQUEST["key"])){

                $results = $wpdb->get_results("SELECT idPost FROM {$wpdb->prefix}imported_files as a, {$wpdb->prefix}posts as b WHERE a.idPost = b.id AND aides = '".$_REQUEST['key']."'");

                foreach ($results as $result) {

                    wp_delete_post( $result->idPost, true );
                    $wpdb->delete("{$wpdb->prefix}imported_files", array('idPost' => $result->idPost ));
                    $wpdb->delete("{$wpdb->prefix}titles_and_links", array('idPost' => $result->idPost ));
                    $wpdb->delete("{$wpdb->prefix}post_to_pat_links", array('idPost' => $result->idPost ));
                    $wpdb->delete("{$wpdb->prefix}post_to_pat_links", array('idPostInLink' => $result->idPost ));

                }

            }

            if (isset($_REQUEST["action"]) && isset($_REQUEST["action"]) == 'update' && isset($_REQUEST["key"]) && isset($_REQUEST["v"]))  {

                $dir =  plugin_dir_path( __FILE__ ).'../aides/'.$_REQUEST["key"];

                if(is_dir($dir)){
                    chmod($dir, 0777) ;
                    rrmdir($dir);
                }

                if(mkdir($dir, 0777, true)){

                    $tmp_name = $_FILES["zip"]["tmp_name"];
                    $name = $_FILES["zip"]["name"];
                    move_uploaded_file($tmp_name, $dir."".$name);

                    $zip = new ZipArchive;
                    if ($zip->open($dir."".$name) === TRUE) {
                        $zip->extractTo($dir);
                        $zip->close();
                        unlink($dir."".$name);
                    }

                    $this->importImgs($dir,'images',$_REQUEST["key"]);
                    $this->importImgs($dir,'schemas',$_REQUEST["key"]);

                    $pathHtm = $dir.'/html/';
                    $results = scandir($pathHtm);

                    foreach ($results as $result) {

                        if($result === '..' || $result === '.' || pathinfo($result, PATHINFO_EXTENSION) == NULL || pathinfo($result, PATHINFO_EXTENSION) == '' ) {
                            continue;
                        } else {

                            $myfile = fopen($pathHtm."".$result, "r") or die("Unable to open file!");
                            $content = fread($myfile,filesize($pathHtm."".$result));
                            fclose($myfile);

                            $CatId = [];
                            $UploadPath = wp_upload_dir();

                            preg_match('/<title>(.*?)<\/title>/m', $content, $matches);
                            $title = $matches[1];

                            $corps = str_replace(array("\n", "\r"), '', $content);

                            preg_match('/<div(.*?)id="content"(.*?)>(.*?)<\/div><\/body>/m', $corps, $matches );

                            $corps = $matches[3];

                            $corps = preg_replace('/src="..\/images\//','src="'.$UploadPath['url'].'/',$corps);
                            $corps = preg_replace('/src="..\/schemas\//','src="'.$UploadPath['url'].'/',$corps);

                            //Add NLP catégorie
                            $pat = term_exists( 'PAT', 'category' );
                            if (is_wp_error($pat) || $pat == NULL ) {
                                $pat = wp_insert_term('PAT', 'category', [ 'slug' => 'PAT']);
                            }
                            array_push($CatId, $pat['term_id']);

                            //Add Aides catégorie Like Integrale, Essentiels
                            $aideCat = term_exists( $_REQUEST["key"], 'category' );
                            if (is_wp_error($aideCat) || $aideCat == NULL ) {
                                $aideCat = wp_insert_term($_REQUEST["key"], 'category', ['parent' => $pat['term_id']]);
                            }
                            array_push($CatId, $aideCat['term_id']);



                            // Add Catégorie based on .htm préfixe
                            include_once plugin_dir_path( __FILE__ ).'../tools/PatArrayCat.php';
                            $prefixe = explode("_", $result)[0].'_';

                            $i = null;
                            $themes = array('Fiscalité', 'Placements', 'Immobilier', 'Retraite et prévoyance', 'Famille et transmission', 'Dirigeant');
                            $themeSlug = array('fiscalite', 'placements', 'immobilier', 'retraite', 'famille', 'dirigeants');

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

                            $new_post = array(
                                'post_title' => html_entity_decode($title),
                                'post_content' => $corps,
                                'post_status' => 'publish',
                                'post_author' => 1,
                                'post_category' => $CatId
                            );

                            $postsId = wp_insert_post( $new_post );

                            $this->insertTitleAndLinks($result,$postsId);

                            $wpdb->insert("{$wpdb->prefix}imported_files", array('aides' => $_REQUEST['key'],'idPost' => $postsId));

                        }

                    }

                    $hhc = new SimpleXMLElement(file_get_contents($dir.'/master/patritec.hhc', FILE_USE_INCLUDE_PATH));

                    createToc($hhc,$dir.'/toc.txt');

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

function utf8_fopen_read($fileName) {
    $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
    $handle=fopen("php://memory", "rw");
    fwrite($handle, $fc);
    fseek($handle, 0);
    return $handle;
}

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

function writeFolder($e,$file){

    $name = $e["name"];

    if(!$e["link"]){

        writeInTocFile($file, "<li><span>$name</span><ul>");

        foreach ($e->item as $item) {

            writeFolder($item,$file);

        }

        writeInTocFile($file, "</ul></li>");

    }else{
        $link = $e["link"];
        writeInTocFile($file, '<li><a href="'.$link.'" page-htm-id="'.$_SESSION['pageHtmId'].'">'.$name.'</a></li>');
        $_SESSION['pageHtmId'] = $_SESSION['pageHtmId'] + 1 ;
    }

}

function writeInTocFile($path,$msg){
    $f = fopen($path, "a+");
    fwrite($f, $msg);
    fclose($f);
    chmod($path, 0777);
}

new ManagePat();