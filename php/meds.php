<?php
  session_start();

  if(!isset($_SESSION['isLogged']))
  {
    header('Location: index.php');
    exit();
  }

  $date = new DateTime("now", new DateTimeZone('Europe/Warsaw') );
  $currentTime = $date->format('H:i:s');
  $newTime = "'".$currentTime. "'";

  if(isset($_POST['date_to'])){
    $date_to = "'".$_POST['date_to']. "'";
    $date_from = "'".$_POST['date_from']. "'";
    }

  if(isset($_POST['name_med']))
  {
    $allGood = true;

    $nameM = $_POST['name_med'];
    $timeM = $_POST['time_med'];
    $id = $_SESSION['id'];
    $startDateM = $_POST['date_start'];
    $endDateM = $_POST['date_end'];

    if(empty($nameM))
    {
      $allGood = false;
      $_SESSION['e_nameM'] = "Pole z nazwą nie może zostać puste!";
    }


    if(empty($timeM))
    {
      $allGood = false;
      $_SESSION['e_timeM'] = "Pole z godziną nie może być puste!";
    }

    if(empty($startDateM))
    {
      $allGood = false;
      $_SESSION['e_startM'] = "Pole z datą początkową nie może być puste!";
    }

    if(empty($endDateM))
    {
      $allGood = false;
      $_SESSION['e_endM'] = "Pole z godziną końcową nie może być puste!";
    }

    
    $_SESSION['form_nameM'] = $nameM;
    $_SESSION['form_timeM'] = $timeM;
    $_SESSION['form_startM'] = $startDateM;
    $_SESSION['form_endM'] = $endDateM;

    
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

        if($allGood==true)
        {
          $connect->query("INSERT INTO meds VALUES(NULL,'$id','$nameM','$timeM','NIE','00:00:00','$startDateM','$endDateM')");
          header('Location: meds.php');
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
  
  if(isset($_GET['id'])){
    require_once "./connect.php";
    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
    $idDel = $_GET['id'];
    $idRow = $_GET['id_row'];
    $result = $connect->query("DELETE FROM meds WHERE id_user='$idDel' AND id_med='$idRow'");
    header('Location: meds.php');
  }

  if(isset($_GET['id_t'])){
    require_once "./connect.php";
    $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port);
    $idDel = $_GET['id_t'];
    $idRow = $_GET['id_row'];
    $result = $connect->query("UPDATE meds SET hour_taken=$newTime WHERE id_user='$idDel' AND id_med='$idRow'");
    $result = $connect->query("UPDATE meds SET taken='TAK' WHERE id_user='$idDel' AND id_med='$idRow'");
    header('Location: meds.php');
  }
 

  ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"
    integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous">
  </script>


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
    integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css"
    integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"
    integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
  </script>

  <title>Leki</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/meds.css" />
  <script src="../js/script.js"></script>


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
                    $result = $connect->query("SELECT name, count(*) as NUM FROM meds WHERE id_user='$id' AND taken='TAK'");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['Ilość wziętych leków', ".$row["NUM"]."],";  
                      }
                    $result2 = $connect->query("SELECT name, count(*) as NUM FROM meds WHERE id_user='$id' AND taken='NIE'");
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
    <div class="meds">
      <div class="display-div-meds">
        <div class="buttons-add-div">
          <button type="button" class="btn-add" data-toggle="modal" data-target="#exampleModalCenter">
            Dodaj nowy lek
          </button>
          <button type="button" class="summary-button" data-toggle="modal" data-target="#summaryModal">Pokaż
            podsumowanie</button>
        </div>
        <hr>
        <div class="date-from-to">
          <form action="" method="post">
            Data od: <input type='date' name="date_from" id="date_from">
            Data do: <input type='date' name="date_to" id="date_to">
            <input type="submit" value="Zatwierdź">
          </form>
        </div>


        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalCenterTitle">Dodawanie nowego leku</h3>
              </div>
              <div class="modal-body">
                <form class="meds-form" method="post">
                  <input type='text' placeholder="Podaj nazwę leku" name="name_med" value="<?php
              if(isset($_SESSION['form_nameM']))
              {
                echo $_SESSION['form_nameM'];
                unset($_SESSION['form_nameM']);
              }
           ?>">
                  <?php
            if(isset($_SESSION['e_nameM']))
            {
              echo '<div class="errorD">'.$_SESSION['e_nameM'].'</div>';
              unset($_SESSION['e_nameM']);
            }
          ?>
                  <input type='time' name="time_med" value="<?php
              if(isset($_SESSION['form_timeM']))
              {
                echo $_SESSION['form_timeM'];
                unset($_SESSION['form_timeM']);
              }
           ?>">
                  <?php
            if(isset($_SESSION['e_timeM']))
            {
              echo '<div class="errorD">'.$_SESSION['e_timeM'].'</div>';
              unset($_SESSION['e_timeM']);
            }
          ?>

                  <input type='date' name="date_start" value="<?php
              if(isset($_SESSION['form_startM']))
              {
                echo $_SESSION['form_startM'];
                unset($_SESSION['form_startM']);
              }
           ?>">
                  <?php
            if(isset($_SESSION['e_startM']))
            {
              echo '<div class="errorD">'.$_SESSION['e_startM'].'</div>';
              unset($_SESSION['e_startM']);
            }
          ?>

                  <input type='date' name="date_end" value="<?php
              if(isset($_SESSION['form_endM']))
              {
                echo $_SESSION['form_endM'];
                unset($_SESSION['form_endM']);
              }
           ?>">
                  <?php
            if(isset($_SESSION['e_endM']))
            {
              echo '<div class="errorD">'.$_SESSION['e_endM'].'</div>';
              unset($_SESSION['e_endM']);
            }
          ?>


                  <input type="submit" value="Dodaj lek">
                </form>
              </div>
            </div>
          </div>
        </div>
        <hr>


        <div class=" modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
          aria-hidden="true">
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

        <table border="1">
          <tr>
            <th>Nazwa leku</th>
            <th>Data początkowa</th>
            <th>Data końcowa</th>
            <th>Godzina</th>
            <th>Godzina wzięcia</th>
            <th>Czy wzięte?</th>
            <th>Lek</th>
            <th>Usunięcie</th>
          </tr>
          <?php


          $id = $_SESSION['id'];
          require_once "./connect.php";
          $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port); 
          if(isset($_POST['date_from'])){
            $result = $connect->query("SELECT * FROM meds WHERE id_user='$id' AND date_start BETWEEN $date_from AND $date_to ORDER BY date_start");
          }
          $howMany = $result->num_rows;
            if($howMany>0){
              while($row = $result->fetch_assoc()){
                $rowId = $row['id_med'];
                $userId = $row['id_user'];
                echo "<tr>
                          <td>".$row['name']."</td>
                          <td>".$row['date_start']."</td>
                          <td>".$row['date_end']."</td>
                          <td>".$row['time']."</td>
                          <td>".$row['hour_taken']."</td>";
                if($row['taken']=='TAK'){
                  echo "<td style='color: green;'>".$row['taken']."</td>";
                }
                else{
                  echo "<td style='color: red;'>".$row['taken']."</td>";
                }

                echo "
                <script type='text/JavaScript'>
                    function deleteRow(rowId) {
                        swal({
                            title: 'Czy na pewno chcesz usunąć lek?',
                            icon: 'warning',
                            buttons: ['Anuluj', 'Usuń'],  
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                swal('Lek został usunięty pomyślnie', {
                                    icon: 'success',
                                });
                                setTimeout(function (){  
                                    window.location.href = 'meds.php?id=".$row['id_user']."&id_row=' + rowId;
                                }, 800)
                            } else {
                                swal('Anulowano usunięcie leku', {
                                    icon: 'error',
                                })
                            }
                        });
                    }
                </script>";
                
                echo "<td>
                        <a href='meds.php?id_t=".$row['id_user']."&id_row=".$row['id_med']."&take=".$row['taken']."' class='btnTake'>Weź</a>
                    </td>
                    <td>
                        <button class='btnDel' onclick='deleteRow(".$row['id_med'].")'>Usuń</button>
                    </td>
                </tr>";
              }
            }
          
          ?>
        </table>
      </div>
    </div>
  </main>
</body>
<footer>
  <p>Copyright &copy; Bartłomiej Olek</p>
</footer>

</html>