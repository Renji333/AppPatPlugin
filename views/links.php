<h1>Gestion des liens :</h1>
<div class="container-fluid linkCss" style="margin-top:20px;">
    <div class="row">
        <div class="col-lg-6">

            <div class="row">
                <select class="selectPost" data-type="GET" data-live-search="true" data-show-subtext="true" title="Choix de l'article :" data-width="75%" data-size="20">
                    <?php
                    foreach ($results as $result) {
                        echo '<option value="'.$result->ID.'" data-guid="'.$result->guid.'" data-subtext="'.$result->post_date.'">'.$result->post_title.'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="row addBottom">
                <div id="CurrentArticle" class="col-lg-9" style="border:1px #ccc solid;display:none;">
                    <div class="row row-margin">
                        <h4 id="TitleCurrentArticle"></h4>
                    </div>
                    <div class="row row-margin addBottom">
                        <div class="btn-group" role="group">
                            <a type="button" id="EditCurrentArticle" class="btn btn btn-default" href=""  target="_blank">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                Editer
                            </a>
                            <a type="button" id="SeeCurrentArticle" class="btn btn btn-info" href=""  target="_blank">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <select class="selectPost" data-type="NLP" data-live-search="true" data-show-subtext="true" title="Choix de l'article de newsletters :" data-width="75%" data-size="10" disabled="disabled">
                    <?php
                    foreach($resultsNlp as $result) {
                        echo '<option value="'.$result->ID.'" data-type="NLP" data-guid="'.$result->guid.'" data-subtext="'.$result->post_date.'">'.$result->post_title.'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="row">
                <select class="selectPost" data-type="PAT" data-live-search="true" data-show-subtext="true" title="Choix de l'article de patrithèque :" data-width="75%" data-size="10" disabled="disabled">
                    <?php
                    foreach($resultsPat as $result) {
                        echo '<option value="'.$result->ID.'" data-type="PAT" data-guid="'.$result->guid.'" data-subtext="'.$result->post_date.'">'.$result->post_title.'</option>';
                    }
                    ?>
                </select>
            </div>

        </div>


        <div class="col-lg-6">

            <div class="row">
                <strong>Les liens vers la Patrith&eacute;que : </strong>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Titres</th>
                        <th>Option</th>
                    </tr>
                    </thead>
                    <tbody class="tbodyLink" data-type="PAT">

                    </tbody>
                </table>
            </div>

            <div class="row">
                <strong>Les liens vers les newsletters : </strong>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Titres</th>
                        <th>Option</th>
                    </tr>
                    </thead>
                    <tbody class="tbodyLink" data-type="NLP">

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
