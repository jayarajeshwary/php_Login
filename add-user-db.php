<?php
require_once "connection.php";
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email_id = $_POST['email_id'];
$pword = password_hash($_POST['pword'], PASSWORD_DEFAULT);
$emp_id = $_POST['emp_id'];
$ldap_id = $_POST['ldap_id'];
$default_site_id = $_POST['default_site_id'];
$site_ids = implode(",",$_POST['site_ids']);
$default_approle_id = $_POST['default_approle_id'];
$approle_ids = implode(",",$_POST['approle_ids']);
$isvisible = ($_POST['isvisible'] == "on") ? "1" : NULL;
$isactive = ($_POST['isactive'] == "on") ? "1" : NULL;

$sql = "SELECT user_key, ad_id, pword FROM app_users WHERE emp_id = :emp_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":emp_id", $param_emp_id, PDO::PARAM_STR);
$param_emp_id = $emp_id;
$stmt->execute();
if($stmt->rowCount() >= 1){
    //header("Location:add-user.php");
    exit;
}
$data = [
    'fname' => $fname,
    'lname' => $lname,
    'email_id' => $email_id,
    'pword' => $pword,
    'emp_id' => $emp_id,
    'ldap_id' => $ldap_id,
    'default_site_id' => $default_site_id,
    'site_ids' => $site_ids,
    'default_approle_id' => $default_approle_id,
    'approle_ids' => $approle_ids,
    'isvisible' => $isvisible,
    'isactive' => $isactive,
];
$sql = "INSERT INTO app_users (fname, lname, email_id, pword, emp_id, ldap_id, default_site_id, site_ids, default_approle_id, approle_ids, isvisible, isactive) 
VALUES (:fname, :lname, :email_id, :pword, :emp_id, :ldap_id, :default_site_id, :site_ids, :default_approle_id, :approle_ids, :isvisible, :isactive)";
$stmt= $pdo->prepare($sql);
$stmt->execute($data);


?>