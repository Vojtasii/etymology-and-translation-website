<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content='text/html; charset=UTF-8' />
<link rel="stylesheet" href="style.css">
<script src=jquery-3.1.1.js></script>
<script src=URI.js></script>
</head>
<body>
<script type=text/javascript>
function changeUrl (action, element) { //URI.js is required
    var uri = new URI(location.href);
    switch (action) {
        case "add": uri.addSearch(element); break;
        case "rem": uri.removeSearch(element); break;
        case "set":
            var value = document.getElementById("targetslct").value;
            uri.setSearch(element, value);
    }
    location.href = uri;
}
</script>
<?php
header('charset=UTF-8');
$linguis = array("Čeština" => "cs", "Français" => "fr", "Lietuvių" => "lt", "English" => "en", "Slovenčina" => "sk", "Slovenščina" => "sl");
$data1 = array("Slovo", "Etymologie", "Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Lorem", "Dolor");
$data2 = array("Věci", "Lidé", "Příroda", "Krajina", "Jídlo", "Domácnost", "Lorem", "Ipsum", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Dolor", "Sit", "Amet", "et", "Maior", "Deum", "Gloriam", "Amen");

class LangOptions {
    private $loclang;
    private $num = 0;
    private $name = 'lang0';
    private $output = array();
    
    public function init($data) {
        $this->loclang = $data;     
    }
    
    public function create() {
        $select = "<th>";
        $select .= $this->createLangSelect(isset($_GET[$this->name]), true);
        $select .= "</th>";
        $this->output[] = $select;
        $this->nextlang();
    
        while ($this->num <= count($this->loclang)) {
            $select = '';
            if ($this->num % 4 == 0) {
                $select .= "</tr>\n<tr class='main'>";
            }
            if (isset($_GET[$this->name])) {
                $select .= "<th>";
                $select .= $this->createLangSelect(true, false);
                $select .= "</th>";
                $this->output[] = $select;
                $this->nextlang();    
            }
            else {
                $select .= "<th>";
                switch ($this->num) {
                    case 1:
                        $select .= $this->addButton("button plus", "add", "+", "lang".$this->num);
                        break;
                    case count($this->loclang):
                        $select .= $this->addButton("button minus", "rem", "-", "lang".($this->num-1));
                        break;
                    default:
                        $select .= $this->addButton("button minus", "rem", "-", "lang".($this->num-1));
                        $select .= $this->addButton("button plus", "add", "+", "lang".$this->num);
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
    
    private function addButton($class, $id, $text, $name) {
        return "<button class='$class' type='button' id='$id' onclick='changeUrl(\"$id\", \"$name\")'>$text</button>";
    }
    
    private function createLangSelect($isset, $istarget) {
        $langorder = $this->loclang;
        $select = '';
        if ($istarget) {
            $select .= "<select class='target' name='$this->name' id='targetslct' onchange='changeUrl(\"set\",\"lang0\")'>";
        }
        else $select .= "<select name='$this->name'>";
        if ($isset) {
            $getlang = array_search($_GET[$this->name], $langorder);
            if($getlang != null) {
                $langorder = array($getlang => $_GET[$this->name]) + $langorder;
            }
        }
        foreach ($langorder as $l => $l_value) {
            $select .= "<option value='$l_value'>".$l."</option>";
        }
        $select .= "</select>";
        return $select;
    }
    
    private function nextlang() {
        $this->num++;
        $this->name = 'lang'.$this->num;
    }        
}

function createCheckboxField($root, $data) {
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
}
?>


<form method="get" id="srchform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
    <tr class="main">
        <?php
        global $linguis;
        $langs = new LangOptions();
        $langs->init($linguis);
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
    <tr class="main"><th colspan="4">Rozsah hledání</th></tr>
    <tr><?php createCheckboxField("rng", $data1); ?></tr>
    <tr class="main"><th colspan="4">Kategorie</th></tr>
    <tr><?php createCheckboxField("cat", $data2); ?></tr>
</table>
</form>

</body>
</html>
