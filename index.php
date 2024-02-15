<?php
 
session_start();
 
if(isset($_GET['logout'])){    
     
    //Simple exit message
    $logout_message = "<div class='msgln'><b class='user-name-left'>". $_SESSION['name'] ." Disconnected</b><br></div>";
    $activeFile = file_get_contents("active.json");
    $activeArray = json_decode($activeFile, true);
    $activeTemp = json_decode($activeFile, true);
    $accId = array_search($_SESSION['name'], $activeArray);
    unset($activeTemp[$accId]);
    setcookie('accid', '');
    $newActiveData = json_encode($activeTemp);
    file_put_contents('active.json', $newActiveData);
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
    
    session_destroy();
    header("Location: index.php"); //Redirect the user
}

if(isset($_POST['enter'])){
    $idFile = file_get_contents("accids.json");
    $userFile = file_get_contents("usernames.json");
    $Ids = json_decode($idFile, true);
    $users = json_decode($userFile, true);
    $idTemp = json_decode($idFile, true);
    $userTemp = json_decode($userFile, true);
    $activeFile = file_get_contents("active.json");
    $activeArray = json_decode($activeFile, true);

    if($_POST['name'] != "" && $_POST['accId'] != "" && !in_array($_POST['name'], $users) && !in_array((int)$_POST['accId'], $Ids)){
        
        setcookie('accid', (int)$_POST['accId']);
        array_push($userTemp, $_POST['name']);
        array_push($idTemp, (int)$_POST['accId']);
        $newIdData = json_encode($idTemp);
        $newUserData = json_encode($userTemp);
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
        $login_message = "<div class='msgln'><b class='user-name-joined'>". $_SESSION['name'] ." Connected</b><br></div>";
        file_put_contents('accids.json', $newIdData);
        file_put_contents('usernames.json', $newUserData);

        if (!in_array($_SESSION['name'], $activeArray)) {
            $activeTemp = json_decode($activeFile, true);
            array_push($activeTemp, $_SESSION['name']);
            $newActiveData = json_encode($activeTemp);
            file_put_contents('active.json', $newActiveData);
            file_put_contents("log.html", $login_message, FILE_APPEND | LOCK_EX);
        }
    } else if ($_POST['name'] == "" &&  $_POST['accId'] != "" && in_array((int)$_POST['accId'], $Ids)){
        $id = array_search($_POST['accId'], $Ids);
        setcookie('accid', (int)$_POST['accId']);
        $_SESSION['name'] = $users[$id];
        $login_message = "<div class='msgln'><b class='user-name-joined'>". $_SESSION['name'] ." Connected</b><br></div>";
        
        if (!in_array($users[array_search($_POST['accId'], $Ids)], $activeArray)) {
            $activeTemp = json_decode($activeFile, true);
            array_push($activeTemp, $users[array_search($_POST['accId'], $Ids)]);
            $newActiveData = json_encode($activeTemp);
            file_put_contents('active.json', $newActiveData);
            file_put_contents("log.html", $login_message, FILE_APPEND | LOCK_EX);
        }
    }
    else{
        echo '<span class="error">Error Please enter a name or use your ID only</span>';
    }
}
function loginForm(){
    echo
    '<div id="loginform">
    <form action="index.php" method="post" style="gap: 0.5rem; align-items: center; display: flex; flex-direction: column; padding-top: 20vh;">
      <input type="text" name="name" id="name" class="name" maxlength="13" placeholder="Username"/>
      <input type="number" name="accId" id="accId" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" placeholder="Account Id" />
      <input type="submit" name="enter" id="enter" value="Login" />
    </form>
  </div>';
}
 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DreamChat</title>
    <link rel="stylesheet" href="style.css" />
    <link type="image/ico" rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400&family=Josefin+Sans:wght@300;400&family=Jura:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/native-emoji/dist/nativeEmoji.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/native-emoji/dist/nativeEmoji.min.css" rel="stylesheet">
</head>

<body>
<?php
    if(!isset($_SESSION['name'])){
        loginForm();
    }
    else {

    }
?>
<div class="body" id="body">
    <div class="main-box">
        <div class="nav-bar">
            <h1 class="Title">DreamChat</h1>
            <h2 class="uName" id="username">Logged in as <?php echo $_SESSION['name'] ?></h2>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" fill="none" class="exit-button" id="exit">
                <path
                    d="M30 33.5L17.75 45.75C17.2917 46.2083 16.7083 46.4375 16 46.4375C15.2917 46.4375 14.7083 46.2083 14.25 45.75C13.7917 45.2917 13.5625 44.7083 13.5625 44C13.5625 43.2917 13.7917 42.7083 14.25 42.25L26.5 30L14.25 17.75C13.7917 17.2917 13.5625 16.7083 13.5625 16C13.5625 15.2917 13.7917 14.7083 14.25 14.25C14.7083 13.7917 15.2917 13.5625 16 13.5625C16.7083 13.5625 17.2917 13.7917 17.75 14.25L30 26.5L42.25 14.25C42.7083 13.7917 43.2917 13.5625 44 13.5625C44.7083 13.5625 45.2917 13.7917 45.75 14.25C46.2083 14.7083 46.4375 15.2917 46.4375 16C46.4375 16.7083 46.2083 17.2917 45.75 17.75L33.5 30L45.75 42.25C46.2083 42.7083 46.4375 43.2917 46.4375 44C46.4375 44.7083 46.2083 45.2917 45.75 45.75C45.2917 46.2083 44.7083 46.4375 44 46.4375C43.2917 46.4375 42.7083 46.2083 42.25 45.75L30 33.5Z"/>
            </svg>
        </div>
        <div class="primary">
            <div class="primary-child chat-box" >
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
            </div>
            <div class="primary-child user-box">
                <h2>Active</h2>
                <p class="active-users" id="active-users"></p>
            </div>
        </div>
        <div class="message-box">
                <input name="usermsg" type="text" maxlength="1000" id="messageBox" placeholder="Message DreamChat">
                <button name="submitmsg" type="submit" id="submitmsg" class="sendBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="2.5vw" height="2.5vw" viewBox="-2 0 24 24" class="sendIcon">
                    <path  d="M5.133 18.02q-.406.163-.77-.066Q4 17.726 4 17.288v-3.942L9.846 12L4 10.654V6.712q0-.438.363-.666q.364-.229.77-.067l12.513 5.27q.49.224.49.755q0 .53-.49.748L5.133 18.02Z"/>
                </svg>
                </button>
            <div class="message-options">
                <button class="msgOptIcon" onclick="toggleTheme()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5vw" height="1.5vw" viewBox="0 0 24 24">
                        <path d="M12 22q-2.05 0-3.875-.788t-3.187-2.15q-1.363-1.362-2.15-3.187T2 12q0-2.075.813-3.9t2.2-3.175Q6.4 3.575 8.25 2.788T12.2 2q2 0 3.775.688t3.113 1.9q1.337 1.212 2.125 2.875T22 11.05q0 2.875-1.75 4.413T16 17h-1.85q-.225 0-.312.125t-.088.275q0 .3.375.863t.375 1.287q0 1.25-.687 1.85T12 22Zm-5.5-9q.65 0 1.075-.425T8 11.5q0-.65-.425-1.075T6.5 10q-.65 0-1.075.425T5 11.5q0 .65.425 1.075T6.5 13Zm3-4q.65 0 1.075-.425T11 7.5q0-.65-.425-1.075T9.5 6q-.65 0-1.075.425T8 7.5q0 .65.425 1.075T9.5 9Zm5 0q.65 0 1.075-.425T16 7.5q0-.65-.425-1.075T14.5 6q-.65 0-1.075.425T13 7.5q0 .65.425 1.075T14.5 9Zm3 4q.65 0 1.075-.425T19 11.5q0-.65-.425-1.075T17.5 10q-.65 0-1.075.425T16 11.5q0 .65.425 1.075T17.5 13Z"/>
                    </svg>
                </button>
                <button class="msgOptIcon" onclick="srcCode()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5vw" height="1.5vw" viewBox="0 0 45 45" fill="none">
                        <path d="M16.5469 22.5L19.3125 19.7344C19.6875 19.3594 19.875 18.9219 19.875 18.4219C19.875 17.9219 19.6875 17.4844 19.3125 17.1094C18.9375 16.7344 18.4925 16.5469 17.9775 16.5469C17.4625 16.5469 17.0169 16.7344 16.6406 17.1094L12.5625 21.1875C12.375 21.375 12.2419 21.5781 12.1631 21.7969C12.0844 22.0156 12.0456 22.25 12.0469 22.5C12.0469 22.75 12.0856 22.9844 12.1631 23.2031C12.2406 23.4219 12.3737 23.625 12.5625 23.8125L16.6406 27.8906C17.0156 28.2656 17.4613 28.4531 17.9775 28.4531C18.4938 28.4531 18.9388 28.2656 19.3125 27.8906C19.6875 27.5156 19.875 27.0781 19.875 26.5781C19.875 26.0781 19.6875 25.6406 19.3125 25.2656L16.5469 22.5ZM28.4531 22.5L25.6875 25.2656C25.3125 25.6406 25.125 26.0781 25.125 26.5781C25.125 27.0781 25.3125 27.5156 25.6875 27.8906C26.0625 28.2656 26.5081 28.4531 27.0244 28.4531C27.5406 28.4531 27.9856 28.2656 28.3594 27.8906L32.4375 23.8125C32.625 23.625 32.7581 23.4219 32.8369 23.2031C32.9156 22.9844 32.9544 22.75 32.9531 22.5C32.9531 22.25 32.9144 22.0156 32.8369 21.7969C32.7594 21.5781 32.6262 21.375 32.4375 21.1875L28.3594 17.1094C28.1719 16.9219 27.9613 16.7812 27.7275 16.6875C27.4937 16.5937 27.2587 16.5469 27.0225 16.5469C26.7887 16.5469 26.5544 16.5937 26.3194 16.6875C26.0844 16.7812 25.8737 16.9219 25.6875 17.1094C25.3125 17.4844 25.125 17.9219 25.125 18.4219C25.125 18.9219 25.3125 19.3594 25.6875 19.7344L28.4531 22.5ZM9.375 39.375C8.34375 39.375 7.46125 39.0081 6.7275 38.2744C5.99375 37.5406 5.62625 36.6575 5.625 35.625V9.375C5.625 8.34375 5.9925 7.46125 6.7275 6.7275C7.4625 5.99375 8.345 5.62625 9.375 5.625H35.625C36.6562 5.625 37.5394 5.9925 38.2744 6.7275C39.0094 7.4625 39.3763 8.345 39.375 9.375V35.625C39.375 36.6562 39.0081 37.5394 38.2744 38.2744C37.5406 39.0094 36.6575 39.3763 35.625 39.375H9.375ZM9.375 35.625H35.625V9.375H9.375V35.625Z" fill="#CCCCCC" fill-opacity="0.45"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="Themes" id="Themes">
            <button onclick="changeTheme(1)">Red</button>
            <button onclick="changeTheme(2)">Orange</button>
            <button onclick="changeTheme(3)">Green</button>
            <button onclick="changeTheme(4)">Blue</button>
            <button onclick="changeTheme(5)">Gray</button>
            <button onclick="changeTheme(0)">Default</button>
        </div>
    </div>
</div>
<div class="codeInsert" id="codeInsert">
    <h2>Input Code Block</h2>
    <textarea class="codeInput" id="codeInput"></textarea>
    <button type="submit" value="Submit" class="codeSubmit">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <g fill="none" >
                <path d="M24 0v24H0V0h24ZM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01l-.184-.092Z"/>
                <path fill="#2c9951" d="M21.546 5.111a1.5 1.5 0 0 1 0 2.121L10.303 18.475a1.6 1.6 0 0 1-2.263 0L2.454 12.89a1.5 1.5 0 1 1 2.121-2.121l4.596 4.596L19.424 5.111a1.5 1.5 0 0 1 2.122 0Z"/>
            </g>
        </svg>
    </button>
    <button type="submit" value="Submit" class="codeCancel">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 12 12">
            <path fill="#992c2c" d="M2.22 2.22a.749.749 0 0 1 1.06 0L6 4.939L8.72 2.22a.749.749 0 1 1 1.06 1.06L7.061 6L9.78 8.72a.749.749 0 1 1-1.06 1.06L6 7.061L3.28 9.78a.749.749 0 1 1-1.06-1.06L4.939 6L2.22 3.28a.749.749 0 0 1 0-1.06Z"/>
    </svg>
    </button>
</div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#messageBox").val();
                    $.post("post.php", { text: clientmsg });
                    $("#messageBox").val("");
                    return false;
                    });
            });
            $(document).ready(function () {
                $(".codeSubmit").click(function () {
                    var clientmsg = $("#codeInput").val();
                    var codeDiv = document.getElementById('codeInsert');
                    $.post("post.php", { code: clientmsg });
                    $("#codeInput").val("");
                    codeDiv.style.display = '';
                    return false;
                    });
            });
            $(document).ready(function () {
                $(".codeCancel").click(function () {
                    var codeDiv = document.getElementById('codeInsert');
                    codeDiv.style.display = '';
                    return false;
                    });
            });
                var previousHtml = 0;
                function getPrevious() {
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            previousHtml = html;
                        }
                    });
                }
                getPrevious();
                function loadLog() {
                    var oldscrollHeight = $(".chat-box").scrollHeight - 20; //Scroll height before the request
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            var currentHtml = html
                            if (currentHtml !== previousHtml ) {
                                $(".chat-box").html(html);  
                                var newscrollHeight = $(".chat-box").scrollHeight - 20; //Scroll height after the request
                                if(newscrollHeight > oldscrollHeight){
                                    $(".chat-box").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                                }
                                getPrevious();
                            }
                        }
                    });
                    $.getJSON('active.json', function(json) {
                            var currentJson = json
                            if (currentJson !== previousJson ) {
                                var activeNames = $('#active-users');
                                activeNames.html('');
                                json.forEach((user) => {
                                    activeNames.html(activeNames.html() + user + "<br>");
                                });
                                activeUsers();
                            }
                    });
                }
                var previousJson = 0;
                function activeUsers() {
                    $.ajax({
                        url: "active.json",
                        cache: false,
                        success: function (json) {
                            previousJson = json;
                        }
                    });
                }
                activeUsers();
                setInterval(loadLog, 500);
                $("#exit").click(function () {
                    window.location = "index.php?logout=true";
                });
        </script>
        <script src="handler.js"></script>
</body>

</html>