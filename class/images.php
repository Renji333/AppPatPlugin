<?php

class images
{

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'check_dir'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    public function add_admin_menu()
    {
        add_menu_page('Récupération des Images de NLP', 'Récupération des Images de NLP', 'manage_options', 'img', array($this, 'display_home'));
    }

    public function display_home(){
        echo '<h1>Récupération des Images : </h1>';
        $scan = $this->getFolder();
        include_once plugin_dir_path( __FILE__ ).'../views/images.php';
    }

    public function getFolder(){

        $path = plugin_dir_path( __FILE__ ).'../nlp_json/images/';

        if (!is_dir($path)) {
            echo '<div class="alert alert-warning" role="alert">Aucun répertoire trouvé !</div>';
            return false;
        }

        $results = scandir($path);
        $scan = [];
        $i = 0 ;

        foreach ($results as $result) {

            if($result === '..'){

                continue;

            } else if ($result === '.'){

                $scan[$i]['name'] = "Racine";
                $scan[$i]['files'] = count(glob(plugin_dir_path( __FILE__ )."../nlp_json/images/*.{jpg,png,gif,bmp}",GLOB_BRACE));
                $i = $i + 1;

            } else if (is_dir($path . '/' . $result)) {

                $scan[$i]['name'] = $result;
                $scan[$i]['files'] = count(glob(plugin_dir_path( __FILE__ )."../nlp_json/images/".$scan[$i]['name']."/*.{jpg,png,gif,bmp}",GLOB_BRACE));
                $i = $i + 1;

            }

        }

        return $scan;

    }

    public function check_dir(){
        if (isset($_GET['dir']) && !empty($_GET['dir'])) {
            $dir = $_GET['dir'];

            if($dir == "Racine"){
                $path = plugin_dir_path( __FILE__ ).'../nlp_json/images/';
            }else{
                $path = plugin_dir_path( __FILE__ ).'../nlp_json/images/'.$dir.'/';
            }

            if (!is_dir($path)) {
                return false;
            }

            $results = scandir($path);

            foreach ($results as $result) {
                if($result === '..' || $result === '.' || pathinfo($result, PATHINFO_EXTENSION) == 'js' || pathinfo($result, PATHINFO_EXTENSION) == NULL || pathinfo($result, PATHINFO_EXTENSION) == '' ) {

                    continue;

                } else {

                    $img = $path. '' .$result;
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
                            $this->Logs->insertLogs($result,'img');
                            echo '<div class="alert alert-warning" role="alert">'.$result.'</div>';
                        }
                    }else{
                        $this->Logs->insertLogs($result,'img');
                        echo '<div class="alert alert-danger" role="alert">'.$result.'</div>';
                    }


                }
            }

            array_map('unlink', glob("$path*.*"));
            rmdir($path);

        }
    }


}
