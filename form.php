<!DOCTYPE html>
<html>

<head>
<META HTTP-EQUIV="Content-type" content='text/html; charset=UTF-8' />
<style>
table {
    font-family: sans-serif;
    border-collapse: collapse;
    text-align: center;
    table-layout: fixed;
    width: 800px;
}

th, td {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr.main {
    background-color: #dddddd;
}

select {
    min-width: 170px;
    min-height: 45px;
}

th.target {
    border-width: 3px;
    border-color: rgb(150,150,150);
}
</style>
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

function createLangList ($num) {
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
?>


<form method="get" id="srchform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
    <tr class="main">
        <th class='target'>
        <?php createLangList(0); ?>
        </th>
        <th>
        <?php createLangList(1); ?>      
        </th>
    </tr>
    <tr class="main">
        <?php
        $p = "Zde napište hledaný termín"; 
        if(isset($_GET['keywords'])) {$p = $_GET['keywords'];}
        echo "<th colspan='4' rowspan='3'><textarea name='keywords' cols='100' rows='8' form='srchform'>$p</textarea></th>";
        ?>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr class="main">
        <th colspan="4">Rozsah hledání</th>
    </tr>
    <tr>
        <td><input type="checkbox" onclick="for(c in document.getElementsByClassName('rng')) document.getElementsByClassName('rng').item(c).checked = this.checked">Vše</td>
        <?php
        for ($i = 2; $i != count($data1) + 2; $i++) {
            $ch = checkGET('rng'.($i-2));
            print "<td><input class='rng' type='checkbox' name='rng".($i-2)."' $ch>".$data1[$i-2]."</td>";
            if ($i % 4 == 0) {
                print "\r\n</tr>\n\t<tr>\r\n";
            }
        }            
        ?>
    </tr>
    <tr class="main">
        <th colspan="4">Kategorie</th>
    </tr>
    <tr>
        <td><input type="checkbox" onclick="for(c in document.getElementsByClassName('cat')) document.getElementsByClassName('cat').item(c).checked = this.checked">Vše</td>
        <?php
        for ($i = 2; $i != count($data2) + 2; $i++) {
            $ch = checkGET('cat'.($i-2));
            print "<td><input class='cat'type='checkbox' name='cat".($i-2)."' $ch>".$data2[$i-2]."</td>";
            if ($i % 4 == 0) {
                print "\r\n</tr>\r<tr>\r\n";
            }
        }            
        ?>
    </tr>
</table>
<input type="submit">
</form>

</body>
</html>