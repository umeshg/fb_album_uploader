<?php

require 'fb_sdk/facebook.php';
require 'fb_sdk/keys.php';


// Get User ID
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  	$signed_request = $facebook->getSignedRequest();
	$page_admin  = $signed_request["page"]["admin"];
	if ( $page_admin == 1 ){ 
		echo 'Welcome Admin!';
	}else {
		echo 'Welcome Not Admin!'; 
	}
} 
else {
  	session_start();
   	$code = $_REQUEST["code"];

   	if(empty($code)) {
     	$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
     	$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
       	. $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       	. $_SESSION['state'];

     	echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }
}
?>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Page Album Uploader</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1>Page Album Uploader</h1>
    <?php if ( $page_admin == 1 ):  ?>
		Section for Admin to manage albums
    <?php else: ?>
		<form action="upload_to_fb.php" method="post"
		enctype="multipart/form-data">
		<label for="file">Filename:</label>
		<input type="file" name="file" id="file" />
		<br />
		<input type="submit" name="submit" value="Submit" />
		</form>
   <?php endif ?>

  </body>
</html>
