<?php 
$admin_user= "Rohit";
$admin_password= "papaJI";

session_start();
$connection = mysqli_connect("localhost","root","","testdatbase");
// $connection = mysqli_connect("sql210.infinityfree.com","if0_37405719","UX0hrNoIbFnTIid","if0_37405719_wp302");


function createInput($type,$name){
    if(strpos($type,"int")!==false){
        return "<input class='form-control' type=\"number\" name=\"$name\" id=\"$name\">";
    }
    else{
         return "<input  class='form-control' type=\"text\" name=\"$name\" id=\"$name\">";
    }
}


if(!isset($_SESSION["USER"]))
{
    if(isset($_POST['user'])){
        $user = $_POST['user'];
        $password = $_POST['password'];
        if($user==$admin_user && $password=$admin_password){
            $_SESSION['MSG']='<div class="alert alert-warning" role="alert">ADMIN LOGIN SUCCESSFUL</div>';
            $_SESSION['USER']="true";
        }
        
    }

    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <main class="bg-dark d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <form class="bg-light p-4 rounded " style="min-width: 30vw" action="" method="post">
            <h1 class="text-center text-danger">Login ADMIN</h1>
            <hr>
            <div class="form-group my-2"><input type="text" placeholder="Enter Username" name="user" id="user" class="form-control"></div>
            <div class="form-group my-2"><input type="text" name="password" placeholder="Enter Password" id="password" class="form-control"></div>
            <button type="submit" class="btn btn-dark w-100">Login</button>
        </form>
    </main>
    
    <?php
    die();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOCAL BASE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    
    <div class="container my-3">
        <h1 class="text-center text-primary">DATABASE LOCAL</h1>
        <form action="" method="post" class="d-flex">
            <input placeholder="Enter Sql Queary" type="text" name="qry" id="qry" class="form-control">
            <button type="submit" class="btn btn-danger">Send</button>
        </form>
        <div class="qrymsg py-4">
            <?php 
                if(isset($_SESSION['MSG'])){
                    echo $_SESSION['MSG'];
                    unset($_SESSION['MSG']);
                }
            ?>
        </div>

        <table class="table">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>View</th>
                <th>Delete</th>
            </tr>
            <?php 
                $query = mysqli_query($connection,"SHOW TABLES");
                $i = 0;
                $tables = mysqli_fetch_all($query);
                for ($i=0; $i < mysqli_num_rows($query); $i++) {                    
                
                    ?>
            
            <tr>
                <td><?=$i+1?></td>
                <td><?=$tables[$i][0]?></td>
                <td><a href="?table=<?=$tables[$i][0]?>" class="btn btn-warning">View</a></td>
                <td><a href="/delete.php?talbe=<?=$tables[$i][0]?>" class="btn btn-danger">Delete</a></td>
            </tr>
                    <?php
                }
            ?>
        </table>


        <?php
        // SHOW COLUMNS FROM users; 
            if(isset($_GET['table'])){
                $table = $_GET['table'];
                $colums_queary = mysqli_query($connection, "SHOW COLUMNS FROM $table;");
                $items_queary = mysqli_query($connection,"SELECT * FROM `$table` WHERE 1 LIMIT 0,50");
                ?>

        <hr>
        <h1 class="display-3 text-warning"><?=$table?></h1>
        <div class="d-flex justify-content-end">
            <form action="" class="d-flex" method="get">
                <input type="text" name="table" id="table" value="<?=$table?>" class="d-none">
                <input type="search" placeholder="Search Item" name="search" id="search" class="form-control">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <table class="table">
            <tr>                
                <?php 
                    $colums = array(); 
                    $DetailsTableArray= array();
                    while($row = mysqli_fetch_assoc($colums_queary)){
                        array_push($colums,$row['Field']);
                        array_push($DetailsTableArray,$row);

                        ?>
                <th><?=$row['Field']?></th>
                        <?php
                    }
                ?>
            </tr>
            <?php 
                if(isset($_GET['search'])){
                    $searchValue = $_GET['search'];     
                    $items_queary = "SELECT * FROM `$table` WHERE ";
                    foreach ($colums as $key) {
                        $items_queary .="`$key` LIKE '%$searchValue%' ";
                        if($key == $colums[count($colums)-1]){
                            break;
                        }
                        $items_queary .="OR ";
                    }
                    $items_queary .="LIMIT 0,10";
                    // echo $items_queary;
                    $items_queary = mysqli_query($connection,$items_queary);
                }

                $items = mysqli_fetch_all($items_queary);
                for ($i=0; $i < mysqli_num_rows($items_queary); $i++) { 
                    $len = count($items[$i]);
                   
                
            ?>
                <tr>
                    <?php 
                     for ($j=0; $j < $len; $j++) { 
                        $val = $items[$i][$j];
                        echo "<td>$val</td>";
                    }                    
                    ?>
                </tr>
            <?php 
                }
            ?>
        

        </table>
            <hr>
            <h2 class="text-danger">Insert Item</h2>
            <form action="" method="post">
                <div class="row">
                    <?php 
                        foreach ($DetailsTableArray as $key) {
                            ?>
                    <div class="col-md-6">
                        <label for="<?=$key["Field"]?>"><?=$key["Field"]?></label>
                        <?php 
                            echo createInput($key["Type"],$key["Field"]);
                        ?>
                    </div>
                            <?php
                        }
                    ?>
                    
                </div>
                <div class="my-4"></div>
                <button type="submit" class="btn btn-dark w-100">Insert</button>
            </form>


                <?php 
            }
        
        ?>



    </div>


</body>
</html>