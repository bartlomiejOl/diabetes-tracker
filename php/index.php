<?php
  session_start();

  if((isset($_SESSION['isLogged']))&&($_SESSION['isLogged']==true)){

    header("Location: home.php");
    exit();
  }

  ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"
    integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous">
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/login.css">
  <script type="text/javascript" src="../js/script.js"></script>




  <title>Logowanie</title>
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
        <li class="items"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'patient_diary.php'?"active":"";?>"
            href="./patient_diary.php">Dziennik pacjenta</a></li>
        <li class="items"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'summary.php'?"active":"";?>"
            href="./summary.php">Podsumowanie</a></li>
        <li class="items"><a href="../php/index.php">Zaloguj się</a></li>
        <li class="btn"><a href="#"><i class="fas fa-bars"></i></a></li>
      </ul>
    </nav>
  </header>
  <main>
    <div class="login">
      <form class="login-form" action="./login.php" method="post">
        <h1 id="login_header">Zaloguj się</h1></br>
        <?php
            if(isset($_SESSION['error']))
            {
              echo $_SESSION['error'];
              unset($_SESSION['error']);
            }
          ?>
        <?php
            if(isset($_SESSION['statusReg']))
            {
              echo $_SESSION['statusReg'];
              unset($_SESSION['statusReg']);
            }
          ?>

        <input type="text" placeholder="Login" name="login">
        <input type="password" placeholder="Hasło" name="password">
        <input type="submit" value="Zaloguj się" name="submitbtn">
      </form>
      <p class="registerMess">Nie posiadasz jeszcze konta?<a href="registration.php">Zarejestruj się</a></p>
    </div>
  </main>
  <footer>
    <p>Copyright &copy; Bartłomiej Olek</p>
  </footer>
</body>

</html>