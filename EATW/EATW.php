<script src=EATW\queryChanger.js></script>
<script src=EATW\listener.js></script>

<?php
include('class.php\DBconn.class.php');
include('class.php\SelectOptions.class.php');
?>

<div class='gui'>
    <div class='form'>
    <?php require_once('form.php'); /* includes $POST_RESULTS and $_GET['lang[]', 'keywords', 'rng[]', 'cat[]'] */ ?>
    </div>

    <div class='translate'>
    <?php require_once('translate_output.php'); ?>
    </div>
</div>
<div>
    <?php if ($POST_RESULTS === true && isset($_GET['keywords'])) require_once('search_output.php'); ?>
</div>
