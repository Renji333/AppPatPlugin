<h1>Gestion des aides patrithèque :</h1>

<div class="container-fluid linkCss" style="margin-top:20px;">
    <div class="row">
        <div class="col-lg-11">
            <table class="table table-bordered">
                <tr>
                    <th>#</th>
                    <th>Version actuelle</th>
                    <th>Mise à jour de l'aide</th>
                    <th>Résultat de l'opération</th>
                </tr>
                <tr>

                    <?php foreach ($v as $key => $value){ ?>

                        <td>
                            <h4>
                                <?php echo strtoupper($key);?>
                            </h4>
                        </td>

                        <td>
                            <?php echo $value;?>
                        </td>

                        <td>

                            <form enctype="multipart/form-data" action="" method="post">

                                <div class="form-group col-lg-6">

                                    <input type="text" class="form-control" name="v" placeholder="Numéro de version : Exemple : 1801" required/>
                                    <input type="hidden" class="form-control" name="key" value="<?php echo $key;?>" required/>

                                    <label for="txt">Insérer le fichier (.zip) contenant l'aide : </label>
                                    <input type="file" class="form-control" name="zip" required/>

                                </div>

                                <button type="submit" class="btn btn-primary">Mettre à jour</button>

                            </form>

                        </td>



                    <?php };?>

                </tr>
            </table>
        </div>
    </div>
</div>