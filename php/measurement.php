<?php
  session_start();

  if(!isset($_SESSION['isLogged']))
  {
    header('Location: index.php');
    exit();
  }
  if(isset($_POST['date_to'])){
  $date_to = "'".$_POST['date_to']. "'";
  $date_from = "'".$_POST['date_from']. "'";
  }

  $currentDate = "'".date('Y-m-d')."'";

  if(isset($_POST['value']))
  {
    $allGood = true;

  
    $value = $_POST['value'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $id = $_SESSION['id'];
    $when = filter_input(INPUT_POST, 'typeMeas', FILTER_SANITIZE_STRING);


    if($value<0)
    {
      $allGood = false;
      $_SESSION['e_value'] = "Pole z pomiarem nie może zostać puste!";
    }

    if(empty($date))
    {
      $allGood = false;
      $_SESSION['e_date_m'] = "Pole z datą nie może być puste!";
    }

    if(empty($time))
    {
      $allGood = false;
      $_SESSION['e_time'] = "Pole z godziną nie może być puste!";
    }
    if(empty($when))
    {
      $allGood = false;
      $_SESSION['e_when'] = "Pole z wyborem pory nie może być puste!";
    }

    $_SESSION['form_value_me'] = $value;
    $_SESSION['form_date_me'] = $date;
    $_SESSION['form_time_me'] = $time;
    
   
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
          $connect->query("INSERT INTO measurement VALUES(NULL,'$id','$value','$date','$time','$when')");
          header('Location: measurement.php');
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
    $result = $connect->query("DELETE FROM measurement WHERE id_user='$idDel' AND id_meas='$idRow'");
    header('Location: measurement.php');
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

  <title>Pomiary</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/measurement.css" />
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
                      $result = $connect->query("SELECT count(value)as below  FROM measurement WHERE value<70  AND whenTake='after' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
                      while($row=mysqli_fetch_assoc($result)){
                        echo "['Poniżej', ".$row["below"]."],";  
                    }
                  }else{
                    $result = $connect->query("SELECT count(value)as below  FROM measurement WHERE value<70  AND whenTake='after' AND id_user='$id' AND `date`= $currentDate ORDER BY date");
                      while($row=mysqli_fetch_assoc($result)){
                        echo "['Poniżej', ".$row["below"]."],";  
                    }
                  }

                  if(isset($_POST['date_from'])){
                    $result = $connect->query("SELECT count(value) as good FROM measurement WHERE (value>=70 AND value<=99) AND whenTake='after' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date ");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['W nornmie', ".$row["good"]."],";  
                    }
                  }else{
                    $result = $connect->query("SELECT count(value) as good FROM measurement WHERE (value>=70 AND value<=99) AND whenTake='after' AND id_user='$id' AND `date`= $currentDate ORDER BY date ");
                    while($row=mysqli_fetch_assoc($result)){
                      echo "['W nornmie', ".$row["good"]."],";  
                    }
                  }

                  if(isset($_POST['date_from'])){
                    $result = $connect->query("SELECT count(value) as above FROM measurement WHERE value>140 AND whenTake='after' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date ");
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
                    $result = $connect->query("SELECT count(value) as good FROM measurement WHERE (value>=70 AND value<=99) AND whenTake='before' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date ");
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
                    $result = $connect->query("SELECT count(value) as above FROM measurement WHERE value>99 AND whenTake='before' AND id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date ");
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
    <div class="measurement">
      <div class="measurement-div">
        <div class="display-div">
          <div class="buttons-add-div">
            <button type="button" class="btn-add" data-toggle="modal" data-target="#exampleModalCenter">
              Dodaj nowy pomiar
            </button>
            <button type="button" class="summary-button" data-toggle="modal" data-target="#summaryModal">Pokaż
              podsumowanie</button>
          </div>

          <div class=" modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h3 class="modal-title" id="exampleModalCenterTitle">Dodawanie pomiaru cukru</h3>
                </div>
                <div class="modal-body">
                  <form class="measurement-form" method="post">
                    <input type='number' step="0.01" placeholder="Podaj pomiar" name="value" value="<?php
              if(isset($_SESSION['form_value_me']))
              {
                echo $_SESSION['form_value_me'];
                unset($_SESSION['form_value_me']);
              }
           ?>">
                    <?php
            if(isset($_SESSION['e_value']))
            {
              echo '<div class="errorD">'.$_SESSION['e_value'].'</div>';
              unset($_SESSION['e_value']);
            }
          ?>

                    <select name="typeMeas" id="typeMeas">
                      <option value="none" selected disabled hidden>Wybierz porę pomiaru</option>
                      <option value="before">Na czczo</option>
                      <option value="after">Po jedzeniu</option>
                    </select>

                    <?php
            if(isset($_SESSION['e_when']))
            {
              echo '<div class="errorD">'.$_SESSION['e_when'].'</div>';
              unset($_SESSION['e_when']);
            }
          ?>


                    <input type='time' name="time" value="<?php
              if(isset($_SESSION['form_time_me']))
              {
                echo $_SESSION['form_time_me'];
                unset($_SESSION['form_time_me']);
              }
           ?>">
                    <?php
            if(isset($_SESSION['e_time']))
            {
              echo '<div class="errorD">'.$_SESSION['e_time'].'</div>';
              unset($_SESSION['e_time']);
            }
          ?>

                    <input type='date' name="date" value="<?php
              if(isset($_SESSION['form_date_me']))
              {
                echo $_SESSION['form_date_me'];
                unset($_SESSION['form_date_me']);
              }
           ?>">
                    <?php
            if(isset($_SESSION['e_date_m']))
            {
              echo '<div class="errorD">'.$_SESSION['e_date_m'].'</div>';
              unset($_SESSION['e_date_m']);
            }
          ?>
                    <input type="submit" value="Dodaj pomiar">
                  </form>
                </div>
              </div>
            </div>
          </div>
          <hr>



          <div class=" modal fade" id="summaryModal" tabindex="-1" role="dialog"
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


          <table id="tableMes">
            <th id="below">pomiar poniżej normy<br /><br />(<70 mg/dl)</th>
            <th id="normal">pomiar w normie<br /><br />(72-99 mg/dl)</th>
            <th id="above">pomiar powyżej normy<br /><br />(>99 mg/dl na czczo)<br /><br />(>140mg/dl po posiłku)</th>
          </table>
          <div class="date-from-to">
            <form action="" method="post">
              Data od: <input type='date' name="date_from" id="date_from">
              Data do: <input type='date' name="date_to" id="date_to">
              <input type="submit" value="Zatwierdź">
            </form>
          </div>

          <table border="1">
            <tr>
              <th>Pomiar cukru</th>
              <th>Kiedy</th>
              <th>Data</th>
              <th>Godzina</th>
              <th>Usuwanie</th>
            </tr>
            <?php
          
          $id = $_SESSION['id'];
          require_once "./connect.php";
          $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port); 
          if(isset($_POST['date_from'])){
          $result = $connect->query("SELECT * FROM measurement WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
          } else {
            $result = $connect->query("SELECT * FROM measurement WHERE id_user='$id' AND `date`= $currentDate ORDER BY date");
          }
          $howMany = $result->num_rows;
            if($howMany>0){
              while($row = $result->fetch_assoc()){
                $rowId = $row['id_meas'];
                $userId = $row['id_user'];
                  echo "
                        <tr> ";
                          if($row['value']<70&& $row['whenTake']=='before'){

                            echo "<td style='color: orange;'>".$row['value']." mg/dl</td>";
                          }
                          if(($row['value']>70&&$row['value']<99)&& $row['whenTake']=='before'){
                            echo "<td style='color: green;'>".$row['value']." mg/dl</td>";
                          }
                          if($row['value']>99&& $row['whenTake']=='before'){
                            echo "<td style='color: red;'>".$row['value']." mg/dl</td>";
                          }
                          if($row['value']<70&& $row['whenTake']=='after'){
                            echo "<td style='color: orange;'>".$row['value']." mg/dl</td>";
                          }
                          if(($row['value']>70&&$row['value']<140)&& $row['whenTake']=='after'){
                            echo "<td style='color: green;'>".$row['value']." mg/dl</td>";
                          }
                          if($row['value']>140&& $row['whenTake']=='after'){
                            echo "<td style='color: red;'>".$row['value']." mg/dl</td>";
                          }

                          if($row['whenTake']=='before'){
                            echo "<td>Na czczo</td>";
                          }
                          if($row['whenTake']=='after'){
                            echo "<td>Po jedzeniu</td>";
                          }

                          echo "
                          <script type='text/JavaScript'>
                              function deleteRow(row) {
                                  var rowId = row
                                  swal({
                                      title: 'Czy na pewno chcesz usunąć pomiar?',
                                      icon: 'warning',
                                      buttons: ['Anuluj', 'Usuń'],  
                                      dangerMode: true,
                                  })
                                  .then((willDelete) => {
                                      if (willDelete) {
                                          swal('Pomiar został usunięty pomyślnie', {
                                              icon: 'success',
                                          });
                                          setTimeout(function (){  
                                              window.location.href = 'measurement.php?id=".$row['id_user']."&id_row=' + rowId;
                                          }, 800)
                                      } else {
                                          swal('Anulowano usunięcie pomiaru', {
                                              icon: 'error',
                                          })
                                      }
                                  });
                              }
                          </script>";


                          echo "
                          <td>".$row['date']."</td>
                          <td>".$row['time']."</td>
                          <td>
                            <button class='btnDel' id='$rowId' onclick='deleteRow($rowId)'>Usuń</button>
                          </td>                      
                        </tr>
                ";
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