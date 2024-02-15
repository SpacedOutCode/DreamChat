<?php
session_start();
if (isset($_SESSION['name'])) {
    $text = $_POST['text'];
    $code = $_POST['code'];
    if (str_starts_with($text, "https://")) {
        $text_message = "<div class='msgln'><b class='user-name'>" . $_SESSION['name'] . "</b> <span class='chat-time'>" . date("j/n/0 g:i A") . "</span><br>&nbsp;<a href='" . $text . "' target='_blank'>" . stripslashes(htmlspecialchars($text)) . "</a><br></div>";
        file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    } else if ($_POST['text']) {
        $text_message = "<div class='msgln'><b class='user-name'>" . $_SESSION['name'] . "</b> <span class='chat-time'>" . date("j/n/0 g:i A") . "</span><br>&nbsp;" . stripslashes(htmlspecialchars($text)) . "<br></div>";
        file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    } else if ($_POST['code']) {
        $code_message = "<div class='msgln'><b class='user-name'>" . $_SESSION['name'] . "</b> <span class='chat-time'>" . date("j/n/0 g:i A") . "</span><pre><code>" . $code . "</code></pre></div>";
        file_put_contents("log.html", $code_message, FILE_APPEND | LOCK_EX);
    }
}
?>