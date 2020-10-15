<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form1')
{
   $mailto = 'laudarroux@hotmail.com';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Query';
   $message = 'Form details :';
   $success_url = '';
   $error_url = '';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;

   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (!is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=ISO-8859-1'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      if ($mailto != '')
      {
         mail($mailto, $subject, $body, $header);
      }
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Query</title>
<meta name="generator" content="Quick 'n Easy Web Builder Trial Version - http://www.quickandeasywebbuilder.com">
<style>
div#container
{
   width: 994px;
   position: relative;
   margin: 0 auto 0 auto;
   text-align: left;
}
body
{
   background-color: transparent;
   color: #000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   line-height: 1.1875;
   margin: 0;
   text-align: center;
}
</style>
<link href="wb.validation.css" rel="stylesheet">
<style>
a
{
   color: #0000FF;
   text-decoration: underline;
}
a:visited
{
   color: #800080;
}
a:active
{
   color: #FF0000;
}
a:hover
{
   color: #0000FF;
   text-decoration: underline;
}
#wb_Form1
{
   background-color: #FBFBFB;
   background-image: none;
   border: 0px solid #000000;
}
#Label2
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 20px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
   vertical-align: top;
}
#Editbox1
{
   border: 1px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 17px;
   line-height: 35px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
}
#Editbox1:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   outline: 0;
}
#Label4
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 20px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
   vertical-align: top;
}
#Editbox2
{
   border: 1px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 17px;
   line-height: 30px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
}
#Editbox2:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   outline: 0;
}
#Label6
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 20px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
   vertical-align: top;
}
#Editbox3
{
   border: 1px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 17px;
   line-height: 30px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
}
#Editbox3:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   outline: 0;
}
#Label8
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 20px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
   vertical-align: top;
}
#TextArea1
{
   border: 1px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 17px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
   resize: none;
}
#TextArea1:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102, 175, 233, 0.60);
   outline: 0;
}
#TextArea1::placeholder
{
   color: #A9A9A9;
   opacity: 1;
}
#TextArea1::-ms-input-placeholder
{
   color: #A9A9A9;
}
#Label9
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 20px;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
   vertical-align: top;
}
#wb_Checkbox1
{
   margin: 0;
}
#wb_Checkbox1, #wb_Checkbox1 *, #wb_Checkbox1 *::before, #wb_Checkbox1 *::after
{
   box-sizing: border-box;
}
#wb_Checkbox1 input[type='checkbox']
{
   position: absolute;
   padding: 0;
   margin: 0;
   opacity: 0;
   z-index: 1;
   width: 20px;
   height: 20px;
   left: 0;
   top: 0;
}
#wb_Checkbox1 label
{
   display: inline-block;
   vertical-align: middle;
   position: absolute;
   left: 0;
   top: 0;
   width: 0;
   height: 0;
   padding: 0;
}
#wb_Checkbox1 label::before
{
   content: "";
   display: inline-block;
   position: absolute;
   width: 20px;
   height: 20px;
   left: 0;
   top: 0;
   background-color: #FFFFFF;
   border: 1px solid #CCCCCC;
   border-radius: 4px;
}
#wb_Checkbox1 label::after
{
   display: inline-block;
   position: absolute;
   width: 20px;
   height: 20px;
   left: 0;
   top: 0;
   padding: 0;
   text-align: center;
   line-height: 20px;
}
#wb_Checkbox1 input[type='checkbox']:checked + label::after
{
   content: " ";
   background: url('data:image/svg+xml,%3Csvg%20height%3D%2220%22%20width%3D%2220%22%20version%3D%221.1%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20style%3D%22fill%3A%23FFFFFF%22%20transform%3D%22scale%280.0112%29%22%3E%0D%0A%3Cpath%20transform%3D%22rotate%28180%29%20scale%28-1%2C1%29%20translate%280%2C-1536%29%22%20d%3D%22M1671%20970q0%20-40%20-28%20-68l-724%20-724l-136%20-136q-28%20-28%20-68%20-28t-68%2028l-136%20136l-362%20362q-28%2028%20-28%2068t28%2068l136%20136q28%2028%2068%2028t68%20-28l294%20-295l656%20657q28%2028%2068%2028t68%20-28l136%20-136q28%20-28%2028%20-68z%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E') no-repeat center center;
   background-size: 80% 80%
}
#wb_Checkbox1 input[type='checkbox']:checked + label::before
{
   background-color: #3370B7;
   background-image: none;
   border-color: #3370B7;
}
#wb_Checkbox1 input[type='checkbox']:focus + label::before
{
   outline: thin dotted;
}
#Button1
{
   border: 1px solid #2E6DA4;
   border-radius: 4px;
   background-color: #4169E1;
   background-image: none;
   color: #FFFFFF;
   font-family: Arial;
   font-weight: normal;
   font-size: 20px;
   -webkit-appearance: none;
   margin: 0;
}
</style>
<script src="js/jquery-1.12.4.min.js"></script>
<script src="js/wb.validation.min.js"></script>
<script>
function submitInquery()
{
   var regexp;
   var Editbox1 = document.getElementById('Editbox1');
   if (!(Editbox1.disabled || Editbox1.style.display === 'none' || Editbox1.style.visibility === 'hidden'))
   {
      if (Editbox1.value == "")
      {
         alert("Name required");
         Editbox1.focus();
         return false;
      }
   }
   var Editbox3 = document.getElementById('Editbox3');
   if (!(Editbox3.disabled || Editbox3.style.display === 'none' || Editbox3.style.visibility === 'hidden'))
   {
      regexp = /^[-+]?\d*\.?\d*$/;
      if (Editbox3.value.length != 0 && !regexp.test(Editbox3.value))
      {
         alert("Enter a valid number");
         Editbox3.focus();
         return false;
      }
   }
   var TextArea1 = document.getElementById('TextArea1');
   if (!(TextArea1.disabled || TextArea1.style.display === 'none' || TextArea1.style.visibility === 'hidden'))
   {
      if (TextArea1.value == "")
      {
         alert("Enter message");
         TextArea1.focus();
         return false;
      }
   }
   return true;
}
</script>
<script>
$(document).ready(function()
{
   $("#Form1").submit(function(event)
   {
      var isValid = $.validate.form(this);
      return isValid;
   });
   $("#Editbox2").validate(
   {
      required: true,
      type: 'email',
      color_text: '#000000',
      color_hint: '#00FF00',
      color_error: '#FF0000',
      color_border: '#808080',
      nohint: false,
      font_family: 'Arial',
      font_size: '13px',
      position: 'topleft',
      offsetx: 0,
      offsety: 0,
      effect: 'none',
      error_text: 'Enter a valid email address'
   });
   $("#Checkbox1").validate(
   {
      required: true,
      type: 'checkbox',
      color_text: '#000000',
      color_hint: '#00FF00',
      color_error: '#FF0000',
      color_border: '#808080',
      nohint: false,
      font_family: 'Arial',
      font_size: '13px',
      position: 'topleft',
      offsetx: 0,
      offsety: 0,
      effect: 'none',
      error_text: 'Show consent'
   });
});
</script>
</head>
<body>
<div id="container">
<div id="wb_Form1" style="position:absolute;left:187px;top:60px;width:555px;height:405px;z-index:9;">
<form name="Inquery" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="Form1" onsubmit="return submitInquery()">
<input type="hidden" name="formid" value="form1">
<label for="Editbox1" id="Label2" style="position:absolute;left:10px;top:4px;width:312pxheight:17px;line-height:17px;z-index:0;">Name</label>
<input type="text" id="Editbox1" style="position:absolute;left:12px;top:33px;width:502px;height:35px;z-index:1;" name="Editbox1" value="" spellcheck="false" placeholder="Enter name here">
<label for="Editbox2" id="Label4" style="position:absolute;left:10px;top:90px;width:312pxheight:17px;line-height:17px;z-index:2;">Email</label>
<input type="text" id="Editbox2" style="position:absolute;left:13px;top:117px;width:502px;height:30px;z-index:3;" name="Editbox2" value="" spellcheck="false" placeholder="Enter your email here">
<input type="text" id="Editbox3" style="position:absolute;left:12px;top:193px;width:503px;height:30px;z-index:4;" name="Editbox3" value="" spellcheck="false" placeholder="Enter your number here">
<label for="TextArea1" id="Label8" style="position:absolute;left:12px;top:240px;width:388pxheight:17px;line-height:17px;z-index:5;">Please tell us a little about how we can help</label>
<textarea name="TextArea1" id="TextArea1" style="position:absolute;left:15px;top:272px;width:500px;height:90px;z-index:6;" rows="1" cols="1" spellcheck="false" placeholder="Enter your message here"></textarea>
<label for="Editbox3" id="Label6" style="position:absolute;left:10px;top:165px;width:312pxheight:17px;line-height:17px;z-index:7;">Your Number</label>
<div id="wb_Checkbox1" style="position:absolute;left:15px;top:382px;width:20px;height:20px;z-index:8;">
<input type="checkbox" id="Checkbox1" name="" value="on" checked style="position:absolute;left:0;top:0;"><label for="Checkbox1"></label></div>
</form>
</div>
<label for="Checkbox1" id="Label9" style="position:absolute;left:228px;top:441px;width:475pxheight:17px;line-height:17px;z-index:10;">I consent to Spirit of Care collecting my personal data</label>
<input type="submit" id="Button1" name="" value="Submit" style="position:absolute;left:202px;top:485px;width:96px;height:51px;z-index:11;">
</div>
</body>
</html>