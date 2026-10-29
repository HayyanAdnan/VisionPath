<?php
require_once 'config/auth.php';
if(!isLoggedIn()){ header('Location: login.php'); exit; }
include 'dashboard.html';
