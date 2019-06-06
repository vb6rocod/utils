<?php
$list = glob("*.php");
   foreach ($list as $l) {
    echo '<a href="'.$l.'" target="_blank">'.$l.'</a><BR>';
}
?>
