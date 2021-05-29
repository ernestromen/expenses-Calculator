<?php



if(!function_exists('csrf')){

function csrf(){
  $token = sha1(rand(1, 10000) . '$$' . rand(1, 1000) . 'icar');
  $_SESSION['csrf_token'] = $token;
  return $token;


}

}