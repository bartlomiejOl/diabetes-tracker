<?php
  session_start();

  if(isset($_POST['email']))
  {
    $allGood = true;

    $login = $_POST['login'];

    if((strlen($login)<3) || (strlen($login)>20))
    {
      $allGood = false;
      $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków";
    }

    if(ctype_alnum($login)==false){
      $allGood = false;
      $_SESSION['e_login'] = "Login może składać się tylko z liter i cyfr";
    }

    $name = $_POST['name'];

    if((strlen($name)<3) || (strlen($name)>20))
    {
      $allGood = false;
      $_SESSION['e_name'] = "Imie musi posiadać od 3 do 20 znaków";
    }

    $surname = $_POST['surname'];

    if((strlen($surname)<2) || (strlen($surname)>27))
    {
      $allGood = false;
      $_SESSION['e_surname'] = "Nazwisko musi posiadać od 2 do 27 znaków";
    }

    $email = $_POST['email'];
    $emailS = filter_var($email, FILTER_SANITIZE_EMAIL);

    if((filter_var($emailS, FILTER_VALIDATE_EMAIL)==false) || ($emailS!=$email))
    {
      $allGood = false;
      $_SESSION['e_email'] = "Niepoprawny email";
    }

    $password = $_POST['password'];
    $repassword = $_POST['re_password'];

    if((strlen($password)<8) || (strlen($password)>20))
    {
      $allGood = false;
      $_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków";
    }

    if($password!=$repassword){
      $allGood = false;
      $_SESSION['e_repassword'] = "Podane hasła nie są identyczne";
    }

    $hash_password = password_hash($password, PASSWORD_DEFAULT);


    $_SESSION['form_login'] = $login;
    $_SESSION['form_password'] = $password;
    $_SESSION['form_repassword'] = $repassword;
    $_SESSION['form_email'] = $email;
    $_SESSION['form_name'] = $name;
    $_SESSION['form_surname'] = $surname;

    

    require_once "./connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port); 

      if($connect->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {

        $result = $connect->query("SELECT id_user FROM users WHERE email='$email'");

        if(!$result) throw new Exception($connect->error);

        $howManyEmails = $result->num_rows;

        if($howManyEmails>0)
        {
          $allGood = false;
          $_SESSION['e_email'] = "Istnieje już konto przypisane do podanego emaila!";
        }

        $result = $connect->query("SELECT id_user FROM users WHERE login='$login'");

        if(!$result) throw new Exception($connect->error);

        $howManyLogins = $result->num_rows;

        if($howManyLogins>0)
        {
          $allGood = false;
          $_SESSION['e_login'] = "Istnieje już konto o podanym loginie!";
        }

        if($allGood==true)
        {
          if($connect->query("INSERT INTO users VALUES(NULL,'$login','$hash_password','$email','$name','$surname')"))
          {
            $_SESSION['registerTrue'] = true;
            $_SESSION['statusReg'] = "<p style='color:greenyellow;'> Konto zostało utworzone pomyślnie! </p>";
            header('Location: index.php');
          }
          else
          {
            throw new Exception($connect->error);
          }
          exit();
        }

        
        $connect->close();
      }

    }
    catch(Exception $e)
    {
      echo "<span style='color:red;'>Błąd serwera!</span>";
    }

  }


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"
    integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous">
  </script>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/register.css">

  <title>Rejestracja</title>
</head>

<body>
  <header>
    <nav>
      <ul>
        <li class="logo"><a href="./home.php">Cukrzyca4Life</a></li>
        <li class="items"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'measurement.php'?"active":"";?>"
            href="./measurement.php">Dziennik pomiarów</a></li>
        <li class="items"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'meds.php'?"active":"";?>"
            href="./meds.php">Dziennik leków</a></li>
        <li class="items"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'foods.php'?"active":"";?>"
            href="./foods.php">Jadłospis</a></li>
        <li class="items"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'patien_diary.php'?"active":"";?>"
            href="./patient_diary.php">Dziennik pacjenta</a></li>
        <li class="items"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'summary.php'?"active":"";?>"
            href="./summary.php">Podsumowanie</a></li>
        <li class="items"><a href="../php/index.php">Zaloguj się</a></li>
        <li class="btn"><a href="#"><i class="fas fa-bars"></i></a></li>
      </ul>
    </nav>
  </header>
  <main>
    <div class="register">
      <form class="register-form" method="post">
        <h1 id="register_header">Zarejestruj się</h1>
        <input type="text" name="login" placeholder="Login" value="<?php
              if(isset($_SESSION['form_login']))
              {
                echo $_SESSION['form_login'];
                unset($_SESSION['form_login']);
              }
           ?>">
        <?php
            if(isset($_SESSION['e_login']))
            {
              echo '<div class="error">'.$_SESSION['e_login'].'</div>';
              unset($_SESSION['e_login']);
            }
          ?>

        <input type="password" name="password" placeholder="Hasło" value="<?php
              if(isset($_SESSION['form_password']))
              {
                echo $_SESSION['form_password'];
                unset($_SESSION['form_password']);
              }
           ?>">
        <?php
            if(isset($_SESSION['e_password']))
            {
              echo '<div class="error">'.$_SESSION['e_password'].'</div>';
              unset($_SESSION['e_password']);
            }
          ?>
        <input type="password" name="re_password" placeholder="Powtórz hasło" value="<?php
              if(isset($_SESSION['form_repassword']))
              {
                echo $_SESSION['form_repassword'];
                unset($_SESSION['form_repassword']);
              }
           ?>">
        <?php
            if(isset($_SESSION['e_repassword']))
            {
              echo '<div class="error">'.$_SESSION['e_repassword'].'</div>';
              unset($_SESSION['e_repassword']);
            }
          ?>
        <input type="text" name="email" placeholder="Email" value="<?php
              if(isset($_SESSION['form_email']))
              {
                echo $_SESSION['form_email'];
                unset($_SESSION['form_email']);
              }
           ?>">
        <?php
            if(isset($_SESSION['e_email']))
            {
              echo '<div class="error">'.$_SESSION['e_email'].'</div>';
              unset($_SESSION['e_email']);
            }
          ?>
        <input type="text" name="name" placeholder="Imię" value="<?php
              if(isset($_SESSION['form_name']))
              {
                echo $_SESSION['form_name'];
                unset($_SESSION['form_name']);
              }
           ?>">
        <?php
            if(isset($_SESSION['e_name']))
            {
              echo '<div class="error">'.$_SESSION['e_name'].'</div>';
              unset($_SESSION['e_name']);
            }
          ?>
        <input type="text" name="surname" placeholder="Nazwisko" value="<?php
              if(isset($_SESSION['form_surname']))
              {
                echo $_SESSION['form_surname'];
                unset($_SESSION['form_surname']);
              }
           ?>">
        <?php
            if(isset($_SESSION['e_surname']))
            {
              echo '<div class="error">'.$_SESSION['e_surname'].'</div>';
              unset($_SESSION['e_surname']);
            }
          ?>
        <input type="submit" value="Zarejestruj">
      </form>
    </div>
  </main>
  <footer>
    <p>Copyright &copy; Bartłomiej Olek</p>
  </footer>
</body>

</html>