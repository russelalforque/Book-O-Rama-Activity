<?php

echo '<link rel="stylesheet" href="styles.css">';

function displayError($message, $href)
{
    echo '
    <link rel="stylesheet" href="styles.css">
    <div class="message-box error-box">
        <p>' . htmlspecialchars($message) . '</p>
        <a href="' . htmlspecialchars($href) . '" class="navbutton">Go Back</a>
    </div>
    ';
}

function displaySuccess($message, $href)
{
    echo '
    <div class="message-box success-box">
        <p>' . htmlspecialchars($message) . '</p>
        <a href="' . htmlspecialchars($href) . '" class="navbutton">Okay</a>
    </div>
    ';
}
?>