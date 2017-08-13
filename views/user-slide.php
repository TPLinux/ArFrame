<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title><?= $this->dlang->siteName ?></title>
    </head>
    <body style="font-family:tahoma">
	<b style="color:green">App URI:</b> /users/(username)/slide/(slide_id)
	<br/>
	<b style="color:green">User URL:</b> /<?php echo $_GET['route'] ?>
	<br/>
	<b style="color:green">Info & Parameters:</b> <?php print_r($this->data) ?>
	<center>
	    <br/>
	    <br/>
	    <b>you can find the URI and edit it in /index.php</b>
	    <h3>
		<a href="<?php echo URL ?>">Home</a>
	    </h3>
	</center>
    </body>
</html>
