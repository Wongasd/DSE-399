<?php
function loginUser($email, $password)
{
    // Example only (simulate database)
    if ($email === "testuser@gmail.com" && $password === "123456") {
        return true;
    }

    return false;
}

?>