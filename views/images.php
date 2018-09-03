<div class="container-fluid">
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Id</th>
                <th>Nom :</th>
                <th>Nombre de fichiers :</th>
                <th>Importer</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $i = 1 ;
            if($scan){
                foreach ($scan as $scanItem) {
                    echo '<tr><td><strong> '.  $i.'</strong></td><td><strong> '.$scanItem['name'].'</strong></td><td><strong> '.$scanItem['files'].'</strong></td><td><a href="?page=img&dir='.$scanItem['name'].'" class="btn btn-primary">Importer</a></td></tr>';
                    $i = $i + 1;
                }
            }
            ?>

            </tbody>
        </table>
    </div>
</div>
