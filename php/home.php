<?php
  session_start();

  if(!isset($_SESSION['isLogged']))
  {
    header('Location: index.php');
    exit();
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
  <script type="text/javascript" src="../js/script.js"></script>
  <title>Aplikacja - cukrzyca</title>
</head>

<body>
  <header>
    <nav>
      <ul>
        <li class="logo"><a class="<?php echo basename($_SERVER['PHP_SELF']) == 'home.php'?"active":"";?>"
            href="./home.php">Diabetes Tracker</a>
        </li>
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
        <?php
            if((isset($_SESSION['isLogged']))&&($_SESSION['isLogged']==true)){
              echo "<li class='items'><a href='logout.php'> Wyloguj się </a></li>";
            }
            else{
              echo "<li class='items'><a href='../php/index.php'>Zaloguj się</a></li>";
            }
            ?>

        <li class="btn"><a href="#"><i class="fas fa-bars"></i></a></li>
      </ul>
    </nav>
  </header>
  <main>
    <div class="content">
      <?php
        echo "<h1 class='main_header'> Aplikacja dla osób chorych na cukrzycę </br></br> Witaj ".$_SESSION['name']." ".$_SESSION['surname']."</h1>";
        ?>
    </div>
  </main>
  <footer>
    <p>Copyright &copy; Bartłomiej Olek</p>
  </footer>
</body>

</html>