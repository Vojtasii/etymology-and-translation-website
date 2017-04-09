﻿<?php
header('charset=UTF-8');
//GET DATA FROM MYSQL SERVER
$dbini = parse_ini_file('.htcredentials.ini');
   define("dbhost", $dbini["dbhost"]);
   define("dbuser", $dbini["dbuser"]);
   define("dbpass", $dbini["dbpass"]);
   define("dbname", $dbini["dbname"]);
$conn = new DBconn(dbhost, dbuser, dbpass, dbname);

function getDictionariesTitleAndCode() {
    global $conn;
    $sql = "SELECT title, code FROM dictionaries_list";
    $result = Array();
    $rows = $conn->queryAll($sql);
    for ($i = 0; $i < $conn->queryCount($sql); $i++) {
        $result += [$rows[$i]['title'] => $rows[$i]['code']];
    }
    return $result;
}
$linguis = getDictionariesTitleAndCode();

//form handler
$POST_RESULTS = false;
$langErr = $keywordsErr = $rngErr = $catErr = "";
$lang = $keywords = "";
if (isset($_GET['sent'])) {
    //TODO
    if (empty($_GET["lang"])) {
        $langErr = "Vyberte aspoň jeden jazyk";
    } else {
        for ($i = 0; $i < count($_GET["lang"]); $i++) {
            $lang = test_input($_GET["lang"][$i]);
            if (!array_search($_GET['lang'][0], $linguis)) {
                $langErr = "Neplatný vstup";
                break;
            }
        }
    }

    if (empty($_GET["keywords"])) {
        $keywordsErr = "";
    } else {
        $keywords = test_input($_GET["keywords"]);
        // check if input has only language characters, \pL is a Unicode category
        if (!preg_match("/^[\s,.'&quot;:«»„“\-\pL]+$/u", $keywords)) {
          $keywordsErr = "Pouze významové znaky jsou povolené";
        }
    }

    if (empty($_GET["rng"])) {
        $rngErr = "Musíte vybrat rozsah hledání";
    }

    if ($langErr . $keywordsErr . $rngErr . $catErr === "") {
        $POST_RESULTS = true;
    }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


//set data (temporary)
$cs = array("Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior");
$fr = array("Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Deum");
$lt = array("Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam");
$en = array("Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor");
$sk = array("Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit");
$sl = array("et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet");
$tempdata = array("cs" => $cs,"fr" => $fr,"lt" => $lt,"en" => $en,"sk" => $sk,"sl" => $sl);


if(isset($_GET['lang']) && array_search($_GET['lang'][0], $linguis)) {
    $data2 = $tempdata[$_GET['lang'][0]];
    $sql = "SELECT `table` FROM dictionaries_list WHERE code='" . $_GET['lang'][0] . "'";
    $result = $conn->queryOne($sql);
    $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='dictionaries' AND `TABLE_NAME`='$result[table]'";
    $data1 = $conn->queryAll($sql);
    $result = Array();
    for ($i = 1; $i < $conn->queryCount($sql); $i++) {
        $result[] = $data1[$i]['COLUMN_NAME'];
    }
    $data1 = $result;
}
else {
    $data1 = false;
    $data2 = false;
}

/*foreach ($linguis as $l => $l_code) {
    $langdata = $tempdata[$l_code];
    $data1 = $langdata[0];
    $data2 = $langdata[1];

$sql = "CREATE TABLE IF NOT EXISTS $l( ".
       "id_" . $l_code . " INT NOT NULL AUTO_INCREMENT, ".
       "$data1[0] VARCHAR(45), ".
       "$data1[1] MEDIUMTEXT, ".
       "PRIMARY KEY ( id_" . $l_code . " )); ";
echo $sql;
$result = mysqli_query($conn, $sql);
if(! $result )
   {
     die(mysqli_error($conn));
   }

}
mysqli_close($conn); */

class SelectOptions {
    private $locdata;
    private $num = 0;
    private $name;
    private $output = array();
    private $selectedoptions = array();
    
    public function __construct($data, $name) {
        $this->locdata = $data;
        $this->name = $name;
        if (isset($_GET[$name])) {
            if (is_array($_GET[$name])) {
                foreach($_GET[$name] as $g) $this->selectedoptions[] = $g;
            }
            else $this->selectedoptions[] = $_GET[$name];
        }
    }
    
    public function create() {
        $select = "<th>";
        $select .= $this->createOptionSelect($this->name, true);
        $select .= "</th>";
        $this->output[] = $select;
        $this->num++;
    
        while ($this->num <= count($this->locdata)) {
            $select = '';
            if ($this->num % 4 === 0) {
                $select .= "</tr><tr class='main'>";
                $this->output[] = $select;
            }
            if ($this->num < count($this->selectedoptions)) {
                $select = "<th>";
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
            }
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
            $select .= "<select class='target' name='$name"."[]"."' id='targetslct' onchange=\"setParameter('$name"."[]"."', '$this->num')\" required>";
        }
        else $select .= "<select name='$name"."[]"."' onchange=\"setParameter('$name"."[]"."','$this->num')\">";

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

    public function push($disabled = false) {
        if ($disabled) {
            echo "<th><select class='hidden'></select></th>";//echo "<th><select disabled" . substr($this->output[0], 11);
            for ($i = 1; $i < count($this->output) - 1; $i++) {
                if ($i % 4 === 0) { echo $this->output[$i]; continue; }
                echo "<th><select disabled" . substr($this->output[$i], 11);
            }
            echo "<th><button class='button hidden'>+</button></th>";
        }
        else foreach ($this->output as $o) {
            echo $o;
        }
    }
}

function createCheckboxField($title, $root, $data) {
    if (empty($data)) return;
    echo "<tr class='main'><th colspan='4'>$title</th></tr><tr>";
    echo "<td class='checkboxtd'><label for='$root'><input type='checkbox' id='$root' onclick=\"$('.$root').prop('checked', this.checked)\">Vše</label></td>";
    for ($i = 0; $i != count($data); $i++) {
        $pow=$i;//$pow = pow(2, $i);
        if (isset($_GET[$root]) && array_search($pow, $_GET[$root]) !== false) {$ch = 'checked';}
        else $ch = '';
        echo "<td class='checkboxtd'><label for='$root".$i."'><input class='$root' type='checkbox' id='$root".$i."' name='$root"."[]"."' value='$pow' $ch>$data[$i]</label></td>";
        if (($i + 2) % 4 === 0) {
            echo "</tr>\n<tr>";
        }
    }
    echo '</tr>';
}
?>

<form method="get" id="srchform" action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
    <tr class="main">
        <?php
        global $linguis;
        $langs = new SelectOptions($linguis, "lang");
        $langs->create();
        $langs->push();
        echo "</tr><tr><th class='error' colspan='4'>$langErr</th>";
        ?>
    </tr>
    <tr class="main">
        <?php
        $p = ""; 
        if(isset($_GET['keywords'])) $p = $_GET['keywords'];
        echo "<th colspan='4' rowspan='1'><textarea name='keywords' rows='8' form='srchform' onkeyup='saveValue(\"keywords\", this.value)'>$p</textarea></th>";
        echo "</tr><tr><th class='error' colspan='4'>$keywordsErr</th>";
        ?>
    </tr>
    <tr class="main"><th colspan="4">
        <input class="reset" type="reset" onclick='resetUrl()'>
        <input class="submit" type="submit" name="sent" value="Vyhledat"></th>
    </tr>
        <?php
        createCheckboxField("Rozsah hledání", "rng", $data1);
        echo "</tr><tr><th class='error' colspan='4'>$rngErr</th>";
        createCheckboxField("Kategorie", "cat", $data2);
        ?>
    </tr>
</table>
</form>
