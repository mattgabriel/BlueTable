<?php

class emailTemplate {
	
	function __construct(){
		
	}
	
	
	


  function emptyLayout($body){
    return '
<table width="700" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#fafafa" >
 <tr>
  <td>
  <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="background-color:#f5f5f5;">
  <tr>
   <td style="padding:3px 0;">
   <table width="600" align="center" border="0" cellspacing="0" cellpadding="0">
    ' . $this->commonHeader() . '
  <tr>
   <td>
   <br><br>
  
  ' . $body . '

  <br><br>

  ' . $this->commonSignature . '

   </td>
  </tr>
   </table>
  ' . $this->commonFooter() . '
 </td>
 </tr>
</table>';
  }
	
	
	
	function commonHeader(){
		return '</table>
     </td>
    </tr>
   </table>
  <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="background-color:#333;border-top:4px solid #F14B5A;">
    <tr>
     <td style="padding:0;">
     <table width="600" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td style="padding:0;"><a href="' . WEB_URL . '">Logo goes here</a></td>
    </tr>
   </table>
     </td>
    </tr>
   </table>
   <table width="600" align="center" border="0" cellspacing="0" cellpadding="0">';
	}
	
	function commonFooter(){
		return '<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#333333" style="border-top:4px solid #F14B5A;">
    <tr>
     <td style="padding:5px 0;">
     <table width="600" align="center" border="0" cellspacing="0" cellpadding="0">
     
    <tr>
     <td>
     <p style="color:#FFF;font-size:11px;font-family:Helvetica, san-serif;margin-top:1em;margin-bottom:1em;">Copyright &copy; ' . date('Y') .'. All rights reserved.</p>
<p style="color:#FFF;font-size:11px;font-family:Helvetica, san-serif;margin-top:1em;margin-bottom:1em;">
     <a href="' . WEB_URL . '" style="color:#FFF;font-size:11px;font-family:Helvetica;text-decoration:none;font-weight:bold;">To unsubscribe from this list update your subscription preferences</a>
     </p>
     </td>
    </tr>
   </table>
     </td>
    </tr>
   </table>';
	}
	
	function commonSignature(){
		return '<p style="color:#333;font-size:13px;font-family:Helvetica, san-serif;margin-bottom:1em;">
Kind regards,<br>
The website name</p>';
	}

}
?>