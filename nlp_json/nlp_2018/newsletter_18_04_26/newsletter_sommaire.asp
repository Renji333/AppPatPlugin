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
<TITLE>Sommaire de la Newsletter Patrith&egrave;que du <!--#include file = "date-du-jour.inc.asp"--></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
		<link href="../../css/style_espaceclient.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../images/plad.js"></script>

<script language="JavaScript">
function impression() {window.open('newsletter_integrale_imprim.asp', 'NEW_FRAME', 'scrollbars=yes,status=no,width=650,height=590');}
</script>

<style type="text/css">
<!--
.Style1 {color: #3F78AE}
-->
</style>
</HEAD>
	<body>
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

		      <div id="contenu-titre">
    <span class="titre_1">Newsletter du <!--#include file = "date-du-jour.inc.asp"--></span><span class="normal"><br>
	</div>
    <br>

          <span class="titre_intro"><!--#include file = "titres/article_1_titre.inc.asp"--></span><br>
          <span class="normal"><!--#include file = "resumes/article_1_resume.inc.asp"--></span><br>
          <a href="newsletter_article_1.asp" class="lien_intro_bleu_clair">&gt;&gt;&nbsp;Lire l'article</a><br><br>

          <span class="titre_intro"><!--#include file = "titres/article_2_titre.inc.asp"--></span><br>
          <span class="normal"><!--#include file = "resumes/article_2_resume.inc.asp"--></span><br>
          <a href="newsletter_article_2.asp" class="lien_intro_bleu_clair">&gt;&gt;&nbsp;Lire l'article</a><br><br>

          <span class="titre_intro"><!--#include file = "titres/article_3_titre.inc.asp"--></span><br>
          <span class="normal"><!--#include file = "resumes/article_3_resume.inc.asp"--></span><br>
          <a href="newsletter_article_3.asp" class="lien_intro_bleu_clair">&gt;&gt;&nbsp;Lire l'article</a><br><br>

          <span class="titre_intro"><!--#include file = "titres/article_4_titre.inc.asp"--></span><br>
          <span class="normal"><!--#include file = "resumes/article_4_resume.inc.asp"--></span><br>
          <a href="newsletter_article_4.asp" class="lien_intro_bleu_clair">&gt;&gt;&nbsp;Lire l'article</a><br><br>

          <span class="titre_intro"><!--#include file = "titres/article_5_titre.inc.asp"--></span><br>
          <span class="normal"><!--#include file = "resumes/article_5_resume.inc.asp"--></span><br>
          <a href="newsletter_article_5.asp" class="lien_intro_bleu_clair">&gt;&gt;&nbsp;Lire l'article</a><br><br>




</span>
<!-- End ImageReady Slices -->
				<!-- Fin de la Zone de contenu de la page -->
			</div>
			<div id="colonne">
				<!--#include file = "../../include/colonne-bloc_cli-imprimer_integrale.inc.asp"--> <div class="theme theme_court"> <div class="icon"><img src="../../images/site/picto-pdf.png" alt="Menu article" /></div> <div class="texte"> <h3>Version PDF</h3> <ul class="std"> <li><a href="javascript:var w=window.open('Newsletter-Patritheque_343_26-avril-2018.pdf','', 'height=650, width=980,toolbar=0,location=0,directories=1,status=0,menubar=0,scrollbars=1,resizable=1,copyhistory=0' ); ">Télécharger l'intégralité de la Newsletter</a></li></ul> </ul> </div> </div> <div class="fin_theme"></div> <!--#include file = "../../include/colonne-bloc_cli-une.inc.asp"--> 
			</div>
			<div id="footer">
				<!--#include file = "../../include/footer_cli-n2.inc.asp"--> 
			</div>
		</div>

<!--#include file="../../ctrl_acces/ga.asp"-->
</BODY>
</html>