<div class="container-fluid">

    <br />

    <div class="row">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID : </th>
                    <th>Fichiers : </th>
                    <th>Total : <?php echo $ret[0]->total ;?></th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($results as $result) { ?>

                <tr>
                    <td><strong><?php echo $result->id ;?></strong></td>
                    <td><strong><?php echo $result->file ;?></strong></td>
                </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>

</div>