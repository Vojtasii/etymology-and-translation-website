<table>
    <tr class="main"><?php $langs->push(true); ?></tr>
    <?php
    for ($l = 1; $l < count($_GET['lang']); $l++) {
        echo "<tr class='main'>";
        $p = "";
        if ($POST_RESULTS === true && isset($_GET['keywords'])) $p = $_GET['keywords'];
        echo "<th colspan='4' rowspan='1'><textarea name='txtarearesults' rows='8' readonly>$p</textarea></th>";
        echo "</tr>";
    }
    ?>
</table>
