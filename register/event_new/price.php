<?php
    include('server.php');

    if (isset($_GET['event'])) {
        $event_code =$_GET['event'];
        
        $q = "SELECT * FROM events WHERE event_code='$event_code'";
        $r = mysqli_query($db, $q);
        $value = mysqli_fetch_assoc($r);
        if ($user['college_name'] === "Baroda Medical College")
            echo $value['price_bmc'];
        else
            echo $value['price_oth'];
    }
?>