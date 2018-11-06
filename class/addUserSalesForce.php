<?php

function addUserSalesForce($e){

    $xml = new SimpleXMLElement($e);

    if($xml->Body->NouveauClient->client != null){

        $client = $xml->Body->NouveauClient->client;

        $id = wp_create_user( $client->Email[0], $client->Motdepasse[0], $client->Email[0]) ;

        update_user_meta( $id, "Societe", $client->Societe[0] );
        update_user_meta( $id, "Codeclient", $client->Codeclient[0] );
        update_user_meta( $id, "Profil", $client->Profil[0] );
        update_user_meta( $id, "Civilite", $client->Civilite[0] );
        update_user_meta( $id, "Datefin", $client->Datefin[0] );
        update_user_meta( $id, "Ville", $client->Ville[0] );
        update_user_meta( $id, "Codepostal", $client->Codepostal[0] );
        update_user_meta( $id, "Statut", $client->Statut[0] );
        update_user_meta( $id, "Fonction", $client->Fonction[0] );
        update_user_meta( $id, "Telephone", $client->Telephone[0] );

        update_user_meta( $id, "first_name", $client->Prenom[0] );
        update_user_meta( $id, "last_name", $client->Nom[0] );

        //wp_update_user( array ('ID' => $client->Idutilisateur[0] ) ) ;

    }

}

/* addUserSalesForce("
<Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap12=\"http://www.w3.org/2003/05/soap-envelope\">
  <Body>
    <NouveauClient xmlns=\"http://www.patritheque.fr/wspat\">
      <client>
        <Nom>HERBRAND</Nom>
        <Prenom>Pascal</Prenom>
        <Email>pascal-herbrand@gmail.com</Email>
        <Societe>ALGO</Societe>
        <Codeclient>xxx</Codeclient>
        <Profil>NLP</Profil>
        <Idutilisateur>xxx</Idutilisateur>
        <Motdepasse>xxxx</Motdepasse>
        <Civilite>M.</Civilite>
        <Datefin>1974-01-15</Datefin>
        <Ville>paris</Ville>
        <Codepostal>75015</Codepostal>
        <Statut>CLIENT</Statut>
        <Fonction>Chef de produit</Fonction>
        <Telephone>xxxxxxx</Telephone>
      </client>
    </NouveauClient>
  </Body>
</Envelope>
" ); */