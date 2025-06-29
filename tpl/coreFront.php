<?php
$name = $_GET['id'];

header("Content-Type: image/png");

die(file_get_contents(GPATH.strtolower($name).".png"));
