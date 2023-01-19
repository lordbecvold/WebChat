<?php
    // start php session
    session_start();

    // check if logs dir found
    if (!file_exists("chat_logs/")) {
        @mkdir("chat_logs/"); 
    }

    // login form
    function loginForm() {
        echo '
            <div id="loginform">
                <form action="/" method="post">
                    <p>Please enter your name to continue:</p>
	                <br><br>
                    <label for="name">Name:</label>
                    <input style="border: 1px solid #474747;" type="text" autofocus="" name="name" id="name" />
                    <input type="submit" name="enter" id="enter" value="Enter" />
                </form>
            </div>
        ';
    }

    // login
    if (isset($_POST['enter'])) {
	    if (strlen($_POST['name']) > 15) {
		    echo "<span class='error'>Please enter a name under 15 characters</span>";
	    } elseif (strlen($_POST['name']) < 1) {
		    echo "<span class='error'>Please enter a name</span>";
	    } elseif (ctype_space($_POST['name'])) {
		    echo "<span class='error' id='error'>Please enter a name</span>";
	    } else {
            $_SESSION["name"] = stripslashes(htmlspecialchars($_POST["name"]));
            $fp = fopen("chat_logs/log_".date("d_m_Y").".log", "a");
            fclose($fp);
        }
    }

    // logout
    if (isset($_GET["logout"])) {
        $fp = fopen("chat_logs/".date("d_m_Y").".log", "a");
        fclose($fp);
        session_destroy();
        header("Location: /"); 
    }
?>
<!DOCTYPE html>
<html>
<head>
    <link id="css" rel="stylesheet" type="text/css" href="assets/css/main.css">
    <title>Chat</title>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
</head>
<body>
<?php

    // check if user logged in
    if (!isset($_SESSION['name'])) {
        loginForm();
    } else {
?>
<div id="wrapper">
    
    <div id="menu">
        <p class="welcome">User, <b><?php echo $_SESSION['name']; ?></b></p>
        <p class="logout"><a id="exit" href="#">Exit</a></p>
        <div style="clear: both"></div>
    </div>
        
    <div id="chatbox">
    <?php

        // check if msg log found
        if (file_exists("chat_logs/log_".date("d_m_Y").".log") && filesize("chat_logs/log_".date("d_m_Y").".log") > 0) {
            
            // open log
            $handle = fopen("chat_logs/log_".date("d_m_Y").".log", "r");
            
            // get log content
            $contents = fread($handle, filesize("chat_logs/log_".date("d_m_Y").".log"));
            
            // close log file
            fclose($handle);
           
            // echo chat content
            echo $contents;
        }
    ?>
    </div>

    <?php // msg input form
        echo '
            <form name="message" action="">
                <input class="input" name="usermsg" autofocus="" spellcheck="true" type="text" id="usermsg" size="63"/> <input class="submit" name="submitmsg" type="submit" id="submitmsg" value="Send"/>
            </form>';
    ?>

</div>

<script type="text/javascript">

    // scrool
    $(document).ready(function() {
        var scrollHeight = $("#chatbox").attr("scrollHeight") - 50;
        var scroll = true;
    
        if (scroll == true) {
            $("#chatbox").animate(
                {scrollTop: scrollHeight}, 
                "normal"
            );
            load = false;
        }
    });
 
    // logout
    $(document).ready(function() {
            $("#exit").click(function() {
            var exit = true;
            if(exit==true) {
                window.location = '/?logout=true';
            }
        });
    });

    // msg submit
    $("#submitmsg").click(function() {
        var clientmsg = $("#usermsg").val();
        $.post("saver.php", {text: clientmsg});
        $("#usermsg").attr("value", "");
        loadLog;
        return false;
    });

    function loadLog() { 
    
        // scrool height
        var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 50; 

        // get date values
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yy = today.getFullYear();

        // date builder
        today = dd + '_' + mm + '_' + yy;

        // send msg to log file
        $.ajax({
            url: "chat_logs/log_" + today + ".log",
            cache: false,
            success: function(html){
                $("#chatbox").html(html); 
           
                // auto-scroll
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 50;
            
                if(newscrollHeight > oldscrollHeight) {

                    // scrool to bottom of div
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, "normal"); 
                }
            },
        });
    }
 
    // chat content refrash interval
    setInterval(loadLog, 100);
</script>
<?php
    } // end of logged part
?>
</body>
</html>
