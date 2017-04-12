<script src=EATW\queryChanger.js></script>
<script src=EATW\listener.js></script>

<?php
include('class.php\DBconn.class.php');
include('class.php\SelectOptions.class.php');
?>

<div class='gui'>
    <div class='form'>
    <?php require_once('form.php'); /* asks for $POST_RESULTS and $_GET['lang[]', 'keywords', 'rng[]', 'cat[]'] */ ?>
    </div>

    <div class='translate'>
    <?php require_once('translate_output.php'); ?>
    </div>
</div>
<div>
    <?php if ($POST_RESULTS) require_once('search_output.php'); ?>
</div>



<?php
/******** FUNCTIONS ********/

function getDictionariesList($args, $conds = null) {
    global $conn;
    $sql = "SELECT $args FROM dictionaries_list";
    if ($conds !== null) $sql .= " WHERE $conds";
    $result = $conn->queryAll($sql);
    return $result;
}

function explodeKeywords($keywords) {
    $values = array();
    $value = "";
    $flag = false;
    for ($i = 0; $i < strlen($keywords); $i++) {
        if ($keywords[$i] === "*") {$value .= "%";}
        else if ($keywords[$i] === "'") {$value .= "\'";}
        else if (preg_match("/[\s\t\n\r]/", $keywords[$i]) && !$flag && !empty($value) && trim($value) !== "") {
            $values[] = trim($value);
            $value = "";
        }
        else if (preg_match("/[\"«»„“]/", $keywords[$i])) {
            if (!$flag) { $flag = true; continue;}
            $flag = false;
            if (!empty($value)) $values[] = trim($value);
            $value = "";
        }
        else $value .= $keywords[$i];

    }
    if (!empty($value) && trim($value) !== "") { $values[] = trim($value); }
    return $values;
}
?>
