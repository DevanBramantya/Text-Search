<?php
    include "koneksi.php";
    $nama_file=array();
    $isi=array();
    $sql=mysqli_query($conn,"select * from artikel");
    //Masukkan semua data nama file dan isi artikel ke dalam array nama_file dan isi
    while($data=mysqli_fetch_array($sql)){
        array_push($nama_file,$data['judul']);
        array_push($isi,$data['isi']);
    }
    //function untuk delta topi
    function deltatopi(&$state_hasil,$char,$nfa){
        $temp_state=array();
        for($i=0;$i<count($state_hasil);$i++){
            //Kondisi jika di state hasil terdapat q0 maka masukkan q0 ke state hasil, karena delta(q0,sigma)=q0
            if($state_hasil[$i]==0){
                array_push($temp_state,0);
            }
            //panggil function delta
            $state_delta=delta($state_hasil[$i],$char,$nfa);
            //jika tidak stuck, maka masukkan state hasil delta tersebut ke array temp_state
            if($state_delta!=-1){
                array_push($temp_state,$state_delta);
            }
        }
        //hilangkan duplikat state
        array_unique($temp_state);
        $state_hasil=array();
        $state_hasil=$temp_state;
    }

    //function untuk delta
    function delta($state,$char,$nfa){
        if(array_key_exists($state,$nfa)){
            //cek apakah state tersebut dapat menerima inputan karakter tersebut(tidak stuck)
            if(array_key_exists($char,$nfa[$state])){
                //return hasil delta
                return $nfa[$state][$char];
            }
        }
        //jika stuck maka return -1
        return -1;
    }


    //create tabel transisi nfa
    function create_nfa($keyword,&$nfa,&$final){
        $z=0;
        for($i=0;$i<count($keyword);$i++){
            $c=false;
            for($j=0;$j<count($keyword[$i]);$j++){
                if($i==0||$c==true){
                    $nfa[$z][$keyword[$i][$j]]=$z+1;
                    $z++;
                }else{
                    if(!array_key_exists($keyword[$i][$j],$nfa[$j])){
                        $nfa[$j][$keyword[$i][$j]]=$z;
                        $c=true;
                    }
                }
                //Masukkan final state ke array final
                if($j==count($keyword[$i])-1){
                    array_push($final,$z);
                }
            }
            $z++;
        }
    }


    //menampilkan quintuple nfa
    function quintuple($final){
        echo "Quintuple = (";
        echo " { ";
        for($i=0;$i<=end($final);$i++){
            echo "q";
            echo "<sub>".$i."</sub>";
            if($i!=end($final)){
                echo ",";
            }
        }
        echo "}, &Sigma;, &delta;, q<sub>0</sub>, {";
        for($i=0;$i<count($final);$i++){
            echo "q";
            echo "<sub>".$final[$i]."</sub>";
            if($i!=count($final)-1){
                echo ",";
            }
        }
        echo "} ) <br>";
    }


    //menampilkan tabel transisi
    function tabel_transisi($final,$keyword,$nfa){
        echo "Tabel Transisi";
        $index_final=0;
        ?>
        <table border="1">
            <?php
                for($i=0;$i<=end($final);$i++){
                    echo "<tr>";
                    if($i==0){
                        echo "<td> &rarr;q<sub>".$i."</sub> </td>";
                        echo "<td>&Sigma;=>{q<sub>0</sub>}</td>";
                        for($j=0;$j<count($keyword);$j++){
                            $alpha=array_keys($nfa[$i]);
                            if(array_key_exists($j,$alpha)){
                                echo "<td>".$alpha[$j]."=>{q<sub>".$nfa[$i][$alpha[$j]]."</sub>}</td>";
                            }else{
                                echo "<td></td>";
                            }
                        }
                    }else{
                        if($i==$final[$index_final]){
                            echo "<td>*q<sub>".$i."</sub></td>";
                            $index_final++;
                        }else{
                            echo "<td>q<sub>".$i."</sub></td>";
                        }
                        for($j=0;$j<=count($keyword);$j++){
                            if(array_key_exists($i,$nfa)){
                                $alpha=array_keys($nfa[$i]);
                                if(array_key_exists($j,$alpha)){
                                    echo "<td>".$alpha[$j]."=>{q<sub>".$nfa[$i][$alpha[$j]]."</sub>}</td>";
                                }else{
                                    echo "<td></td>";
                                }
                            }else{
                                if($j==0){
                                    echo "<td>&Sigma;=>{}</td>";
                                }else{
                                    echo "<td></td>";
                                }
                            }
                        }
                    }
                    echo "</tr>";
                }
            ?>
        </table>
        <?php
    }

    
    //proses pengecekan file dokumen
    function cek_file($nama_file,$final,$state_hasil,$keyword,$nfa,$isi,$kode){
        //jika keyword sudah ditemukan maka end akan bernilai true dan pengecekan dokumen akan dihentikan
        $end=false;
        $index_kata=0;
        //memecah isi artikel per kata untuk snippet
        $content=explode(" ",$isi);
        for($i=0;$i<strlen($isi);$i++){
            $char=$isi[$i];
            if($char==" "){
                $index_kata++;
            }
            deltatopi($state_hasil,strtolower($char),$nfa);
            //pengecekan final state
            for($j=0;$j<count($final);$j++){
                //kondisi jika keyword sudah ditemukan
                if(in_array($final[$j],$state_hasil)){
                    //variabel untuk menyimpan kata yang sedang di cek sekarang
                    $string_cek=strtolower($content[$index_kata]);
                    //hilangkan simbol-simbol selain alphabet dan angka di string
                    $string_cek=preg_replace("/[^A-Za-z0-9 ]/", '', $string_cek);
                    //variabel untuk keyword yang sedang di periksa
                    $keyword_cek=join($keyword[$j]);
                    //kondisi untuk mengecek karakter selanjutnya adalah bukan alphabet dan kondisi untuk mengecek karakter sebelumnya adalah bukan alphabet
                    if(!ctype_alpha($isi[$i+1]) && strpos($string_cek,$keyword_cek) === 0){
                        //menampilkan nama file
                        echo "<a href='artikel.php?artikel=$kode'>".$nama_file."</a>";
                        echo "<br>";
                        $index_awal=$index_kata-10;
                        if($index_awal<0){
                            $index_awal=0;
                        }
                        //tampilkan snippet sebanyak 20 kata
                        for($j=$index_awal;$j<$index_awal+20;$j++){
                            //Bold untuk keyword
                            if($j==$index_kata){
                                echo "<b>$content[$j]</b>";
                            }else if(array_key_exists($j,$content)){
                                print($content[$j]);
                            }
                            print(" ");
                        }
                        echo "<br><br>";
                        $end=true;
                        break;
                    }
                }
            }
            //jika variabel end bernilai true maka pengecekan file di hentikan
            if($end==true){
                break;
            }
        }
    }
?>