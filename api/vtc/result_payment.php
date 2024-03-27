<?php
if(isset($_POST)) file_put_contents('logs.txt',json_encode($_POST));
die();