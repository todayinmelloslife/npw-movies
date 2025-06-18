<?php

  require_once("../View/header.php");

  if($userDao) {
    $userDao->destroyToken();
  }
?>