<?php
    include('konekcija.php');

    if (isset($_POST['key'])) {

        if($_POST['key'] == 'ubaci'){
            
            $pr_id = $_POST['pr_id'];
            $naziv = $_POST['naziv'];
            $model = $_POST['model'];
            $mestoProizvodnje = $_POST['mesto_proizvodnje'];
            $cena = $_POST['cena'];
            
            $check = mysqli_query($conn,"SELECT * FROM proizvodi WHERE pr_id = '$pr_id'");
    
            if(mysqli_num_rows($check) > 0) {
                echo "Isti model " . $model . ", već postoji!";
            } else {
            
                $sql="INSERT INTO `proizvodi` (`pr_id`,`naziv`, `model`, `mesto_proizvodnje`,`cena`,`zap_id`) VALUES ('$pr_id','$naziv', '$model', '$mestoProizvodnje', '$cena', 1)";
            
                if ($conn->query($sql) === TRUE) {
                    echo "Ubacen novi proizvod!";
                }
                else 
                {
                    echo "Takav proizvod vec postoji!";
                }
            }
        }


        if($_POST['key'] == 'ucitaj'){
    
            $start = $conn->real_escape_string($_POST['start']);
            $limit = $conn->real_escape_string($_POST['limit']);
            $sort = $conn->real_escape_string($_POST['sort']);
            $sql = $conn->query("SELECT pr_id, naziv, model, mesto_proizvodnje, cena FROM proizvodi ORDER BY $sort LIMIT $start, $limit");

            if ($sql->num_rows > 0) {
                $response = "";
                while($data = $sql->fetch_array()) {
    
                    $response .= '
                                <tr>
                                    <td id="proizvod_'.$data["pr_id"].'">'.$data["pr_id"].'</td>
                                    <td>'.$data["naziv"].'</td>
                                    <td>'.$data["model"].'</td>
                                    <td>'.$data["mesto_proizvodnje"].'</td>
                                    <td>'.$data["cena"].'</td>
                                    <td>
                                        <input type="button" onclick="izmeniPogledaj('.$data["pr_id"].', \'izmeni\')" value="Izmeni" class="btn btn-primary">
                                        <input type="button" onclick="izmeniPogledaj('.$data["pr_id"].', \'pogledaj\')" value="Pogledaj" class="btn btn-warning">
                                        <input type="button" onclick="izbrisi('.$data["pr_id"].')" value="Izbriši" class="btn btn-danger">
                                    </td>
                                </tr>
                            ';
                    }
                    exit($response);
                    } else
                        exit('reachedMax');	
        }


        
        if ($_POST['key'] == 'izbrisi') {
            $pr_id = $conn->real_escape_string($_POST['pr_id']);
            $conn->query("DELETE FROM proizvodi WHERE pr_id='$pr_id'");
            exit('Model proizvoda obrisan!');
        }

        if($_POST['key'] == 'uzmiPodatke'){
            $pr_id = $conn->real_escape_string($_POST['pr_id']);
            $sql = $conn->query("SELECT pr_id , naziv, model, mesto_proizvodnje, cena FROM proizvodi WHERE pr_id = $pr_id");
            $data = $sql->fetch_array();
            $jsonArray = array(
                'pr_id'=>$data['pr_id'],
                'naziv'=>$data['naziv'],
                'model'=>$data['model'],
                'mesto_proizvodnje'=>$data['mesto_proizvodnje'],
                'cena'=>$data['cena']
            );
            exit(json_encode($jsonArray));
        }
            

        if ($_POST['key'] == 'izmeni') {

            $trenutni_red = $conn->real_escape_string($_POST['pr_id']);

                $pr_id = $_POST['pr_id'];
                $naziv = $_POST['naziv'];
                $model = $_POST['model'];
                $mesto_proizvodnje=$_POST['mesto_proizvodnje'];
                $cena=$_POST['cena'];
                
            
                $sql="UPDATE proizvodi SET naziv='$naziv', model='$model', mesto_proizvodnje='$mesto_proizvodnje', cena='$cena' WHERE pr_id='$trenutni_red'";
                if ($conn->query($sql) === TRUE) {
                    echo "Uspešno izmenjen proizvod!";
                }
                else 
                {
                    echo "Proizvod nije izmenjen!";
                }
            
            }
        }
    
        mysqli_close($conn);
    
?>