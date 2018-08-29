<?php

class pat
{

    public $bool = false;

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'checkUpdatePat'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    public function add_admin_menu()
    {
        add_menu_page(  'Gestion des aides patrithèque', 'Gestion des aides patrithèque', 'manage_options','aides',  array($this, 'display_home_link'), 'dashicons-book-alt');
    }

    public function display_home_link()
    {
        $v['integrale'] = get_option( 'integrale' );
        $bool = $this->bool;
        include_once plugin_dir_path( __FILE__ ).'../views/pat.php';
    }

    public function importImgs($dir,$e){

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

                    }else{
                        echo '<div class="alert alert-warning" role="alert">'.$result.'</div>';
                    }

                }else{
                    echo '<div class="alert alert-danger" role="alert">'.$result.'</div>';
                }

            }

        }

    }

    public function checkUpdatePat(){

        if (isset($_GET['page']) && $_GET['page'] == "aides") {

            if (isset($_REQUEST["key"]) && isset($_REQUEST["v"]))  {

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

                    $this->importImgs($dir,'images');
                    $this->importImgs($dir,'schemas');

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

                            $pat = term_exists( 'PAT', 'category' );

                            if (is_wp_error($pat) || $pat == NULL ) {
                                $pat = wp_insert_term('PAT', 'category', [ 'slug' => 'PAT']);
                            }

                            array_push($CatId, $pat['term_id']);

                            $new_post = array(
                                'post_title' => html_entity_decode($title),
                                'post_content' => $corps,
                                'post_status' => 'publish',
                                'post_author' => 1,
                                'post_category' => $CatId
                            );

                            $postsId = wp_insert_post( $new_post );

                            $this->insertTitleNlpLinks($title,$result,$postsId);

                        }

                    }

                }

                update_option( $_REQUEST["key"], intval($_REQUEST["v"]));
                $this->bool = true;

                chmod($dir, 0777) ;
                rrmdir($dir);

            }

        }

    }

    public function insertTitleNlpLinks($txt,$link,$id){
        global $wpdb;
        $txt = htmlspecialchars($txt);
        $id = htmlspecialchars($id);
        $link = str_replace('archive/','',htmlspecialchars($link));
        $link = str_replace('corps/','newsletter_',htmlspecialchars($link));
        $link = str_replace('_corps.inc','',htmlspecialchars($link));
        $wpdb->insert("{$wpdb->prefix}titles_and_links", array('title' => $txt,'file' => $link,'idPost' => $id));
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