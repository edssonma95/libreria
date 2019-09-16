<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>TRCBooks - Administrator</title>

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">

</head>

<body id="page-top">

  <div id="app">
    
    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
      <a class="navbar-brand mr-1" href="index.html">TRCBooks Administrator</a>
    </nav>

    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="sidebar navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.html">
            <span>Libros</span>
          </a>
        </li>
        <li class="nav-item active" style="position:absolute; bottom: 0;">
          <a class="nav-link" href="logout">
            <span>Cerrar sesión</span>
          </a>
        </li>
      </ul>

      <div id="content-wrapper">

        <div class="container-fluid">
          <book-list context="admin"></book-list>
        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © TRCBooks 2019</span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="https://kit.fontawesome.com/10d46139ed.js"></script>
  <script src="{{ URL::asset('/js/app.js') }}"></script>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>


</body>

</html>
