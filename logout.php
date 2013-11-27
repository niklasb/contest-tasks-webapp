<?php

require_once("includes/preamble.php");
unset($_SESSION["name"]);
add_session_message("You have been logged out.");
redirect("");
