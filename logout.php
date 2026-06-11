```php
<?php

session_start();

/* REMOVE ALL SESSION VARIABLES */

session_unset();

/* DESTROY SESSION */

session_destroy();

/* REMOVE SESSION COOKIE */

if (ini_get("session.use_cookies"))
{
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* REDIRECT */

header("Location:index.php");
exit();

?>
```
