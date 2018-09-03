<?php

class nlp{

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'check_dir'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
    }

    public function add_admin_menu()
    {
        add_menu_page('Import des Newsletters', 'Import des Newsletters', 'manage_options', 'nlp', array($this, 'display_home'));
    }

    public function display_home(){
        echo '<h1>Récupération des Newsletters : </h1>';
        $scan = $this->getFolder();
        include_once plugin_dir_path( __FILE__ ).'../views/nlp.php';
    }

    public function makeDate($e,$i){

        $tableHeure = ['00:00:00','00:00:01','00:00:02','00:00:03','00:00:04','00:00:05','00:00:06','00:00:07','00:00:08','00:00:09','00:00:10','00:00:11','00:00:12'];

        $e = str_replace(' ', '-', $e);
        $e = html_entity_decode($e);
        $e = str_replace('janvier', '01', $e);
        $e = str_replace('février', '02', $e);
        $e = str_replace('mars', '03', $e);
        $e = str_replace('avril', '04', $e);
        $e = str_replace('mai', '05', $e);
        $e = str_replace('juin', '06', $e);
        $e = str_replace('juillet', '07', $e);
        $e = str_replace('août', '08', $e);
        $e = str_replace('septembre', '09', $e);
        $e = str_replace('octobre', '10', $e);
        $e = str_replace('novembre', '11', $e);
        $e = str_replace('décembre', '12', $e);

        $e = date('Y-m-d H:i:s', strtotime($e.' '.$tableHeure[12 - $i]));

        return $e;
    }

    public function utf8_fopen_read($fileName) {
        $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
        $handle=fopen("php://memory", "rw");
        fwrite($handle, $fc);
        fseek($handle, 0);
        return $handle;
    }

    public function getFolder(){

        $path = plugin_dir_path( __FILE__ ).'../nlp_json/export_nlp/';

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
                $scan[$i]['files'] = count(glob(plugin_dir_path( __FILE__ )."../nlp_json/export_nlp/*.{txt}",GLOB_BRACE));
                $i = $i + 1;
            } else if (is_dir($path . '/' . $result)) {
                $scan[$i]['name'] = $result;
                $scan[$i]['files'] = count(glob(plugin_dir_path( __FILE__ )."../nlp_json/export_nlp/".$scan[$i]['name']."/*.{txt}",GLOB_BRACE));
                $i = $i + 1;
            }
        }

        return $scan;

    }

    public function check_dir(){

        function makeRegexSpaceSrcImg($e)
        {
            return 'src="../../../images/' . preg_replace('/\s/', "-", $e[1]) . '"';
        }

        if (isset($_GET['dir']) && !empty($_GET['dir'])) {

            $dir = $_GET['dir'];

            if($dir == "Racine"){
                $path = plugin_dir_path( __FILE__ ).'../nlp_json/export_nlp/';
            }else{
                $path = plugin_dir_path( __FILE__ ).'../nlp_json/export_nlp/'.$dir.'/';
            }

            if (!is_dir($path)) {
                return false;
            }

            $results = scandir($path);

            foreach ($results as $result) {

                if($result === '..' || $result === '.' || pathinfo($result, PATHINFO_EXTENSION) == NULL || pathinfo($result, PATHINFO_EXTENSION) == '' ) {

                    continue;

                } else {

                    $myfile = fopen($path."".$result, "r") or die("Unable to open file!");
                    $jsonTab = json_decode(fread($myfile,filesize($path."".$result)));
                    $iH = 0 ;
                    fclose($myfile);

                    if($jsonTab != null){
                        foreach ($jsonTab as $json) {

                            $CatId = [];

                            $corpsFile = $this->utf8_fopen_read( plugin_dir_path( __FILE__ ).'../nlp_json/'.str_replace("archive","",$json->corps), "r");
                            $UploadPath = wp_upload_dir();
                            $corps  = (string) fread($corpsFile,9999999);
                            $corps = preg_replace_callback('/src="..\/..\/..\/images\/(.*?)"/',"makeRegexSpaceSrcImg",$corps);
                            $corps = preg_replace('/<img src="..\/..\/..\/images\//','<img src="'.$UploadPath['url'].'/',$corps);
                            $corps = html_entity_decode($corps);

                            $corps = preg_replace('/<span(.*?)class="titre_1">(.*?)<\/span>/mi', '<h1>$2</h1>', $corps);
                            $corps = preg_replace('/<span(.*?)class="titre_2">(.*?)<\/span>/mi', '<h2>$2</h2>', $corps);
                            $corps = preg_replace('/<span(.*?)class="titre_3">(.*?)<\/span>/mi', '<h3>$2</h3>', $corps);
                            $corps = preg_replace('/<span(.*?)class="titre_4">(.*?)<\/span>/mi', '<h4>$2</h4>', $corps);
                            $corps = preg_replace('/<span(.*?)class="titre_5">(.*?)<\/span>/mi', '<h5>$2</h5>', $corps);
                            $corps = preg_replace('/<span(.*?)class="titre_6">(.*?)<\/span>/mi', '<h6>$2</h6>', $corps);

                            fclose($corpsFile);

                            $nlpCat = term_exists( 'NLP', 'category' );
                            if (is_wp_error($nlpCat) || $nlpCat == NULL ) {
                                $nlpCat = wp_insert_term('NLP', 'category', [ 'slug' => 'NLP']);
                            }
                            array_push($CatId, $nlpCat['term_id']);

                            $new_post = array(
                                'post_title' => html_entity_decode($json->title),
                                'post_content' => $corps,
                                'post_date' => $this->makeDate($json->date,$iH),
                                'post_status' => 'publish',
                                'post_author' => 1,
                                'post_category' => $CatId
                            );

                            $postsId = wp_insert_post( $new_post );
                            $iH = $iH + 1;

                        }
                    }else{
                        echo '<div class="alert alert-warning" role="alert">'.$result.'</div>';
                    }
                }
            }

            //array_map('unlink', glob("$path*.*"));
            //rmdir($path);


        }

    }


}