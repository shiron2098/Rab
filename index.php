<?php

$date = date('l',strtotime('now'));
print_R($date);


?>
<form action="Job.php" method="post">
<input type="text" name="data">
    <button name="button" type="submit">Start</button>
</form>
