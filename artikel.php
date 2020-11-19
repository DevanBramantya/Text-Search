<?php 
    include "koneksi.php";
    $kode=$_GET['artikel'];
    $sql=mysqli_query($conn,"select * from artikel where kode='$kode'");
    $data=mysqli_fetch_array($sql);
?>

<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- Fontawesome-->
        <script src="https://kit.fontawesome.com/ac1ee11f2c.js" crossorigin="anonymous"></script>

        <!-- Goggle Font -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Leckerli+One&family=Open+Sans&display=swap" rel="stylesheet"> 
        <link rel="icon" href="assets/img/desktop-computer-screen-with-magnifying-glass-and-list.svg">
        <title>Application of Finite Automata in Text Search</title>
    </head>
    <body>
        <nav class="navbar navbar-light bg-white mb-3">
            <a class="navbar-brand" href="index.php">
                <h1 id="judul_artikel">Rereh Artikel</h1>
            </a>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow rounded">
                        <div class="card-header bg-white">
                            <h3><?php echo $data['judul'];?></h3>
                        </div>
                        <div class="card-body text-justify">
                            <blockquote class="blockquote mb-0">
                                <?php echo $data['isi'];?>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
