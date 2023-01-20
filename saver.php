<?php
    session_start();

    // check if file used in chat main
    if (isset($_POST)) {
             
        // blank msg prevent
        if (!empty($_SESSION['name']) && !empty($_POST['text'])) {
            $text = $_POST['text'];
            $fp = fopen("chat_logs/log_".date("d_m_Y").".log", 'a');

            // check if admin user
            if ($_SESSION['name'] == "lordbecvold") {
                $user = "<userRed>".$_SESSION['name']."</userRed>";
            } else {
                $user = "<user>".$_SESSION['name']."</user>";
            }

            fwrite($fp, "<div class='msgln'><span>(".date("H:i").") <b>".$user."</b>: ".stripslashes (htmlspecialchars($text))."<br></span></div>");
            fclose($fp);
        } 
    }
?>
