<?php
    session_start();

    // blank msg prevent
    if (!empty($_SESSION['name']) && !empty($_POST['text'])) {
        $text = $_POST['text'];
        $fp = fopen("chat_logs/log_".date("d_m_Y").".log", 'a');
        fwrite($fp, "<div class='msgln'><span>(".date("H:i").") <b><user>".$_SESSION['name']."</user></b>: ".stripslashes (htmlspecialchars($text))."<br></span></div>");
        fclose($fp);
    } 
?>
