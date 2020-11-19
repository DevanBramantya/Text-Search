<?php
    include "header.php";
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
        <div class="container" style="margin-bottom: -10%;">
            <div class="row">
                <form action="" method="POST">
                    <div class="col-md-12 mx-auto">
                        <h1 class="text-center" id="judul">Rereh Artikel</h1> 
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="keyword" placeholder="Cari Kata" required>
                        </div>
                        <br>
                        <p class="text-center">Masukkan Dokumen Yang Digunakan :
                            <br><small>Contoh : Jika ingin menggunakan dokumen 1 - 50 maka masukkan 1 dan 50</small>
                        </p>
                        <div class="form-row mb-2">
                            <div class="form-group col-md-6">
                            <input type="text" placeholder="Masukan Angka" class="form-control" name="awal" required>
                            </div>
                            <div class="form-group col-md-6">
                            <input type="text" placeholder="Masukan Angka"  class="form-control" name="akhir" required>
                            </div>
                        </div>
                        <button type="submit"  name="submit" class="btn mx-auto d-block">CARI ARTIKEL</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="list_index">
            <?php 
                if(isset($_POST['submit'])){
                    //Memecah keyword per kata
                    $keyword=explode(" ",$_POST['keyword']);
                    $awal=$_POST['awal'];
                    $akhir=$_POST['akhir'];
                    for($i=0;$i<count($keyword);$i++){
                        //Keyword dipecah lagi perhuruf
                        $keyword[$i]=str_split(strtolower($keyword[$i]));
                    }
                    $nfa=array();
                    $final=array();
                    create_nfa($keyword,$nfa,$final);
                    quintuple($final);
                    tabel_transisi($final,$keyword,$nfa);
                    $state_hasil=array();
                    $state_hasil[0]=0;
                    for($i=$awal-1;$i<$akhir;$i++){
                        cek_file($nama_file[$i],$final,$state_hasil,$keyword,$nfa,$isi[$i],$i+1);
                    }   
                }
            ?>
        </div>
    </body>
</html>
