<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content='text/html; charset=UTF-8' />
<link rel="stylesheet" href="style.css">

<!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
<script src=URI.js\jquery-1.10.2.min.js></script>
<script src=URI.js\src\URI.min.js></script>
<script src=queryChanger.js></script>
<script src=listener.js></script>
</head>
<body>

<div class='gui'>
    <div class='form'>
    <?php require_once('form.php'); /* includes $POST_RESULTS and $_GET['lang[]', 'keywords', 'rng[]', 'cat[]'] */ ?>
    </div>

    <div class='results'>
    <?php require_once('database_output.php'); ?>
    </div>
</div>

</body>
</html>
