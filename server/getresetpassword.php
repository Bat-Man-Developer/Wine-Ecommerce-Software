<?php

include('connection.php');

if(isset($_POST['resetpasswordbtn'])){
  $userpassword = $_POST['flduserpassword'];
  $userconfirmpassword = $_POST['flduserconfirmpassword'];
  $useremail = $_POST['flduseremail'];

  //1. if password dont match
  if($userpassword !== $userconfirmpassword){
    header('location: ../account.php?error=passwords dont match');
  }
  else if(strlen($userpassword) < 8)
  {//2. if password is less than 8 characters
    header('location: ../account.php?error=password must be atleast 8 characters');
  }
  else{//3. no errors
    //3.1 check whether there is a user with this email or not
    $stmt = $conn->prepare("SELECT count(*) FROM users WHERE flduseremail=?");
    $stmt->bind_param('s',$useremail);
    $stmt->execute();
    $stmt->bind_result($num_rows);
    $stmt->store_result();
    $stmt->fetch();

    //3.1.1 if there is a user already registered with this email
    if($num_rows != 0){
      $stmt = $conn->prepare("UPDATE users SET flduserpassword=? WHERE flduseremail=?");

      $stmt->bind_param('ss',md5($userpassword),$useremail);

      if($stmt->execute()){
        header('location: ../login.php?message=Password Has Been Reset Succesfully. Login To Account!');
      }
    }
    else{//3.1.2 if no user registered with this email before
        header('location: ../resetpassword.php?error=Email not found!');
    }
  }
}

?>