<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content='text/html; charset=UTF-8' />
<link rel="stylesheet" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src=URI.js\src\URI.min.js></script>
</head>
<body>
<script type=text/javascript>
function changeUrl (action, element, value) { //URI.js is required
    var uri = new URI(location.href);
    switch (action) {
        case "add":
            //checks query for an element with the same name and value
            //if it exists it calls itself with the value + "1" (contatenation)
            //otherwise adds the parameter
            if (uri.hasQuery(element, value, true)) {changeUrl("add", element, value + 1); return;}
            uri.addQuery(element, value);
            break;
        case "rem":
            //loads the query as an object, selects just one element
            //checks for emptiness (removes immediately if empty)
            //checks if the value is an array (if it is, removes just the last value of the array)
            //removes the parameter
            var query = uri.query(true);
            query = query[element];
            if (query == null) {uri.removeQuery(element); break;}
            if (Array.isArray(value)) query = query[query.length-1];
            uri.removeQuery(element, query);
            break;
        case "set":
            //loads the query as an object, selects just one element
            //checks for emptiness (sets the element without further questions if empty)
            //checks if the value is an array (if it is, changes just the desired value in query)
            ////checks query for an element with the same name and value
            ////if it exists, it exits the function
            //sets the parameter
            var query = uri.query(true);
            query = query[element];
            if (query == null) {uri.setQuery(element, document.getElementsByName(element).value); break;}
            if (typeof query == "object") {
                query[value] = document.getElementsByName(element)[value].value;
                if (uri.hasQuery(element, query[value], true)) {return;}
            }
            else query = document.getElementsByName(element).value;
            uri.setQuery(element, query);
            break;
        case "target":
            //special case for the target language select, much simpler
            var value = document.getElementById("targetslct").value;
            uri.setQuery(element, value);
    }
    location.href = uri;
}
</script>
<?php
header('charset=UTF-8');

$linguis = array("Čeština" => "cs", "Français" => "fr", "Lietuvių" => "lt", "English" => "en", "Slovenčina" => "sk", "Slovenščina" => "sl");
$cs = array(array("Slovo", "Etymologie"), array("Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior"));
$fr = array(array("Mot", "Etymologie"), array("Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Deum"));
$lt = array(array("Žodis", "Etymologija"), array("Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam"));
$en = array(array("Word", "Etymology"), array("Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor"));
$sk = array(array("Slovo", "Etymológia"), array("Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit"));
$sl = array(array("Beseda", "Etymologija"), array("et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet"));
$tempdata = array("cs" => $cs,"fr" => $fr,"lt" => $lt,"en" => $en,"sk" => $sk,"sl" => $sl);
if(isset($_GET['langT']) && array_key_exists($_GET['langT'], $tempdata)) {
    $langdata = $tempdata[$_GET['langT']];
    $data1 = $langdata[0];
    $data2 = $langdata[1];
}
else {
    $data1 = false;
    $data2 = false;
}

class SelectOptions {
    private $locdata;
    private $num = 0;
    private $name;
    private $output = array();
    private $selectedoptions = array();
    
    public function init($data, $name) {
        $this->locdata = $data;
        $this->name = $name;
        if (isset($_GET[$name."T"])) {$this->selectedoptions[0] = $_GET[$name."T"];}
        else $this->selectedoptions[0] = null;
        if (isset($_GET[$name])) {
            if (is_array($_GET[$name])) {
                foreach($_GET[$name] as $g) $this->selectedoptions[] = $g;
            }
            else $this->selectedoptions[] = $_GET[$name];
        }
    }
    
    public function create() {
        $select = "<th>";
        $select .= $this->createOptionSelect($this->name."T", true);
        $select .= "</th>";
        $this->output[] = $select;
        $this->num++;
    
        while ($this->num <= count($this->locdata)) {
            $select = '';
            if ($this->num % 4 == 0) {
                $select .= "</tr>\n<tr class='main'>";
            }
            if ($this->num < count($this->selectedoptions)) {
                $select .= "<th>";
                $select .= $this->createOptionSelect($this->name, false);
                $select .= "</th>";
                $this->output[] = $select;
                $this->num++;
            }
            else {
                $select .= "<th>";
                switch ($this->num) {
                    case 1:
                        $select .= $this->addButton("button plus", "add", "+", "lang[]", $this->num);
                        break;
                    case count($this->locdata):
                        $select .= $this->addButton("button minus", "rem", "-", "lang[]", $this->num-1);
                        break;
                    default:
                        $select .= $this->addButton("button minus", "rem", "-", "lang[]", $this->num-1);
                        $select .= $this->addButton("button plus", "add", "+", "lang[]", $this->num);
                }
                $select .= "</th>";
                $this->output[] = $select;
                break;
            } //add: a button
        }
    }
    
    public function push() {
        foreach ($this->output as $o) {
            print $o;
        }        
    }
    
    private function addButton($class, $id, $text, $name, $value = null) {
        return "<button class='$class' type='button' id='$id' onclick='changeUrl(\"$id\", \"$name\", \"$value\")'>$text</button>";
    }
    
    private function createOptionSelect($name, $istarget) {
        $isset = isset($_GET[$name]);
        $optionorder = $this->locdata;
        $select = '';
        if ($istarget) {
            $select .= "<select class='target' name='$name' id='targetslct' onchange='changeUrl(\"target\",\"$name\")'>";
        }
        else $select .= "<select name='$name"."[]"."' onchange='changeUrl(\"set\",\"$name"."[]"."\", $this->num-1)'>";

        if ($isset) {
            $getlang = array_search($this->selectedoptions[$this->num], $optionorder);
            if($getlang != null) {
                $optionorder = array($getlang => $this->selectedoptions[$this->num]) + $optionorder;
            }
            else $select .= "<option disabled selected value display:none></option>";
        }
        else $select .= "<option disabled selected value display:none></option>";

        foreach ($optionorder as $l => $l_value) {
            $select .= "<option value='$l_value'>".$l."</option>";
        }
        $select .= "</select>";
        return $select;
    }
    
    private function nextlang() {
        $this->num++;
        //$this->name = 'lang'.$this->num;
    }        
}

function createCheckboxField($name, $root, $data) {
    if (empty($data)) return;
    print "<tr class='main'><th colspan='4'>$name</th></tr><tr>";
    print "<td><input type='checkbox' onclick=\"for(c in document.getElementsByClassName('$root')) document.getElementsByClassName('$root')[c].checked = this.checked\">Vše</td>";
    for ($i = 0; $i != count($data); $i++) {
        $ch = $root.$i;
        if(isset($_GET[$ch])) {$ch = 'checked';}
        else $ch = '';
        print "<td><input class='$root' type='checkbox' name='$root".$i."' $ch>".$data[$i]."</td>";
        if (($i + 2) % 4 == 0) {
            print "</tr>\n<tr>";
        }
    }
    print '</tr>';
}
?>


<form method="get" id="srchform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
    <tr class="main">
        <?php
        global $linguis;
        $langs = new SelectOptions();
        $langs->init($linguis, "lang");
        $langs->create();
        $langs->push(); 
        ?>
    </tr>
    <tr class="main">
        <?php
        $p = ""; 
        if(isset($_GET['keywords'])) {$p = $_GET['keywords'];}
        echo "<th colspan='4' rowspan='1'><textarea name='keywords' cols='108' rows='8' form='srchform'>$p</textarea></th>";
        ?>
    </tr>
    <tr class="main"><th colspan="4"><input class="submit" type="submit"></th></tr>
        <?php
        createCheckboxField("Rozsah hledání", "rng", $data1);
        createCheckboxField("Kategorie", "cat", $data2);
        ?>
</table>
</form>

</body>
</html>
