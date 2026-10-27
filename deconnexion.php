<?php

session_start();
//Je détruis la session pour procéder à la déconnexion
session_destroy();

//Je redirige vers la page index
header("Location:index.php");

exit;
?>