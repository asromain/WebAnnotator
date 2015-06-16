<?php require_once 'include/meta.php'; ?>
<?php require_once 'include/header.php'; ?>

    <h1>Description URI :</h1>

    <?php
    if (!isset($attributes)) {
        echo "Pas de triplet";
    }
    else {
    ?>
    <table>
        <tr>
            <th>Subject</th>
            <th>Predicate</th>
            <th>Object</th>
        </tr>

        <?php
        foreach ($attributes as $value) {
        ?>
        <tr>
            <td style="padding:10px;"><a href="<?php echo $subject; ?>"><?php echo $subject; ?></a></td>
            <td style="padding:10px;"><a href="<?php echo $value['predicate']; ?>"><?php echo $value['predicate']; ?></a></td>
            <td style="padding:10px;">
                <?php
                $tmpStr = $value['object'];
                // detection rapide d'url pour mettre une balise a clicable
                if(preg_match('/https?:\/\//i', $tmpStr) ) {
                    echo '<a href="'.$tmpStr.'">'.$tmpStr.'</a>';
                }
                else {
                    echo $tmpStr;
                }
                ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
    <?php
    } // fin else
    ?>

<?php require_once 'include/footer.php'; ?>
