<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$config = [

    "db_host" => "localhost",
    "db_name" => "blog_p5",
    "db_user" => "root",
    "db_pwd" => "",
    "env" => "dev", // dev / prod
    "admin_email" => "contact@sitez-vous.com"

];
