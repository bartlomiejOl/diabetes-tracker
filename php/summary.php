<?php
  session_start();

  if(!isset($_SESSION['isLogged']))
  {
    header('Location: index.php');
    exit();
  }

  $currentDate = "'".date('Y-m-d')."'";

  if(isset($_POST['date_to'])){
    $date_to = "'".$_POST['date_to']. "'";
    $date_from = "'".$_POST['date_from']. "'";
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
  <title>Podsumowanie</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/summary.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
    integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css"
    integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"
    integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
  </script>
  <script src="../js/script.js"></script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Pomiar', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT count(value) as below  FROM measurement WHERE value<70 AND whenTake='after' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
                      while($row=mysqli_fetch_assoc($result)){
                        echo "['Poniżej', ".$row["below"]."],";  
                    }
                  }else{
                    $result = $connect->query("SELECT count(value) as below  FROM measurement WHERE value<70 AND whenTake='after' AND id_user='$id' AND `date`= $currentDate ORDER BY date");
                      while($row=mysqli_fetch_assoc($result)){
                        echo "['Poniżej', ".$row["below"]."],";  
                    }
                  }

                  if(isset($_POST['date_from'])){
                    $result = $connect->query("SELECT count(value) as good FROM measurement WHERE (value>=70 AND value<=99) AND whenTake='after' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['W nornmie', ".$row["good"]."],";  
                    }
                  }else{
                    $result = $connect->query("SELECT count(value) as good FROM measurement WHERE (value>=70 AND value<=99) AND whenTake='after' AND id_user='$id' AND `date`= $currentDate ORDER BY date");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['W nornmie', ".$row["good"]."],";  
                    }
                  }

                  if(isset($_POST['date_from'])){
                    $result = $connect->query("SELECT count(value) as above FROM measurement WHERE value>140 AND whenTake='after' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['Powyżej', ".$row["above"]."],";  
                    }
                  }else{
                     $result = $connect->query("SELECT count(value) as above FROM measurement WHERE value>140 AND whenTake='after' AND id_user='$id' AND `date`= $currentDate ORDER BY date ");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['Powyżej', ".$row["above"]."],";  
                    }
                  }
                  ?>
    ]);

    var options = {
      title: 'Wykres pomiarów cukru po jedzeniu',
      is3D: true,
      slices: {
        0: {
          color: 'orange'
        },
        1: {
          color: '#00FF08'
        },
        2: {
          color: 'red'
        }
      },
      pieSliceText: 'none',
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechartAfter'));

    chart.draw(data, options);
  }
  </script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Pomiar', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];


                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT count(value)as below  FROM measurement WHERE value<70  AND whenTake='before' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
                      while($row=mysqli_fetch_assoc($result)){
                        echo "['Poniżej', ".$row["below"]."],";  
                    }
                  }else{
                    $result = $connect->query("SELECT count(value)as below  FROM measurement WHERE value<70  AND whenTake='before' AND id_user='$id' AND `date`= $currentDate ORDER BY date");
                      while($row=mysqli_fetch_assoc($result)){
                        echo "['Poniżej', ".$row["below"]."],";  
                    }
                  }

                  if(isset($_POST['date_from'])){
                    $result = $connect->query("SELECT count(value) as good FROM measurement WHERE (value>=70 AND value<=99) AND whenTake='before' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['W nornmie', ".$row["good"]."],";  
                    }
                  } else{
                    $result = $connect->query("SELECT count(value) as good FROM measurement WHERE (value>=70 AND value<=99) AND whenTake='before' AND id_user='$id' AND `date`= $currentDate ORDER BY date ");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['W nornmie', ".$row["good"]."],";  
                    }
                  }

                  if(isset($_POST['date_from'])){
                    $result = $connect->query("SELECT count(value) as above FROM measurement WHERE value>99 AND whenTake='before' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['Powyżej', ".$row["above"]."],";  
                    }
                  }else{
                    $result = $connect->query("SELECT count(value) as above FROM measurement WHERE value>99 AND whenTake='before' AND id_user='$id' AND `date`= $currentDate ORDER BY date ");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['Powyżej', ".$row["above"]."],";  
                    }
                  }
                  ?>
    ]);

    var options = {
      title: 'Wykres pomiarów cukru przed jedzeniem',
      is3D: true,
      slices: {
        0: {
          color: 'orange'
        },
        1: {
          color: '#00FF08'
        },
        2: {
          color: 'red'
        }
      },
      pieSliceText: 'none',
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechartBefore'));

    chart.draw(data, options);
  }
  </script>


  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Nazwa', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT description, count(*) as NUM FROM diary WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to GROUP BY description");
                    }
                    else{
                      $result = $connect->query("SELECT description, count(*) as NUM FROM diary WHERE id_user='$id' GROUP BY description");
                    }
                    while($row=mysqli_fetch_assoc($result)){
                      echo"['".$row['description']."',".$row['NUM']."],"; 
                      }
                  ?>
    ]);

    var options = {
      title: 'Wykres podsumowania dzienniku',
      is3D: true,
      pieSliceText: 'none',
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechartDiary'));

    chart.draw(data, options);
  }
  </script>


  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Nazwa', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT brekfast, count(*) as NUM FROM food WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to GROUP BY brekfast");
                    } else{
                      $result = $connect->query("SELECT brekfast, count(*) as NUM FROM food WHERE id_user='$id' AND `date`= $currentDate GROUP BY brekfast");
                    }
                      while($row=mysqli_fetch_assoc($result)){
                      echo"['".$row['brekfast']."',".$row['NUM']."],"; 
                      }
                  ?>
    ]);

    var data_sec = google.visualization.arrayToDataTable([
      ['Nazwa', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT sec_brekfast, count(*) as NUM FROM food WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to GROUP BY sec_brekfast");
                    } else{
                      $result = $connect->query("SELECT sec_brekfast, count(*) as NUM FROM food WHERE id_user='$id' AND `date`= $currentDate GROUP BY sec_brekfast");
                    }
                    while($row=mysqli_fetch_assoc($result)){
                      echo"['".$row['sec_brekfast']."',".$row['NUM']."],"; 
                      }
                  ?>
    ]);

    var data_lunch = google.visualization.arrayToDataTable([
      ['Nazwa', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT lunch, count(*) as NUM FROM food WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to GROUP BY lunch");
                    } else{
                      $result = $connect->query("SELECT lunch, count(*) as NUM FROM food WHERE id_user='$id' AND `date`= $currentDate GROUP BY lunch");
                    }
                    while($row=mysqli_fetch_assoc($result)){
                      echo"['".$row['lunch']."',".$row['NUM']."],"; 
                      }
                  ?>
    ]);

    var data_evening = google.visualization.arrayToDataTable([
      ['Nazwa', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT evening, count(*) as NUM FROM food WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to GROUP BY evening");
                    } else{
                      $result = $connect->query("SELECT evening, count(*) as NUM FROM food WHERE id_user='$id' AND `date`= $currentDate GROUP BY evening");
                    }
                    while($row=mysqli_fetch_assoc($result)){
                      echo"['".$row['evening']."',".$row['NUM']."],"; 
                      }
                  ?>
    ]);

    var data_dinner = google.visualization.arrayToDataTable([
      ['Nazwa', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT dinner, count(*) as NUM FROM food WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to GROUP BY dinner");
                    } else{
                      $result = $connect->query("SELECT dinner, count(*) as NUM FROM food WHERE id_user='$id' AND `date`= $currentDate GROUP BY dinner");
                    }
                    while($row=mysqli_fetch_assoc($result)){
                      echo"['".$row['dinner']."',".$row['NUM']."],"; 
                      }
                  ?>
    ]);

    var options = {
      title: 'Wykres śniadań',
      is3D: true,
      pieSliceText: 'none',
    };
    var options_sec = {
      title: 'Wykres drugich śniadań',
      is3D: true,
      pieSliceText: 'none',
    };
    var options_lunch = {
      title: 'Wykres obiadów',
      is3D: true,
      pieSliceText: 'none',
    };
    var options_evening = {
      title: 'Wykres podwieczorków',
      is3D: true,
      pieSliceText: 'none',
    };
    var options_dinner = {
      title: 'Wykres kolacjii',
      is3D: true,
      pieSliceText: 'none',
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechartBrekfast'));
    var chart_sec = new google.visualization.PieChart(document.getElementById('piechartSecBrekfast'));
    var chart_lunch = new google.visualization.PieChart(document.getElementById('piechartLunch'));
    var chart_evening = new google.visualization.PieChart(document.getElementById('piechartEvening'));
    var chart_dinner = new google.visualization.PieChart(document.getElementById('piechartDinner'));

    chart.draw(data, options);
    chart_sec.draw(data_sec, options_sec);
    chart_lunch.draw(data_lunch, options_lunch);
    chart_evening.draw(data_evening, options_evening);
    chart_dinner.draw(data_dinner, options_dinner);
  }
  </script>


  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Nazwa', 'Ilość'],
      <?php
                    require_once "./connect.php";
                    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
                    $id = $_SESSION['id'];
                    if(isset($_POST['date_from'])){
                      $result = $connect->query("SELECT name, count(*) as NUM FROM meds WHERE id_user='$id' AND taken='TAK' AND date_start BETWEEN $date_from AND $date_to");
                    }
                    else{
                      $result = $connect->query("SELECT name, count(*) as NUM FROM meds WHERE id_user='$id' AND taken='TAK' AND date_start = $currentDate");
                    }
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['Ilość wziętych leków', ".$row["NUM"]."],";  
                      }
                    if(isset($_POST['date_from'])){
                      $result2 = $connect->query("SELECT name, count(*) as NUM FROM meds WHERE id_user='$id' AND taken='NIE' AND date_start BETWEEN $date_from AND $date_to");
                    }
                    else{
                      $result2 = $connect->query("SELECT name, count(*) as NUM FROM meds WHERE id_user='$id' AND taken='NIE' AND date_start = $currentDate");
                    }
                    while($row=mysqli_fetch_assoc($result2)){
                      echo "['Ilość nie wziętych leków', ".$row["NUM"]."],";  
                      }
                  ?>
    ]);

    var options = {
      title: 'Wykres podsumowania leków',
      is3D: true,
      slices: {
        0: {
          color: '#00FF08'
        },
        1: {
          color: 'red'
        },
      },
      pieSliceText: 'none',
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechartMeds'));

    chart.draw(data, options);
  }
  </script>


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

    <div class='summary'>
      <div class="display-div">
        <div class="date-from-to">
          <form action="" method="post">
            Data od: <input type='date' name="date_from" id="date_from">
            Data do: <input type='date' name="date_to" id="date_to">
            <input type="submit" value="Zatwierdź">
          </form>
        </div>
        <hr>
        <div class="summary-div">
          <button type="button" class="summary-button-measurement" data-toggle="modal"
            data-target="#summaryModalMeasurement">Pokaż
            podsumowanie pomiarów</button>
          <button type="button" class="summary-button-meds" data-toggle="modal" data-target="#summaryModalMeds">Pokaż
            podsumowanie leków</button>
          <button type="button" class="summary-button-food" data-toggle="modal" data-target="#summaryModalFoods">Pokaż
            podsumowanie jadłospisu</button>
          <button type="button" class="summary-button-patient-diary" data-toggle="modal"
            data-target="#summaryModalDiary">Pokaż podsumowanie dziennika pacjenta</button>
        </div>

        <div class="modal fade" id="summaryModalMeasurement" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalCenterTitle">Podsumowanie pomiarów cukru</h3>
              </div>
              <div class="modal-body">
                <div id="piechartBefore"></div>
                <div id="piechartAfter"></div>
              </div>
            </div>
          </div>
        </div>



        <div class=" modal fade" id="summaryModalMeds" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalCenterTitle">Podsumowanie leków</h3>
              </div>
              <div class="modal-body">
                <div id="piechartMeds"></div>
              </div>
            </div>
          </div>
        </div>



        <div class=" modal fade" id="summaryModalFoods" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalCenterTitle">Podsumowanie jadłospisu</h3>
              </div>
              <div class="modal-body" style="height: 500px; overflow: scroll; overflow-x: hidden;">
                <div id="piechartBrekfast"></div>
                <div id="piechartSecBrekfast"></div>
                <div id="piechartLunch"></div>
                <div id="piechartEvening"></div>
                <div id="piechartDinner"></div>
              </div>
            </div>
          </div>
        </div>



        <div class=" modal fade" id="summaryModalDiary" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalCenterTitle">Podsumowanie dzienniku pacjenta</h3>
              </div>
              <div class="modal-body">
                <div id="piechartDiary"></div>
              </div>
            </div>
          </div>
        </div>



      </div>
    </div>
  </main>
</body>
<footer>
  <p>Copyright &copy; Bartłomiej Olek</p>
</footer>

</html>