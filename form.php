<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content='text/html; charset=UTF-8' />
<link rel="stylesheet" href="style.css">
</head>
<body>
<script type=text/javascript>
function changeUrl (action, element) {
    switch (action) {
        case "add": addParameter("lang" + element); break;
        case "rem": element--; remParameter("lang" + element); break;
    }
}

function addParameter(element) {
    var loc = location.href;
    loc += loc.indexOf("?") === -1 ? "?" : "&";
    location.href = loc + element + "=";
}
function remParameter(element) {           //credit to bobince at StackOverflow
    var url = location.href;
    var urlparts= url.split('?');
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(element)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        for (var i= pars.length; i-- > 0;) {
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                pars.splice(i, 1);
            }
        }

        url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
        location.href = url;
    } else {
        location.href = url;
    }
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
        $select = "<th class='target'>";  
        $select .= $this->createLangSelect(isset($_GET[$this->name]));
        $select .= "</th>";
        $this->output[] = $select;
        $this->nextlang();
    
        while ($this->num <= count($this->loclang)) {
            $select = '';
            if ($this->num % 4 == 0) {
                $select .= "</tr>\n<tr class='main'>";
            }
            if (isset($_GET[$this->name])) {
                $select .= $this->addLangSelect();
                $this->output[] = $select;
                $this->nextlang();    
            }
            else {
                $select .= "<th>";
                switch ($this->num) {
                    case 1:
                        $select .= $this->addButton("button plus", "add", "+", $this->num);
                        break;
                    case count($this->loclang):
                        $select .= $this->addButton("button minus", "rem", "-", $this->num);
                        break;
                    default:
                        $select .= $this->addButton("button minus", "rem", "-", $this->num);
                        $select .= $this->addButton("button plus", "add", "+", $this->num);
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
    
    private function addButton($class, $id, $text, $num) {
        return "<button class='$class' type='button' id='$id' onclick='changeUrl(\"$id\", $num)'>$text</button>";
    }
    
    private function addLangSelect() {
        $select = '';
        $select .= "<th>";
        $select .= $this->createLangSelect(true);
        $select .= "</th>";
        return $select;
    }   
    
    private function createLangSelect($isset) {
        $select = '';
        $select .= "<select name='$this->name'>";
        if ($isset) {
            $getlang = array_search($_GET[$this->name], $this->loclang);
            if($getlang != null) {
                $this->loclang = array($getlang => $_GET[$this->name]) + $this->loclang;
            }
        }
        foreach ($this->loclang as $l => $l_value) {
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

function checkGET($attr) {
    if(isset($_GET[$attr])) {return 'checked';}
    else return '';    
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
    <tr class="main"></tr>
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
