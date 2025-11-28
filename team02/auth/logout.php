<?php
/**
 * Backend: 김현영
 */
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
