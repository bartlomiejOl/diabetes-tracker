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

  if(isset($_POST['description']))
  {
    $allGood = true;

    $description = $_POST['description'];
    $id = $_SESSION['id'];

    if(empty($description))
    {
      $allGood = false;
      $_SESSION['e_desc'] = "Pole z opisem nie może zostać puste!";
    }

    $date = $_POST['date_description'];

    if(empty($date))
    {
      $allGood = false;
      $_SESSION['e_date_desc'] = "Pole z datą nie może być puste!";
    }

    $_SESSION['form_description'] = $description;
    $_SESSION['form_date_pat'] = $date;


    
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
          $connect->query("INSERT INTO diary VALUES(NULL,'$id','$date','$description')");
          header('Location: patient_diary.php');
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
    $result = $connect->query("DELETE FROM diary WHERE id_user='$idDel' AND id_info='$idRow'");
    header('Location: patient_diary.php');
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
                    $result = $connect->query("SELECT description, count(*) as NUM FROM diary WHERE id_user='$id' GROUP BY description");
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





  <title>Dziennik pacjenta</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/petient_diary.css" />
  <script src="../js/script.js"></script>
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
    <div class='diary'>
      <div class="display-div">
        <div class="buttons-add-div">
          <button type="button" class="btn-add" data-toggle="modal" data-target="#exampleModalCenter">
            Dodaj nowy wpis
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
                <h3 class="modal-title" id="exampleModalCenterTitle">Dodawanie nowego wpisu</h3>
              </div>
              <div class="modal-body">
                <form class="diary-form" method="post">
                  <input type='text' placeholder="Opis" name="description" value="<?php
              if(isset($_SESSION['form_description']))
              {
                echo $_SESSION['form_description'];
                unset($_SESSION['form_description']);
              }
           ?>">
                  <?php
            if(isset($_SESSION['e_desc']))
            {
              echo '<div class="errorD">'.$_SESSION['e_desc'].'</div>';
              unset($_SESSION['e_desc']);
            }
          ?>
                  <input type='date' name="date_description" value="<?php
              if(isset($_SESSION['form_date_pat']))
              {
                echo $_SESSION['form_date_pat'];
                unset($_SESSION['form_date_pat']);
              }
           ?>">
                  <?php
            if(isset($_SESSION['e_date_desc']))
            {
              echo '<div class="errorD">'.$_SESSION['e_date_desc'].'</div>';
              unset($_SESSION['e_date_desc']);
            }
          ?>
                  <input type="submit" value="Dodaj wpis">
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
                <h3 class="modal-title" id="exampleModalCenterTitle">Podsumowanie dziennika pacjenta</h3>
              </div>
              <div class="modal-body">
                <div id="piechartDiary"></div>
              </div>
            </div>
          </div>
        </div>



        <table border="1">
          <tr>
            <th>Opis</th>
            <th>Data</th>
            <th>Usuwanie</th>
          </tr>
          <?php


          $id = $_SESSION['id'];
          require_once "./connect.php";
          $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port); 
          if(isset($_POST['date_from'])){
            $result = $connect->query("SELECT * FROM diary WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
          }
          $howMany = $result->num_rows;
            if($howMany>0){
              while($row = $result->fetch_assoc()){
                $rowId = $row['id_info'];
                $userId = $row['id_user'];
                
                echo "
                      <script type='text/JavaScript'>
                          function deleteRow(rowId) {
                              swal({
                                  title: 'Czy na pewno chcesz usunąć wpis?',
                                  icon: 'warning',
                                  buttons: ['Anuluj', 'Usuń'],  
                                  dangerMode: true,
                              })
                              .then((willDelete) => {
                                  if (willDelete) {
                                      swal('Wpis został usunięty pomyślnie', {
                                          icon: 'success',
                                      });
                                      setTimeout(function (){  
                                          window.location.href = 'patient_diary.php?id=".$row['id_user']."&id_row=' + rowId;
                                      }, 800)
                                  } else {
                                      swal('Anulowano usunięcie wpisu', {
                                          icon: 'error',
                                      })
                                  }
                              });
                          }
                      </script>";

                echo "<tr>
                          <td>".$row['description']."</td>
                          <td>".$row['date']."</td>
                          <td>
                              <button class='btnDel' onclick='deleteRow(".$row['id_info'].")'>Usuń</button>
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