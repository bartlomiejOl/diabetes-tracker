<?php

  session_start();

  if((!isset($_POST['login'])) || (!isset($_POST['password'])))
  {
    header("Location: index.php");
    exit();
  }

  require_once "./connect.php";

  $connect = @new mysqli($host,$db_user,$db_password,$db_name,$db_port); 

  if($connect->connect_errno!=0){
    echo "Error".$connect->connect_errno;
  }
  else{

    $login = $_POST['login'];
    $password = $_POST['password'];

    $login = htmlentities($login,ENT_QUOTES,"UTF-8");
    
    if($result = @$connect->query(sprintf("SELECT * FROM users WHERE login='%s'",
        mysqli_real_escape_string($connect,$login)))){

      $howManyUsers = $result->num_rows;
      if($howManyUsers>0){

        $row = $result->fetch_assoc();

        if(password_verify($password,$row['password']))
        {
          $_SESSION['isLogged'] = true;
          $_SESSION['id'] =  $row['id_user'];
          $_SESSION['name'] =  $row['name'];
          $_SESSION['surname'] =  $row['surname'];

          
          unset($_SESSION['error']);
          $result->free_result();
          header("Location: home.php");
        }
        else{
          $_SESSION['error'] = "<p style='color:red;'> Nieprawidłowy login lub hasło! </p>";
        header("Location: index.php");
        }
      }
      else{
        $_SESSION['error'] = "<p style='color:red;'> Nieprawidłowy login lub hasło! </p>";
        header("Location: index.php");
      }
    }
    $connect->close();
  }
?>