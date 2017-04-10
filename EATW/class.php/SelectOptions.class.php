<?php
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
?>
