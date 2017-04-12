<table>
    <tr class="main"><?php $langs->push(true); ?></tr>
    <?php
    $table = $title = "";
    $values = array();
    if ($POST_RESULTS === true && isset($_GET['keywords'])) {
        $values = explodeKeywords($_GET['keywords']);
        $result = getDictionariesList("`table`, title, translate", "code='" . $_GET['lang'][0] . "'");
        $table = $result[0]['table'];
        $title = $result[0]['title'];
        $translate = $result[0]['translate'];
    }
    if (isset($_GET['lang'][1])) {
    for ($l = 1; $l < count($_GET['lang']); $l++) {
            echo "<tr class='main'>";
            $p = "";
            if ($POST_RESULTS) {
                $code = $_GET['lang'][$l];
                $result = getDictionariesList("`table`, translate", "code='$code'");
                $srtable = $result[0]['table'];
                $srtranslate = $result[0]['translate'];
                //$conds = implode("','", $values);
                foreach ($values as $v) {
                    $sql = "SELECT $srtranslate FROM $table JOIN $srtable ON $table.id_$code = $srtable.id_$code
                            WHERE $translate = '$v'";
                    $result = $conn->queryOne($sql);
                    if ($result) $p .= "$result[$srtranslate] ";
                    else $p .= "$v ";
                }
            }
            echo "<th colspan='4' rowspan='1'><textarea name='txtarearesults' rows='8' readonly>$p</textarea></th>";
            echo "</tr>";
        }
    }
    ?>
</table>
