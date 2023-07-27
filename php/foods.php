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

  if(isset($_POST['date_f']))
  {
    $allGood = true;

    $id = $_SESSION['id'];

    $breakfast = filter_input(INPUT_POST, 'breakfast', FILTER_SANITIZE_STRING);
    $sec_breakfast = filter_input(INPUT_POST, 'sec_breakfast', FILTER_SANITIZE_STRING);
    $lunch = filter_input(INPUT_POST, 'lunch', FILTER_SANITIZE_STRING);
    $evening_food = filter_input(INPUT_POST, 'evening_food', FILTER_SANITIZE_STRING);
    $dinner = filter_input(INPUT_POST, 'dinner', FILTER_SANITIZE_STRING);
    
   $x = $_POST['date_f'];
    
    
    if(empty($_POST['date_f']))
    {
      $allGood = false;
      $_SESSION['e_date_f'] = "Pole z data nie może być puste!";
    }
    if(empty($breakfast))
    {
      $allGood = false;
      $_SESSION['e_brekfast'] = "Pole z śniadaniem nie może być puste!";
    }
    if(empty($sec_breakfast ))
    {
      $allGood = false;
      $_SESSION['e_sec_b'] = "Pole z drugim śniadaniem nie może być puste!";
    }
    if(empty($lunch))
    {
      $allGood = false;
      $_SESSION['e_lunch'] = "Pole z obiadem nie może być puste!";
    }
    if(empty($evening_food ))
    {
      $allGood = false;
      $_SESSION['e_evening'] = "Pole z podwieczorkiem nie może być puste!";
    }
    if(empty($dinner ))
    {
      $allGood = false;
      $_SESSION['e_dinner'] = "Pole z kolacją nie może być puste!";
    }

  

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
          $connect->query("INSERT INTO food VALUES(NULL,'$id','$breakfast','$sec_breakfast','$lunch','$evening_food','$dinner','$x')");
          header('Location: foods.php');
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
    $result = $connect->query("DELETE FROM food WHERE id_user='$idDel' AND id_food='$idRow'");
    header('Location: foods.php');
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


  <title>Jedzenie</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/food.css" />
  <script src="../js/script.js"></script>
</head>

<body>
  <header>
    <nav>
      <ul>
        <li class="logo"><a href="./home.php">Diabetes Tracker</a></li>
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
    <div class='food'>
      <div class="food-div-display">
        <div class="buttons-add-div">
          <button type="button" class="btn-add" data-toggle="modal" data-target="#exampleModalCenter">
            Dodaj nowy posiłek
          </button>
          <button type="button" class="summary-button" data-toggle="modal" data-target="#summaryModal">Pokaż
            podsumowanie</button>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalCenterTitle">Dodawanie nowego posiłku</h3>
              </div>
              <div class="modal-body">
                <form class="food-form" method="post">
                  <select name="breakfast" id="breakfast">
                    <option value="none" selected disabled hidden>Śniadanie</option>
                    <option value="Razowe kanapki z twarożkiem i pomidorem">Razowe kanapki z twarożkiem i pomidorem
                    </option>
                    <option value="Owsianka">Owsianka</option>
                    <option value="Jajecznica z pomidorami">Jajecznica z pomidorami</option>
                    <option value="Omlet z chudą wędliną">Omlet z chudą wędliną</option>
                    <option value="Kanapki z pastą z jajka">Kanapki z pastą z jajka</option>
                    <option value="Omlet ze startym jabłkiem i cynamonem">Omlet ze startym jabłkiem i cynamonem
                    </option>
                    <option value="Kanapki razowe z pastą z tuńczyka i jogurtu">Kanapki razowe z pastą z tuńczyka i
                      jogurtu
                    </option>
                    <option value="Jajka na twardo">Jajka na twardo</option>

                  </select>
                  <?php
            if(isset($_SESSION['e_brekfast']))
            {
              echo '<div class="errorD">'.$_SESSION['e_brekfast'].'</div>';
              unset($_SESSION['e_brekfast']);
            }
          ?>
                  <select name="sec_breakfast" id="sec_breakfast">
                    <option value="none" selected disabled hidden>Drugie śniadani</option>
                    <option value="Koktajl z jabłka, kefiru i cynamonu">Koktajl z jabłka, kefiru i cynamonu</option>
                    <option value="Kanapki razowe z chudą wędliną i ogórkiem kiszonym">Kanapki razowe z chudą wędliną
                      i
                      ogórkiem
                      kiszonym</option>
                    <option value="Sałatka owocowa z jabłka i pomarańczy z migdałami">Sałatka owocowa z jabłka i
                      pomarańczy z
                      migdałami</option>
                    <option value="Kanapki z twarożkiem i szczypiorkiem">Kanapki z twarożkiem i szczypiorkiem</option>
                    <option value="Sałatka z gotowanym kurczakiem i pomidorami">Sałatka z gotowanym kurczakiem i
                      pomidorami
                    </option>
                    <option value="Kanapki z serem żółtym i ogórkiem kiszonym"> Kanapki z serem żółtym i ogórkiem
                      kiszonym
                    </option>
                    <option value="Owsianka z gruszką, jabłkiem i migdałami">Owsianka z gruszką, jabłkiem i migdałami
                    </option>
                    <option value="Pudding twarogowy z malinami">Pudding twarogowy z malinami</option>
                  </select>
                  <?php
            if(isset($_SESSION['e_sec_b']))
            {
              echo '<div class="errorD">'.$_SESSION['e_sec_b'].'</div>';
              unset($_SESSION['e_sec_b']);
            }
          ?>
                  <select name="lunch" id="lunch">
                    <option value="none" selected disabled hidden>Obiad</option>
                    <option value="Pierś z kurczaka w sosie pomidorowym na brązowym ryżu">Pierś z kurczaka w sosie
                      pomidorowym
                      na brązowym ryżu</option>
                    <option value="Pulpety z indyka w sosie pieczarkowym z kaszą pęczak">Pulpety z indyka w sosie
                      pieczarkowym z
                      kaszą pęczak</option>
                    <option value="Zupa pieczarkowa z mięsem z kurczaka">Zupa pieczarkowa z mięsem z kurczaka</option>
                    <option value="Schab duszony w sosie własnym z kalafiorem i kaszą gryczaną">Schab duszony w sosie
                      własnym z
                      kalafiorem i kaszą gryczaną</option>
                    <option value="Kotleciki z kaszy gryczanej z sałatką z kiszonych ogórków">Kotleciki z kaszy
                      gryczanej z
                      sałatką z kiszonych ogórków</option>
                    <option value="Pulpeciki z fileta z dorsza w sosie koperkowym z ziemniakami">Pulpeciki z fileta z
                      dorsza w
                      sosie koperkowym z ziemniakami</option>
                    <option value="Zapiekanka makaronowa z chudą wędliną i pomidorami">Zapiekanka makaronowa z chudą
                      wędliną i
                      pomidorami</option>
                    <option value="Makaron z kurczakiem i szpinakiem w pesto z rukoli">Makaron z kurczakiem i
                      szpinakiem
                      w pesto
                      z rukoli</option>
                  </select>
                  <?php
            if(isset($_SESSION['e_lunch']))
            {
              echo '<div class="errorD">'.$_SESSION['e_lunch'].'</div>';
              unset($_SESSION['e_lunch']);
            }
          ?>
                  <select name="evening_food" id="evening_food">
                    <option value="none" selected disabled hidden>Podwieczorek</option>
                    <option value="Orzechy włoskie i kawałki gruszki">Orzechy włoskie i kawałki gruszki</option>
                    <option value="Sałatka z pomarańczy i orzechów włoskich">Sałatka z pomarańczy i orzechów włoskich
                    </option>
                    <option value="Budyń na mleku z mrożonymi truskawkami i orzechami">Budyń na mleku z mrożonymi
                      truskawkami i
                      orzechami</option>
                    <option value="Koktajl z gruszki, kakao i kefiru">Koktajl z gruszki, kakao i kefiru</option>
                    <option value="Surówka z marchewki, jabłka i selera z orzechami włoskimi">Surówka z marchewki,
                      jabłka i
                      selera z orzechami włoskimi</option>
                    <option value="Kanapka z masłem i siekanymi orzechami">Kanapka z masłem i siekanymi orzechami
                    </option>
                    <option value="Koktajl ze świeżego buraka, marchewki i jabłka oraz migdały">Koktajl ze świeżego
                      buraka,
                      marchewki i jabłka oraz migdały</option>
                    <option value="Serek ziarnisty, pieczywo razowe, pomidorki koktajlowe,">Serek ziarnisty, pieczywo
                      razowe,
                      pomidorki koktajlowe,</option>
                  </select>
                  <?php
            if(isset($_SESSION['e_evening']))
            {
              echo '<div class="errorD">'.$_SESSION['e_evening'].'</div>';
              unset($_SESSION['e_evening']);
            }
          ?>
                  <select name="dinner" id="dinner">
                    <option value="none" selected disabled hidden>Kolacja</option>
                    <option value="Sałatka z kaszy gryczanej, kiszonego ogórka i fety">Sałatka z kaszy gryczanej,
                      kiszonego
                      ogórka i fety</option>
                    <option value="Kanapki razowe z pastą z fety i ogórka">Kanapki razowe z pastą z fety i ogórka
                    </option>
                    <option value="Zapiekanka brokułowa z fetą i ziemniakami">Zapiekanka brokułowa z fetą i
                      ziemniakami
                    </option>
                    <option value="Bułka razowa z pastą z makreli">Bułka razowa z pastą z makreli</option>
                    <option value="Kanapki z wędliną, serem żółtym i rzodkiewką">Kanapki z wędliną, serem żółtym i
                      rzodkiewką
                    </option>
                    <option value="Sałatka z brokułem, tuńczykiem i makaronem pełnoziarnistym">Sałatka z brokułem,
                      tuńczykiem i
                      makaronem pełnoziarnistym</option>
                    <option value="Sałatka z fasoli konserwowej, kaszy bulgur i papryki">Sałatka z fasoli konserwowej,
                      kaszy
                      bulgur i papryki</option>
                    <option value="Sałatka z rukolą, kurczakiem, kolorowymi paprykami i jogurtowo-ziołowym dressingiem">
                      Sałatka
                      z rukolą, kurczakiem, kolorowymi paprykami i jogurtowo-ziołowym dressingiem.</option>
                  </select>
                  <?php
            if(isset($_SESSION['e_dinner']))
            {
              echo '<div class="errorD">'.$_SESSION['e_dinner'].'</div>';
              unset($_SESSION['e_dinner']);
            }
          ?>
                  <input type='date' name='date_f'>
                  <?php
            if(isset($_SESSION['e_date_f']))
            {
              echo '<div class="errorD">'.$_SESSION['e_date_f'].'</div>';
              unset($_SESSION['e_date_f']);
            }
          ?>
                  <input type="submit" value="Dodaj posiłki">
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



        <div class="date-from-to">
          <form action="" method="post">
            Data od: <input type='date' name="date_from" id="date_from">
            Data do: <input type='date' name="date_to" id="date_to">
            <input type="submit" value="Zatwierdź">
          </form>
        </div>

        <table border="1">
          <tr>
            <th>Śniadanie</th>
            <th>Drugie śniadanie</th>
            <th>Obiad</th>
            <th>Podwieczorek</th>
            <th>Kolacja</th>
            <th style="width:10%">Data</th>
            <th>Usuwanie</th>
          </tr>
          <?php


            $id = $_SESSION['id'];
            require_once "./connect.php";
            $connect = new mysqli($host,$db_user,$db_password,$db_name,$db_port); 
            if(isset($_POST['date_from'])){
              $result = $connect->query("SELECT * FROM food WHERE id_user='$id' AND `date` BETWEEN $date_from AND $date_to ORDER BY date");
              } else {
                $result = $connect->query("SELECT * FROM food WHERE id_user='$id' AND `date`= $currentDate ORDER BY date");
              }
            $howMany = $result->num_rows;
              if($howMany>0){
                while($row = $result->fetch_assoc()){
                  $rowId = $row['id_food'];
                  $userId = $row['id_user'];

                  
                  echo "
                  <script type='text/JavaScript'>
                      function deleteRow(rowId) {
                          swal({
                              title: 'Czy na pewno chcesz usunąć rekord?',
                              icon: 'warning',
                              buttons: ['Anuluj', 'Usuń'],  
                              dangerMode: true,
                          })
                          .then((willDelete) => {
                              if (willDelete) {
                                  swal('Rekord został usunięty pomyślnie', {
                                      icon: 'success',
                                  });
                                  setTimeout(function (){  
                                      window.location.href = 'foods.php?id=".$row['id_user']."&id_row=' + rowId;
                                  }, 800)
                              } else {
                                  swal('Anulowano usunięcie rekordu', {
                                      icon: 'error',
                                  })
                              }
                          });
                      }
                  </script>";
                  
                  echo "
                  <tr> 
                      <td>".$row['brekfast']."</td>
                      <td>".$row['sec_brekfast']."</td>
                      <td>".$row['lunch']."</td>
                      <td>".$row['evening']."</td>
                      <td>".$row['dinner']."</td>
                      <td>".$row['date']."</td>
                      <td>
                          <button class='btnDel' onclick='deleteRow(".$row['id_food'].")'>Usuń</button>
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