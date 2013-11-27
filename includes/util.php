<?php

function h($str) {
  echo htmlspecialchars($str);
}
function get_url() {
  $url = 'http';
  if ($_SERVER["HTTPS"] == "on") 
    $url .= "s";
  $url .= "://";
  if ($_SERVER["SERVER_PORT"] != "80") 
    $url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
  else 
    $url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
  return $url;
}
function redirect($rel_url) {
  $url = preg_replace("@/[^/]*$@", $rel_url, get_url());
  header("Location: $url");
  die();
}
function validate_http_url($url) {
  if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) 
    return false;
  if (!preg_match("@^https?://@", $url))
    return false;
  return true;
}
