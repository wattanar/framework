<?php 

// Delete this file after installed!

$user = 'admin';
$pass = 'admin';

$db_name = "test";
$db_user = "test";
$db_pass = "test";
$db_host = "test";

$settings = [
  "Database" => $db_name, 
  "UID" => $db_user, 
  "PWD" => $db_pass ,
  "CharacterSet" => "UTF-8",
  "ReturnDatesAsStrings" => true,
  "MultipleActiveResultSets" => true,
  "ConnectionPooling" => true
];

$conn = sqlsrv_connect($db_host, $settings);

if (!$conn) {
  echo "Unable to connect database.";
  exit;
} 

$pass_salt = password_hash($pass, PASSWORD_DEFAULT);

$checkUser = sqlsrv_has_rows(sqlsrv_query(
  $conn,
  "SELECT user_login 
  FROM web_users
  WHERE user_login = ?",
  [
    $user
  ]
));

if ($checkUser === true) {
  echo "This " . $user . " has already exists.";
  exit;
}

$create_user = sqlsrv_query(
  $conn,
  "INSERT INTO web_users(
    user_login,
    user_pass,
    user_registered,
    user_status
  ) VALUES(?, ?, ?, ?)",
  [
    strtolower($user),
    $pass_salt,
    date('Y-m-d H:i:s'),
    1
  ]
);

if ($create_user) {
  echo "Create user successful. <br>";
  exit;
} else {
  echo "Create user failed.";
  exit;
}
