<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'layoutgrid1')
{
   $mailto = 'laudarroux@hotmail.com';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Query via website';
   $message = 'Values submitted from web site form:';
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
<title>Test Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body
{
   background-color: #FFFFFF;
   color: #000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   line-height: 1.1875;
   margin: 0;
   padding: 0;
}
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
#wb_LayoutGrid1
{
   clear: both;
   position: relative;
   table-layout: fixed;
   display: table;
   text-align: center;
   width: 100%;
   background-color: transparent;
   background-image: none;
   border: 0px solid #000000;
   box-sizing: border-box;
   margin: 0;
}
#LayoutGrid1
{
   box-sizing: border-box;
   padding: 0px 15px 0px 15px;
   margin-right: auto;
   margin-left: auto;
}
#LayoutGrid1 > .row
{
   margin-right: -15px;
   margin-left: -15px;
}
#LayoutGrid1 > .row > .col-1
{
   box-sizing: border-box;
   font-size: 0px;
   min-height: 1px;
   padding-right: 15px;
   padding-left: 15px;
   position: relative;
}
#LayoutGrid1 > .row > .col-1
{
   float: left;
}
#LayoutGrid1 > .row > .col-1
{
   background-color: transparent;
   background-image: none;
   width: 100%;
   text-align: left;
}
#LayoutGrid1:before,
#LayoutGrid1:after,
#LayoutGrid1 .row:before,
#LayoutGrid1 .row:after
{
   display: table;
   content: " ";
}
#LayoutGrid1:after,
#LayoutGrid1 .row:after
{
   clear: both;
}
@media (max-width: 480px)
{
#LayoutGrid1 > .row > .col-1
{
   float: none;
   width: 100% !important;
}
}
#wb_LayoutGrid2
{
   clear: both;
   position: relative;
   table-layout: fixed;
   display: table;
   text-align: center;
   width: 100%;
   background-color: transparent;
   background-image: none;
   border: 0px solid #000000;
   box-sizing: border-box;
   margin: 0;
}
#LayoutGrid2
{
   box-sizing: border-box;
   padding: 5px 15px 5px 15px;
   margin-right: auto;
   margin-left: auto;
}
#LayoutGrid2 > .row
{
   margin-right: -15px;
   margin-left: -15px;
}
#LayoutGrid2 > .row > .col-1, #LayoutGrid2 > .row > .col-2
{
   box-sizing: border-box;
   font-size: 0px;
   min-height: 1px;
   padding-right: 15px;
   padding-left: 15px;
   position: relative;
}
#LayoutGrid2 > .row > .col-1, #LayoutGrid2 > .row > .col-2
{
   float: left;
}
#LayoutGrid2 > .row > .col-1
{
   background-color: transparent;
   background-image: none;
   width: 25%;
   text-align: left;
}
#LayoutGrid2 > .row > .col-2
{
   background-color: transparent;
   background-image: none;
   width: 75%;
   text-align: left;
}
#LayoutGrid2:before,
#LayoutGrid2:after,
#LayoutGrid2 .row:before,
#LayoutGrid2 .row:after
{
   display: table;
   content: " ";
}
#LayoutGrid2:after,
#LayoutGrid2 .row:after
{
   clear: both;
}
@media (max-width: 480px)
{
#LayoutGrid2 > .row > .col-1, #LayoutGrid2 > .row > .col-2
{
   float: none;
   width: 100% !important;
}
}
#Label1
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   box-sizing: border-box;
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
   font-size: 13px;
   box-sizing: border-box;
   line-height: 15px;
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
#wb_LayoutGrid3
{
   clear: both;
   position: relative;
   table-layout: fixed;
   display: table;
   text-align: center;
   width: 100%;
   background-color: transparent;
   background-image: none;
   border: 0px solid #000000;
   box-sizing: border-box;
   margin: 0;
}
#LayoutGrid3
{
   box-sizing: border-box;
   padding: 5px 15px 5px 15px;
   margin-right: auto;
   margin-left: auto;
}
#LayoutGrid3 > .row
{
   margin-right: -15px;
   margin-left: -15px;
}
#LayoutGrid3 > .row > .col-1, #LayoutGrid3 > .row > .col-2
{
   box-sizing: border-box;
   font-size: 0px;
   min-height: 1px;
   padding-right: 15px;
   padding-left: 15px;
   position: relative;
}
#LayoutGrid3 > .row > .col-1, #LayoutGrid3 > .row > .col-2
{
   float: left;
}
#LayoutGrid3 > .row > .col-1
{
   background-color: transparent;
   background-image: none;
   width: 25%;
   text-align: left;
}
#LayoutGrid3 > .row > .col-2
{
   background-color: transparent;
   background-image: none;
   width: 75%;
   text-align: left;
}
#LayoutGrid3:before,
#LayoutGrid3:after,
#LayoutGrid3 .row:before,
#LayoutGrid3 .row:after
{
   display: table;
   content: " ";
}
#LayoutGrid3:after,
#LayoutGrid3 .row:after
{
   clear: both;
}
@media (max-width: 480px)
{
#LayoutGrid3 > .row > .col-1, #LayoutGrid3 > .row > .col-2
{
   float: none;
   width: 100% !important;
}
}
#Label2
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   box-sizing: border-box;
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
   font-size: 13px;
   box-sizing: border-box;
   line-height: 15px;
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
#wb_LayoutGrid4
{
   clear: both;
   position: relative;
   table-layout: fixed;
   display: table;
   text-align: center;
   width: 100%;
   background-color: transparent;
   background-image: none;
   border: 0px solid #000000;
   box-sizing: border-box;
   margin: 0;
}
#LayoutGrid4
{
   box-sizing: border-box;
   padding: 5px 15px 5px 15px;
   margin-right: auto;
   margin-left: auto;
}
#LayoutGrid4 > .row
{
   margin-right: -15px;
   margin-left: -15px;
}
#LayoutGrid4 > .row > .col-1, #LayoutGrid4 > .row > .col-2
{
   box-sizing: border-box;
   font-size: 0px;
   min-height: 1px;
   padding-right: 15px;
   padding-left: 15px;
   position: relative;
}
#LayoutGrid4 > .row > .col-1, #LayoutGrid4 > .row > .col-2
{
   float: left;
}
#LayoutGrid4 > .row > .col-1
{
   background-color: transparent;
   background-image: none;
   width: 25%;
   text-align: left;
}
#LayoutGrid4 > .row > .col-2
{
   background-color: transparent;
   background-image: none;
   width: 75%;
   text-align: left;
}
#LayoutGrid4:before,
#LayoutGrid4:after,
#LayoutGrid4 .row:before,
#LayoutGrid4 .row:after
{
   display: table;
   content: " ";
}
#LayoutGrid4:after,
#LayoutGrid4 .row:after
{
   clear: both;
}
@media (max-width: 480px)
{
#LayoutGrid4 > .row > .col-1, #LayoutGrid4 > .row > .col-2
{
   float: none;
   width: 100% !important;
}
}
#Label3
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   box-sizing: border-box;
   padding: 4px 4px 4px 4px;
   margin: 0;
   text-align: left;
   vertical-align: top;
}
#wb_LayoutGrid5
{
   clear: both;
   position: relative;
   table-layout: fixed;
   display: table;
   text-align: center;
   width: 100%;
   background-color: transparent;
   background-image: none;
   border: 0px solid #000000;
   box-sizing: border-box;
   margin: 0;
}
#LayoutGrid5
{
   box-sizing: border-box;
   padding: 5px 15px 5px 15px;
   margin-right: auto;
   margin-left: auto;
}
#LayoutGrid5 > .row
{
   margin-right: -15px;
   margin-left: -15px;
}
#LayoutGrid5 > .row > .col-1, #LayoutGrid5 > .row > .col-2
{
   box-sizing: border-box;
   font-size: 0px;
   min-height: 1px;
   padding-right: 15px;
   padding-left: 15px;
   position: relative;
}
#LayoutGrid5 > .row > .col-1, #LayoutGrid5 > .row > .col-2
{
   float: left;
}
#LayoutGrid5 > .row > .col-1
{
   background-color: transparent;
   background-image: none;
   width: 25%;
   text-align: left;
}
#LayoutGrid5 > .row > .col-2
{
   background-color: transparent;
   background-image: none;
   width: 75%;
   text-align: left;
}
#LayoutGrid5:before,
#LayoutGrid5:after,
#LayoutGrid5 .row:before,
#LayoutGrid5 .row:after
{
   display: table;
   content: " ";
}
#LayoutGrid5:after,
#LayoutGrid5 .row:after
{
   clear: both;
}
@media (max-width: 480px)
{
#LayoutGrid5 > .row > .col-1, #LayoutGrid5 > .row > .col-2
{
   float: none;
   width: 100% !important;
}
}
#Label4
{
   border: 0px solid #CCCCCC;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   box-sizing: border-box;
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
   color :#555555;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   box-sizing: border-box;
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
#wb_LayoutGrid6
{
   clear: both;
   position: relative;
   table-layout: fixed;
   display: table;
   text-align: center;
   width: 100%;
   background-color: transparent;
   background-image: none;
   border: 0px solid #000000;
   box-sizing: border-box;
   margin: 0;
}
#LayoutGrid6
{
   box-sizing: border-box;
   padding: 5px 15px 5px 15px;
   margin-right: auto;
   margin-left: auto;
}
#LayoutGrid6 > .row
{
   margin-right: -15px;
   margin-left: -15px;
}
#LayoutGrid6 > .row > .col-1, #LayoutGrid6 > .row > .col-2
{
   box-sizing: border-box;
   font-size: 0px;
   min-height: 1px;
   padding-right: 15px;
   padding-left: 15px;
   position: relative;
}
#LayoutGrid6 > .row > .col-1, #LayoutGrid6 > .row > .col-2
{
   float: left;
}
#LayoutGrid6 > .row > .col-1
{
   background-color: transparent;
   background-image: none;
   width: 25%;
   text-align: left;
}
#LayoutGrid6 > .row > .col-2
{
   background-color: transparent;
   background-image: none;
   width: 75%;
   text-align: left;
}
#LayoutGrid6:before,
#LayoutGrid6:after,
#LayoutGrid6 .row:before,
#LayoutGrid6 .row:after
{
   display: table;
   content: " ";
}
#LayoutGrid6:after,
#LayoutGrid6 .row:after
{
   clear: both;
}
@media (max-width: 480px)
{
#LayoutGrid6 > .row > .col-1, #LayoutGrid6 > .row > .col-2
{
   float: none;
   width: 100% !important;
}
}
#Button1
{
   border: 1px solid #2E6DA4;
   border-radius: 4px;
   background-color: #3370B7;
   background-image: none;
   color: #FFFFFF;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   -webkit-appearance: none;
   margin: 0;
}
</style>
   <meta charset="utf-8">
    <title>Contact Form</title>

    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>
<div id="wb_LayoutGrid1">
<form name="inquiries" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="LayoutGrid1">
<input type="hidden" name="formid" value="layoutgrid1">
<div class="row">
<div class="col-1">
<div id="wb_LayoutGrid2">
<div id="LayoutGrid2">
<div class="row">
<div class="col-1">
<label for="Editbox1" id="Label1" style="display:block;width:100%;line-height:13px;z-index:0;"><h3>Name</h3></label>
   <br>
<input type="text" id="Editbox1" style="display:block;width: 100%;height:25px;z-index:1;" placeholder="Enter name here" name="name" value="" spellcheck="false">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid3">
<div id="LayoutGrid3">
<div class="row">
<div class="col-1">
<label for="Editbox2" id="Label2" style="display:block;width:100%;line-height:13px;z-index:2;"><h3>Email</h3></label>
   <br>
<input type="text" id="Editbox2" style="display:block;width: 100%;height:25px;z-index:3;" name="email" placeholder="Enter your email here" value="" spellcheck="false">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid4">
<div id="LayoutGrid4">
<div class="row">
<div class="col-1">
<label for="" id="Label3" style="display:block;width:100%;line-height:13px;z-index:4;">Query ?</label>
</div>
<div class="col-2">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid5">
<div id="LayoutGrid5">
<div class="row">
<div class="col-1">
<label for="TextArea1" id="Label4" style="display:block;width:100%;line-height:13px;z-index:5;"><h3>Please tell us a little about how we can help</h3></label>
<br>
<textarea name="comments" id="TextArea1" style="display:block;width: 100%;;height:100px;z-index:6;" placeholder="Enter your message here" rows="1" cols="1" spellcheck="false"></textarea>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid6">
<div id="LayoutGrid6">
<div class="row">
<div class="col-1">
</div>
<div class="col-2">
<input type="submit" id="Button1" name="" value="Submit" style="display:inline-block;width:96px;height:25px;z-index:7;">
</div>
</div>
</div>
</div>
</div>
</div>
</form>
</div>
</body>
</html>
