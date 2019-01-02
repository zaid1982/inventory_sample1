<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Inventory System</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Material Design Bootstrap -->
        <link rel="stylesheet" href="css/mdb.min.css">
        <!-- Your custom styles (optional) -->
        <link href="css/style.css" rel="stylesheet">

        <!-- Your custom styles (optional) -->
        <style>

        </style>

        <!-- JQuery -->
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    </head>

    <body class="fixed-sn white-skin">

        <!--Main Navigation-->
        <header>
            <!-- Sidebar navigation -->
            <?php include 'html/nav_left.html'; ?>
            <!--/. Sidebar navigation -->

            <!-- Navbar -->
            <?php include 'html/nav_top.html'; ?>
            <!-- /.Navbar -->
        </header>
        <!--Main Navigation-->

        <!--Main layout-->
        <main>
            <div class="container-fluid">

            </div>
        </main>
        <!--Main layout-->

        <!-- SCRIPTS -->
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="js/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="js/mdb.min.js"></script>
        <!--Custom scripts-->
        <script type="text/javascript">

            document.write('<scr' + 'ipt src="js/common.js?' + new Date().valueOf() + '" type="text/javascript"></scr' + 'ipt>');

            document.addEventListener('DOMContentLoaded', function () {

                ShowLoader();
                setTimeout(function () {
                    initiatePages();

                    HideLoader();
                }, 300);
            });

        </script>
    </body>

</html>
