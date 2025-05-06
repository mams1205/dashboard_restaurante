<!DOCTYPE html>
<html lang="en">

<head>
<link rel="stylesheet" href="main.css">
<?php

/* $Id:       index.php 2024/07/01 $*/
/* Author:    BSC                  $*/
/* $Revision: 2024-1.0             $*/

$PageSecurity = 1;

include('includes/librerias.php');
include('includes/session.inc');
$title = 'Menu Principal';

$ModuleLink = array();
$ModuleList = array();
$ModuleIcon = array();
$I   = 0;
$sql = "Select * From accesos_modulos 
         Where idmodulo In(Select idmodulo From accesos_modulos_usuarios 
                            Where usuario = '".$_SESSION['UserID']."')
         Order By idmodulo";
$res = DB_query($sql,$db);
while ($row = DB_fetch_array($res)) {
   $ModuleLink[$I] = $row['modulelink'];    
   $ModuleList[$I] = $row['descripcionmodulo'];
   $ModuleIcon[$I] = $row['icono'];
	$I++;
}

if (!isset($_GET['App'])) $_SESSION['Module'] = $ModuleLink[0];
else $_SESSION['Module'] = $_GET['App'];

?>
<?php
$today_date = date('Y-m-d');
// Convertir la fecha a formato YYYYMMDD
$formatted_date = date('Ymd', strtotime($today_date));

//ventas hoy
$sql_ventas = "SELECT SUM(ht_imp) AS total_ventas FROM SRe_HT WHERE ht_fec = '$formatted_date'";

$res_ventas = DB_query($sql_ventas, $db);

if ($row = DB_fetch_array($res_ventas)) {
$total_ventas = $row['total_ventas'];

// Depuración: Verifica el valor de $total_ventas
// var_dump($total_ventas); // Esto te permitirá ver qué valor tiene

// Asegúrate de que no sea NULL o vacío
$total_ventas = (float) $total_ventas; // Asegúrate de convertirlo a número

// Asignar el valor de 45 si es 0 o NULL
$total_ventas = $total_ventas > 0 ? $total_ventas : 0;
$total_ventas = number_format($total_ventas, 3, '.', ',');
} else {
$total_ventas = 33; // 
}
/////

// //consulta para la semana
$firstDayOfWeek = date('Y-m-d', strtotime('last monday', strtotime($today_date)));
$formatted_monday = date('Ymd', strtotime($firstDayOfWeek));


// // Crear un array para almacenar las fechas de la semana

for ($i = 0; $i < 7; $i++) {
    $dates_of_week[] = date('Y-m-d', strtotime("$formatted_monday +$i days"));
};

// Inicializar una variable para almacenar la suma total de ventas
$total_ventas_semana = 0;

// Crear la consulta SQL para obtener las ventas por día de la semana
foreach ($dates_of_week as $date) {
    $sql_ventas = "SELECT SUM(ht_imp) AS total_ventas 
                   FROM SRe_HT 
                   WHERE DATE(ht_fec) = '$date'";

    // Ejecutar la consulta
    $res_ventas = DB_query($sql_ventas, $db);
    
    // Guardar el resultado en una variable
    if ($row = DB_fetch_array($res_ventas)) {
        $total_ventas_s = $row['total_ventas'] ? $row['total_ventas'] : 0;
    } else {
        $total_ventas_s = 0;
    }

    // Sumar las ventas del día al total de ventas de la semana
    $total_ventas_semana += $total_ventas_s;
}

// ventas para el mes

$firstDayOfmonth = date('Y-m-d', strtotime('first day of this month', strtotime($today_date)));
$formatted_firstday = date('Ymd', strtotime($firstDayOfmonth));

// Obtener el último día del mes con el formato 'Y-m-d'
$lastDayOfMonth = date('Y-m-d', strtotime('last day of this month', strtotime($today_date)));
// Convertir esa fecha al formato 'Ymd' (sin guiones)
$formatted_lastday = date('Ymd', strtotime($lastDayOfMonth));


// Llenar el array con todas las fechas del mes
$current_date = $firstDayOfmonth ;
while ($current_date<= $lastDayOfMonth) {
    $dates_of_month[] = $current_date;
    $current_date = date('Y-m-d', strtotime($current_date . ' +1 day')); // Incrementar un día
}

// Inicializar una variable para almacenar el total de ventas del mes
$total_ventas_mes = 0;

// Realizar la consulta SQL para cada día del mes
foreach ($dates_of_month as $date) {
    // Realizar la consulta SQL para cada fecha
    $sql_ventas_mes = "SELECT SUM(ht_imp) AS total_ventas 
                   FROM SRe_HT 
                   WHERE DATE(ht_fec) = '$date'";

    // Ejecutar la consulta
    $res_ventas_mes = DB_query($sql_ventas_mes, $db);

    // Guardar el resultado
    if ($row = DB_fetch_array($res_ventas_mes)) {
        $total_ventas_month = $row['total_ventas'] ? $row['total_ventas'] : 0;
    } else {
        $total_ventas_month = 0;
    }

    // Sumar las ventas del día al total de ventas del mes
    $total_ventas_mes += $total_ventas_month;
}


?>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-primary navbar-dark justify-content-end" style="background-color: #282d3e;">
      <ul class="navbar-nav">
        <a href="indexppal.php">
          <img src="Images/home.jpg" alt="Inicio" class="brand-image img-circle elevation-3" style="opacity:.8">
        </a>
        <a href="help.php">
          <img src="Images/help.jpg" alt="Ayuda" class="brand-image img-circle elevation-3" style="opacity:.8">
        </a>
        <a href="Logout.php?'.SID.'" onclick="return confirm(\''.'Esta seguro de salir?'.'\');">
          <img src="Images/exit.jpg" alt="Salir" class="brand-image img-circle elevation-3" style="opacity:.8">
        </a>
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #282d3e;">
      <a href="indexppal.php" class="brand-link">
        <img src="Images/Logo_Gpode10_ppal.jpg" alt="Logo Slic-Soluciones" 
             class="brand-image img-rounded elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-dark pl-2" style="color: white;">Grupo de 10</span>
      </a>
      <!-- Sidebar -->
      <div class="sidebar" style="background-color: #282d3e;">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="Images/usuario.jpg" class="img-circle elevation-2" alt="Imagen usuario">
          </div>
          <div class="info">
            <?php
              echo '<a href="" class="d-block" style="color: white;">'.$_SESSION['UsersRealName'].'</a>';
            ?>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
              data-accordion="false">
            <li class="nav-header" style="color: white;">M O D U L O S</li>
              <?php
                $i = 0;
                while ($i < count($ModuleLink)) {
                  echo '<li class="nav-item has-treeview" style = "color white">
                          <a href="'.$ModuleLink[$i].'" class="nav-link" style="color: white;">
                            <i class="nav-icon '.$ModuleIcon[$i].'"></i>
                            <p style="color: white;">'.$ModuleList[$i].'</p>
                          </a>';
                  echo '</li>';
                  $i++;
                }
              ?>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
         <!-- Content Header (Page header) -->
         <div class="content-header">
            <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1 class="m-0 text-dark">Grupo de 10</h1>
                  </div>
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="indexppal.php">Menu Principal</a></li>
                     <li class="breadcrumb-item active">Inicio</li>
                     </ol>
                  </div>
               </div>
            </div>
         </div>
         <!-- /.content-header -->

         <!-- Main content -->
         <section class="content">
         <div class="container">
          <div class="row d-flex align-items-stretch">
              <div class="col-sm-3 p-2 p-sm-3">
              <div class="card h-100" 
                   style="background-color: #00a7fa; text-align: center; cursor: pointer;"
                   data-bs-toggle="modal" data-bs-target="#ventasModal">
                <div class="card-body">
                  <h5 class="card-title" style="color: white; font-size: 25px">Ventas Totales (Hoy)</h5>
                  <p class="card-text" style="color: white; font-size: 25px">$
                    <span
                        data-purecounter-start="0"
                        data-purecounter-end="<?= $total_ventas?>"
                        data-purecounter-duration="1"
                        date-purecounter-separator = "true"
                        data-purecounter-decimals = "3"
                        data-purecounter-currency = "true"

                        class="purecounter"
                    ></span>
                  </p>
                </div>
              </div>
              </div>

              <div class="col-sm-3 p-2 p-sm-3">
                <div class="card h-100" 
                    style="background-color: #00587A; text-align: center; cursor: pointer;" 
                    data-bs-toggle="modal" data-bs-target="#ventasModal_sem">
                  <div class="card-body">
                    <h5 class="card-title" style="color: white; font-size: 20px">Ventas Totales (Semana)</h5>
                    <p class="card-text" style="color: white; font-size: 25px">$
                      <span
                          data-purecounter-start="0"
                          data-purecounter-end="<?= number_format($total_ventas_semana, 3,'.','')?>"
                          data-purecounter-duration="1"
                          date-purecounter-separator = "true"
                          data-purecounter-decimals = "3"
                          data-purecounter-currency = "true"
                          class="purecounter"
                      ></span>
                    </p>
                  </div>
                </div>
              </div>

              <div class="col-sm-3  p-2 p-sm-3"">
              <div class="card h-100" style="background-color: #05184C; text-align: center; cursor: pointer;" >
                <div class="card-body">
                  <h5 class="card-title" style="color: white; font-size: 25px">Ventas Totales (Mes)</h5>
                  <p class="card-text" style="color: white; font-size: 25px">$
                    <span
                        data-purecounter-start="0"
                        data-purecounter-end="<?= $total_ventas_mes?>"
                        data-purecounter-duration="1"
                        date-purecounter-separator = "true"
                        data-purecounter-decimals = "3"
                        class="purecounter"
                    ></span>
                  </p>
                </div>
              </div>
              </div>

              <div class="col-sm-3  p-2 p-sm-3"">
              <div class="card h-100" style="background-color: rgba(248, 33, 65); text-align: center; cursor: pointer;" 
                  data-bs-toggle="modal" data-bs-target="#ventasModal_canc">
                <div class="card-body">
                  <h5 class="card-title" style="color: white; font-size: 25px">Cancelaciones (Hoy)</h5>
                  <p class="card-text" style="color: white; font-size: 25px">$
                    <span
                        data-purecounter-start="0"
                        data-purecounter-end="1234.3456"
                        data-purecounter-duration="1"
                        data-purecounter-decimals = "3"
                        date-purecounter-separator = "true"
                        class="purecounter"
                    ></span>
                  </p>
                </div>
              </div>
              </div>
  
          </div>

          <div class="row d-flex align-items-stretch">
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5>Acumulado del Mes</h5>
                  <br>
                  <canvas id="myChart"></canvas>
                </div>
              </div>
            </div>
          
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5>Ventas por Categoría</h5>
                  <br>
                  <canvas id="myChart2"></canvas>
                </div>
              </div>
            </div>

          </div>

          <div class="row d-flex align-items-stretch">
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5>Top 5 Bebidas del Mes</h5>
                  <br>
                  <canvas id="myChart4"></canvas>
                </div>
              </div>
            </div>
          
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5>Top 5 Alimentos del Mes</h5>
                  <br>
                  <canvas id="myChart3"></canvas>
                </div>
              </div>
            </div>

          </div>
        </div>
        </div>
    </section>


  <!-- MODAL VENTAS DE HOY TABLA -->
           <!-- modal ventas cancelaciones -->
           <div class="modal fade" id="ventasModal_canc" tabindex="-1" aria-labelledby="ventasModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ventasModalLabel_canc">Detalles de Cancelaciones (Hoy)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="pt-2 table-responsive table-hover">
                <table class = "table table-bordered">
                  <thead style="background-color: #282d3e;color: white; font-weight: bold;">
                    <tr>
                      <th> Sucursal </th>
                      <th> Tipo </th>
                      <th> Hora </th>
                      <th> Motivo </th>
                    </tr>
                  </thead>
                  <tfoot style="background-color: #bebebe;color: white;"> </tfoot>
                  <tbody>
                    <tr>
                      <td> BQ </td>
                      <td> Platillo Cancelado </td>
                      <td> 10:30:00 </td>
                      <td> Molletes Quemados </td>
                    </tr>
                    <tr>
                      <td> Const. </td>
                      <td> Cortesia </td>
                      <td> 10:45:00 </td>
                      <td> Cupon  </td>
                    </tr>
                    <tr>
                      <td> BQ </td>
                      <td> Cuenta Cancelada </td>
                      <td> 12:50:00 </td>
                      <td> Error Mesero </td>
                    </tr>
                    <tr>
                      <td> Const. </td>
                      <td> Platillo Cancelado </td>
                      <td> 16:10:00 </td>
                      <td> Platillo Equivocado </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ventasModal" tabindex="-1" aria-labelledby="ventasModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ventasModalLabel_hoy">Detalles de Ventas (Hoy)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="pt-2 table-responsive table-hover">
                <table class = "table table-bordered">
                  <thead style="background-color: #282d3e;color: white; font-weight: bold;">
                    <tr>
                      <th> Sucursal </th>
                      <th> Nombre </th>
                      <th> Tot. Ventas Hoy </th>
                    </tr>
                  </thead>
                  <tfoot style="background-color: #bebebe;color: white;"> </tfoot>
                  <tbody>
                    <?php
                      $sql_suc_hoy = "SELECT 
                                        ht_emp, 
                                        'Ante Sala',
                                        ROUND(SUM(ht_imp),2) AS total_ventas 
                                      FROM SRe_HT 
                                      WHERE DATE(ht_fec) = CURRENT_DATE()
                                      GROUP BY(ht_emp)";
                      $res_suc_hoy = DB_query($sql_suc_hoy, $db);
                      while($row = DB_fetch_row($res_suc_hoy)){
                    ?>
                    <tr>
                      <td> <?php echo $row[0] ?></td>
                      <td> <?php echo $row[1] ?></td>
                      <td> <?php echo '$' . number_format($row[2], 2); ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
      </div>
    </div>
  </div>


    <!-- modal ventas semana -->
    <div class="modal fade" id="ventasModal_sem" tabindex="-1" aria-labelledby="ventasModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ventasModalLabel_sem">Detalles de Ventas (Semana)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="pt-2 table-responsive table-hover">
                <table class = "table table-bordered">
                  <thead style="background-color: #282d3e;color: white; font-weight: bold;">
                    <tr>
                      <th> Sucursal </th>
                      <th> Nombre </th>
                      <th> Tot. Ventas Sem</th>
                    </tr>
                  </thead>
                  <tfoot style="background-color: #bebebe;color: white;"> </tfoot>
                  <tbody>
                    <?php
                      
                      $sql_suc_sem = "SELECT 
                                          ht_emp,
                                         'Ante Sala' as nombre,
                                          ROUND(SUM(ht_imp),2) AS total_ventas_semana
                                      FROM SRe_HT 
                                      WHERE DATE(ht_fec) BETWEEN DATE('$firstDayOfWeek') AND DATE('$today_date')
                                      GROUP BY ht_emp";
                      $res_suc_sem = DB_query($sql_suc_sem, $db);
                      while($row = DB_fetch_row($res_suc_sem)){
                    ?>
                    <tr>
                      <td> <?php echo $row[0] ?></td>
                      <td> <?php echo $row[1] ?></td>
                      <td> <?php echo '$' . number_format($row[2], 2); ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
      </div>
    </div>


 


  
    


 </body>
<?php
    $sql_cum_mes = "SELECT ROUND(SUM(ht_imp),2), DAY(ht_fec) as fecha
                    FROM SRe_HT 
                    WHERE MONTH(ht_fec) = MONTH(CURDATE())  
                  AND YEAR(ht_fec) = YEAR(CURDATE())
                  GROUP BY(ht_fec)
                    order by ht_fec ASC";

    // Ejecutar la consulta
    $res_cum_mes = DB_query($sql_cum_mes, $db);
    while($row = DB_fetch_row($res_cum_mes)) {
      $labels[] = $row[1];
      $data_cum[] = $row[0];
    }

    $labels_json = json_encode($labels);
    $data_json = json_encode($data_cum);

//Data grafica de ventas por categoria
    $sql_catego = "SELECT dt_lin, COUNT(*) 
                   FROM SRe_DT 
                   GROUP BY dt_lin";
    
    // Ejecutar la consulta
    $res_catego = DB_query($sql_catego, $db);
    while($row = DB_fetch_row($res_catego)) {
      $labels_catego[] = $row[0];
      $data_catego[] = $row[1];
    }

    $labels_catego_json = json_encode($labels_catego);
    $data_catego_json = json_encode($data_catego);

//DATOS TOP 5 ALIMENTOS 
    $sql_top_alim = "SELECT dt_des, SUM(dt_can) AS cuenta 
                     FROM SRe_DT 
                     WHERE dt_lin = 'ALIMEN' AND MONTH(dt_fec) = MONTH(CURRENT_DATE)
                     GROUP BY(dt_des) order by cuenta DESC
                     LIMIT 5";

    $res_top_alim = DB_query($sql_top_alim, $db);
    while($row = DB_fetch_row($res_top_alim)) {
      $labels_desc_alim[] = $row[0];
      $data_alim[] = $row[1];
    }

    $labels_alim_json = json_encode($labels_desc_alim);
    $data_alim_json = json_encode($data_alim);

//DATOS TOP 5 BEBIDAS
$sql_top_beb = "SELECT dt_des, sum(dt_can) AS cuenta 
                  FROM SRe_DT 
                  WHERE dt_lin = 'BEBIDA' AND MONTH(dt_fec) = MONTH(CURRENT_DATE)
                  GROUP BY(dt_des) order by cuenta DESC
                  LIMIT 5";

$res_top_beb = DB_query($sql_top_beb, $db);
while($row = DB_fetch_row($res_top_beb)) {
$labels_desc_beb[] = $row[0];
$data_beb[] = $row[1];
}

$labels_beb_json = json_encode($labels_desc_beb);
$data_beb_json = json_encode($data_beb);
?>

<!-- Bootstrap 5 JS Bundle (con Popper.js incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
 <script src="plugins/js/new_counter.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script>
  document.addEventListener("DOMContentLoaded", function() {
    const ctx1 = document.getElementById('myChart').getContext('2d');
    const ctx2 = document.getElementById('myChart2').getContext('2d');
    const ctx3 = document.getElementById('myChart3').getContext('2d');
    const ctx4 = document.getElementById('myChart4').getContext('2d');
    
    let labels = <?php echo $labels_json; ?>;
    let data = <?php echo $data_json; ?>;

    let labels_catego = <?php echo $labels_catego_json; ?>;
    let data_catego = <?php echo $data_catego_json; ?>;

    let labels_alim = <?php echo $labels_alim_json; ?>;
    let data_alim = <?php echo $data_alim_json; ?>;

    let labels_beb = <?php echo $labels_beb_json;?>;
    let data_beb = <?php echo $data_beb_json;?>;
    

    // Gráfico de líneas
    new Chart(ctx1, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Tot. de Ventas',
          data: data,
          borderWidth: 1,
          borderColor: 'black',
          backgroundColor: 'rgba(0, 0, 255, 0.5)',
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Gráfico de dona
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: labels_catego,
        datasets: [{
          label: 'My First Dataset',
          data: data_catego,
          backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
          ],
          hoverOffset: 4
        }]
      }
    });
 
    // Gráfico de líneas
    new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: labels_alim,
                    datasets: [{
                        data: data_alim,
                        label: 'Alimentos',
                        borderWidth: 1,
                        borderColor: 'black',
                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                      }]
                    },
                options: {
                    plugins: {
                        legend: {
                          display: false // Esto oculta la leyenda (label) de la gráfica
                          }
                        },
                    scales: {
                        y: {
                          beginAtZero: true, // Inicia en 0
                          min: 0, // Asegura que el mínimo sea 0
                          ticks: {
                              stepSize: 10 // Define el incremento de los ticks en el eje Y
                          }
                        }
                      }
                    }
                  });
    new Chart(ctx4, {
                type: 'bar',
                data: {
                    labels: labels_beb,
                    datasets: [{
                        data: data_beb,
                        label: 'Bebidas',
                        borderWidth: 1,
                        borderColor: 'black',
                        backgroundColor: 'rgba(0, 0, 255, 0.5)',
                    }]
                },
                options: {
        indexAxis: 'y', // Convierte el gráfico en horizontal
        plugins: {
            legend: {
                display: false // Oculta la leyenda
            }
        },
        scales: {
            x: { // Ahora el eje X es el valor numérico
                beginAtZero: true, // Inicia en 0
                ticks: {
                    stepSize: 10 // Define el incremento de los ticks en el eje X
                }
            }
        }
    }
});
        });
        
</script>

 
 <footer class="page-footer font-small blue">
   <div class="footer-copyright text-center py-3">
     <strong>Copyright &copy; 1996-2024 <a href="http://Slic-Soluciones.com">Slic-Soluciones</a>.</strong>
     All rights reserved.
     <div class="d-none d-sm-inline-block">
       <b>Version</b> 1.0.0
     </div>       
   </div>
 </footer>
 
 <?php
   include('includes/libfooter.php');
 ?>
 </html>