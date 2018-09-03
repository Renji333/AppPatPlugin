<%
dim selectMenu
selectMenu = ""
%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
<!--#include file="../../ctrl_acces/cookie_tools.asp"-->
<% 
If not isOkToEnter(Request) then  
 Response.Clear 
 Response.Redirect("../../index.asp?Msg=5") 
End if 

If not isOkProfil(Request,"NLP") then 
 
  Response.Clear 
 Response.Redirect("../../index.asp?Msg=5") 
End if 
%>
<html>
<HEAD>
<TITLE>Newsletter Patrith&egrave;que du <!--#include file = "date-du-jour.inc.asp"--> - <!--#include file = "titres/article_1_titre.inc.asp"--></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
		<link href="../../css/style_espaceclient.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../images/plad.js"></script>

<script language="JavaScript">
function impression() {window.open('newsletter_article_1_imprim.asp', 'NEW_FRAME', 'scrollbars=yes,status=no,width=650,height=590');}
</script>

<style type="text/css">
<!--
.millesime1 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: bold; color: #1A4159; text-decoration: none }
.Style3 {font-style: italic}
.Style4 {font-style: italic}
-->
</style>

</HEAD>
	<body>
	<a name="hautdepage" id="hautdepage"></a>
		<div id="container">
			<div id="header">
				<!--#include file = "../../include/header_cli-n2.inc.asp"--> 
			</div>
			<div id="subheader_page">
				<div class="logoBIG"><img src="../../images/site/bandeau_newsletter_patritheque_960-80_2.jpg" alt="La Newsletter Patrithèque" /></div>
			</div>
			<div id="before_contenu">
				<div class="visu_navigation"></div></div>
			<div id="contenu">
				<!-- Zone de contenu de la page -->

<BODY BGCOLOR=#E7F2F7 link="#1A4159" vlink="#1A4159" alink="#1A4159">

  <!-- ImageReady Slices (maquette_4.psd) -->

	<p class="millesime"><!--#include file = "date-du-jour.inc.asp"--></p>	<div id="contenu-titre">
    <span class="titre_1"><!--#include file = "titres/article_1_titre.inc.asp"--></span><span class="normal"><br>
	</div>
    <br>
    <div id="contenu-article"><span class="normal">
	
	<!--#include file = "corps/article_1_corps.inc.asp"-->	
	</div><a href="#hautdepage"><img src="../../images/site/symbole_haut.jpg" title="Retour haut de page" /></a>&nbsp;&nbsp;&nbsp;<a href="javascript:impression();"><img src="../../images/site/symbole_imprimer.jpg" title="Imprimer l'article" /></a>&nbsp;&nbsp;&nbsp;<a href="newsletter_sommaire.asp"><img src="../../images/site/symbole_sommaire.jpg" title="Sommaire de cette Newsletter" /></a>&nbsp;&nbsp;&nbsp;<a href="javascript:var w=window.open('../../formulaire.asp?action=contact2','', 'height=650, width=800,toolbar=0,location=0,directories=1,status=0,menubar=0,scrollbars=1,resizable=1,copyhistory=0' ); "><img src="../../images/site/symbole_contact.jpg" title="Une question ou suggestion ?" /></a>
	<br /><br /><br />
	</span>

	
<!-- End ImageReady Slices -->
				<!-- Fin de la Zone de contenu de la page -->
			</div>
			<div id="colonne"> 
				<!--#include file = "colonne-bloc_sommaire.inc.asp"--> 
				<!--#include file = "colonne-liens/article_1_bloc-liens.inc.asp"--> 
			</div>
			<div id="footer">
				<!--#include file = "../../include/footer_cli-n2.inc.asp"--> 
			</div>
		</div>
<!--#include file="../../ctrl_acces/ga.asp"-->
</BODY>
</html>