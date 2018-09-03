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
<TITLE>Newsletter Patrith&egrave;que - <!--#include file = "date-du-jour.inc.asp"--> - Version imprimable int&eacute;grale</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="../../styles/Patritheque.css" type="text/css">
<link rel="stylesheet" href="../../styles/imprimante.css" type="text/css" media="print">

<script language="JavaScript" src="../../images/plad.js"></script>
<script language="JavaScript">

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
</script>

<script language="javascript">
function FermeFenetre()
{window.close();}
</script>

<script language="javascript">
window.print();
</script>

<style type="text/css">

<!--
.millesime1 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: bold; color: #1A4159; text-decoration: none }
.Style4 {font-size: 18px}
.titre_intro1 {font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-style: normal; font-weight: bold; color: #1A4159; text-decoration: none}
.lien_intro_souligne1 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #1A4159;  text-decoration: none}
.normal1 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; line-height: normal; font-weight: normal; color: #333333; text-decoration: none}
.Style5 {font-style: italic}
-->
</style>
</HEAD>
<a name="hautdepage"></a>
<BODY link="#1A4159" vlink="#1A4159" alink="#1A4159">
<TABLE WIDTH="560" CELLPADDING="0" CELLSPACING"0" align="center" style="border-style:solid; border-width:1px; border-color:#1A4159">
  <TR> 
    <TD align="left" valign="top"></TD>
  </TR>
  <TR> 
    <TD> 
      <table cellpadding="0" cellspacing="0" >
        <tr>
          <td width="5" height="35">&nbsp;</td>
          <td width="312" class="millesime Style4"><img src="../../images/img_1_imprim.gif" alt="Newsletter Patrith&egrave;que" width="279" height="79" border="0"></td>
          <td width="235" class="noprint">&nbsp;</td>
          <td width="6"></td>
        </tr>
        <tr> 
          <td height="35"valign="top"></td>
          <td colspan="2" valign="top" class="millesime"><!--#include file = "date-du-jour.inc.asp"--></td>
          <td></td>
        </tr>
        <tr> 
          <td></td>
          <td colspan="2" class="normal"></td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="titre_1">Sommaire</td>
          <td>&nbsp;</td>
        </tr>


<!-- Début des titres d'articles -->
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal"><span class="ul">
              <ul>
              <li class="titre_intro"><!--#include file = "titres/article_1_titre.inc.asp"--></li>
			  </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal"><span class="ul">
		    <ul>
			<li class="titre_intro"><!--#include file = "titres/article_2_titre.inc.asp"--></li>
			</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal"><span class="ul">
		    <ul>
			<li class="titre_intro"><!--#include file = "titres/article_3_titre.inc.asp"--></li>
			</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal"><span class="ul">
		    <ul>
			  <li class="titre_intro"><!--#include file = "titres/article_4_titre.inc.asp"--></li>
			  </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal"><span class="ul">
		    <ul>
			  <li class="titre_intro"><!--#include file = "titres/article_5_titre.inc.asp"--></li>
			  </td>
          <td>&nbsp;</td>
        </tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" style="border-bottom:0.50pt; border-bottom-style:dashed; border-color:#1A4159;">&nbsp;</td>
<td>&nbsp;</td>
</tr>

<!-- Fin des titres d'articles -->

        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal">

<!-- Début article 1 -->	<div id="contenu-titre">
    <span class="titre_1"><!--#include file = "titres/article_1_titre.inc.asp"--></span><span class="normal"><br>
	</div>
    <br>
    <div id="contenu-article"><span class="normal">
	
	<!--#include file = "corps/article_1_corps.inc.asp"-->	
	</div></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" style="border-bottom:0.50pt; border-bottom-style:dashed; border-color:#1A4159;">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">
		  
<!-- Début article 2 -->	<div id="contenu-titre">
    <span class="titre_1"><!--#include file = "titres/article_2_titre.inc.asp"--></span><span class="normal"><br>
	</div>
    <br>
    <div id="contenu-article"><span class="normal">
	
	<!--#include file = "corps/article_2_corps.inc.asp"-->	
	</div></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" style="border-bottom:0.50pt; border-bottom-style:dashed; border-color:#1A4159;">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">

<!-- Début article 3 -->	<div id="contenu-titre">
    <span class="titre_1"><!--#include file = "titres/article_3_titre.inc.asp"--></span><span class="normal"><br>
	</div>
    <br>
    <div id="contenu-article"><span class="normal">
	
	<!--#include file = "corps/article_3_corps.inc.asp"-->	
	</div></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" style="border-bottom:0.50pt; border-bottom-style:dashed; border-color:#1A4159;">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">
		  
<!-- Début article 4 -->	<div id="contenu-titre">
    <span class="titre_1"><!--#include file = "titres/article_4_titre.inc.asp"--></span><span class="normal"><br>
	</div>
    <br>
    <div id="contenu-article"><span class="normal">
	
	<!--#include file = "corps/article_4_corps.inc.asp"-->	
	</div></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" style="border-bottom:0.50pt; border-bottom-style:dashed; border-color:#1A4159;">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" class="normal">
		  
<!-- Début article 5 -->	<div id="contenu-titre">
    <span class="titre_1"><!--#include file = "titres/article_5_titre.inc.asp"--></span><span class="normal"><br>
	</div>
    <br>
    <div id="contenu-article"><span class="normal">
	
	<!--#include file = "corps/article_5_corps.inc.asp"-->	
	</div><!-- FIN DE LA NLP INTEGRALE -->

</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" style="border-bottom:0.50pt; border-bottom-style:dashed; border-color:#1A4159;">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
        

        
        <tr>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </TD>
  </TR>
  <TR> 
    <TD> 
      <table width="560" cellpadding="0" cellspacing="0">
        <tr bgcolor="#FFFFFF"> 
          <td width="158"><a href="http://www.harvest.fr" target="_blank"><img src="../../images/img_3.gif" alt="Logo Harvest - lien vers le site" width="160" height="30" border="0"></a></td>
          <td width="400" class="bas_page">
            <table width="340" cellpadding="0" cellspacing="0" bordercolor="#CC3333" >
              <tr> 
                <td width="338" class="bas_page Style3"><div align="center" class="bas_page_fonctions">www.patritheque.fr&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Tous droits r&eacute;serv&eacute;s&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;2018</div></td>

              </tr>
            </table>
          </td>
        </tr>
      </table>
    </TD>
  </TR>
</TABLE>
<!--#include file="../../ctrl_acces/ga.asp"-->
</BODY>
</html>