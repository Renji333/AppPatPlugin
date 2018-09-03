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
<TITLE>Newsletter <!--#include file = "date-du-jour.inc.asp"--> - <!--#include file = "titres/article_1_titre.inc.asp"--> - Version imprimable</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="../../styles/Patritheque.css" type="text/css">
<link rel="stylesheet" href="../../styles/imprimante.css" type="text/css" media="print">

<script language="JavaScript" src="../../images/plad.js"></script>

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
.Style5 {font-size: 12px; text-align: justify; font-style: normal; line-height: normal; color: #333333; text-decoration: none; font-family: Arial, Helvetica, sans-serif;}
.Style6 {font-style: italic}
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
          <td width="4" height="35">&nbsp;</td>
          <td width="462" class="millesime Style4"><img src="../../images/img_1_imprim.gif" alt="Newsletter Patrith&egrave;que - version imprimable" width="279" height="79" border="0"></td>
          <td width="86" class="noprint">&nbsp;</td>
          <td width="6"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="normal">
		  

	<p class="millesime"><!--#include file = "date-du-jour.inc.asp"--></p>	<div id="contenu-titre">
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
                <td width="338" class="bas_page Style3"><div align="center" class="bas_page_fonctions">www.patritheque.fr&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Tous droits r&eacute;serv&eacute;s&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;2018</div>
                </div></td>
              </tr>
            </table>          </td>
        </tr>
      </table>
    </TD>
  </TR>
</TABLE>

<!--#include file="../../ctrl_acces/ga.asp"-->
</BODY>
</html>