<?php 
require_once __DIR__.'/../../models/UserModel.php'; // import UserModel

$user = new User(); // instantiate user instance
$user->logout(); // logout user with defined logout function of the User Model instance;
    
header("Location: /urls.php?pg=index"); // redirect to home page
exit;



