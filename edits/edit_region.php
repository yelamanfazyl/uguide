<?php 
    require $_SERVER['DOCUMENT_ROOT']."/utils/db.php";

    $region_id = $_GET['region_id'];

    $region = R::load("regions",$region_id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_region'])) {
    
        $region_name = $_POST['region_name'];
        $region_id = $_POST['region_id'];

        if((trim($region_name) == "")){
            $errors[] = "Введите название региона";
        }
        
        $rep_region = R::find("regions","name = ?", array($region_name));
        if($rep_region){
            $errors[] = "Регион с таким именем уже существует";
        }

        if((empty($region_id)) || ($region_id == "")){
            $errors[] = "Регион не был найден";
        }

        try{
            if(empty($errors)){
                $region = R::load("regions",$region_id);
                $region->name = $region_name;
                
                R::store($region);

                $success[] = "Регион был изменен";
            }
        } catch (\Throwable $th) {
            throw $th;
            die();                
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editing region</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="/assets/libs/img_upload/image-uploader.min.css">
</head>
<body>
    <div class="container col-8 my-4">
        <?php 
            if(!empty($errors)){ ?>
            <div class="col-12 bg-white py-1 mb-5">
                <h2 class="text-center text-danger">
                    <?php  
                        echo array_shift($errors);
                    ?>
                </h2>
            </div>
        <?php
            }
        ?>

        <?php 
            if(!empty($success)){ ?>
            <div class="col-12 bg-white py-1 mb-5">
                <h2 class="text-center text-success">
                    <?php  
                        echo array_shift($success);
                    ?>
                </h2>
            </div>
        <?php
            }
        ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="text-center" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <h2 class='text-center my-2'>Изменить регион</h2>
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="region_name" placeholder="Название региона" value="<?php echo $region->name; ?>">
            </div>

            <div class="form-group">
                <input type="hidden" name="region_id" value="<?php echo $region->id; ?>">
                <button class="btn btn-lg btn-success w-50" type="submit" name="edit_region">Изменить регион</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</body>
</html>