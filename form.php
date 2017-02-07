<!DOCTYPE html>
<html>

<head>
<META HTTP-EQUIV="Content-type" content='text/html; charset=UTF-8' />
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
header('charset=UTF-8');
$linguis = array("Čeština" => "cs", "Français" => "fr", "Lietuvių" => "lt");
$data1 = array("Slovo", "Etymologie", "Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Lorem", "Dolor");
$data2 = array("Věci", "Lidé", "Příroda", "Krajina", "Jídlo", "Domácnost", "Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Amen");

function checkGET($attr) {
    if(isset($_GET[$attr])) {return 'checked';}
    else return '';    
}

function createLangList($num) {
    global $linguis;
    $name = 'lang'.$num;
    print "<select name='$name'>";
    if(isset($_GET[$name])) {    
        print "<option value='$_GET[$name]'>".array_search($_GET[$name], $linguis)."</option>";
    }
    foreach ($linguis as $l => $l_value) {
        print "<option value='$l_value'>".$l."</option>";
    }
    print "</select>";
}

function createCheckboxField($root, $data) {
    print "<td><input type='checkbox' onclick=\"for(c in document.getElementsByClassName('$root')) document.getElementsByClassName('$root').item(c).checked = this.checked\">Vše</td>";
    for ($i = 0; $i != count($data); $i++) {
        $ch = $root.$i;
        if(isset($_GET[$ch])) {$ch = 'checked';}
        else $ch = '';
        print "<td><input class='$root' type='checkbox' name='$root".$i."' $ch>".$data[$i]."</td>";
        if (($i + 2) % 4 == 0) {
            print "\r\n</tr>\n\t<tr>\r\n";
        }
    }                
}
?>


<form method="get" id="srchform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
    <tr class="main">
        <th class='target'><?php createLangList(0); ?></th>
        <th><?php createLangList(1); ?></th>
    </tr>
    <tr class="main">
        <?php
        $p = ""; 
        if(isset($_GET['keywords'])) {$p = $_GET['keywords'];}
        echo "<th colspan='4' rowspan='1'><textarea name='keywords' cols='108' rows='8' form='srchform'>$p</textarea></th>";
        ?>
    </tr>
    <tr class="main"><th colspan="4"><input class="submit" type="submit"></th></tr>
    <tr class="main"><th colspan="4">Rozsah hledání</th></tr>
    <tr><?php createCheckboxField("rng", $data1); ?></tr>
    <tr class="main"><th colspan="4">Kategorie</th></tr>
    <tr><?php createCheckboxField("cat", $data2); ?></tr>
</table>
</form>

</body>
</html>
