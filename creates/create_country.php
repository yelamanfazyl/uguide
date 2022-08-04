<?php 
    require $_SERVER['DOCUMENT_ROOT']."/utils/db.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_country'])) {
    
        $country_name = $_POST['country_name'];
        $region_id = $_POST['region_id'];

        if((trim($country_name) == "")){
            $errors[] = "Введите название страны";
        }
        
        $rep_country = R::find("countries","name = ?", array($country_name));
        if($rep_country){
            $errors[] = "Страна с таким именем уже существует";
        }

        if(($region_id == "") || (empty($region_id))){
            $errors[] = "Регион не был выбран";
        }

        try{
            if(empty($errors)){
                $country = R::dispense("countries");
                $country->name = $country_name;
                $country->region_id = $region_id;
                
                R::store($country);

                $success[] = "Страна была создана";
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
    <title>Adding new country</title>
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
                <h2 class='text-center my-2'>Создать новую страну</h2>
            </div>
            
            <div class="form-group">
                <input type="text" class="form-control" name="country_name" placeholder="Название страны">
            </div>

            <?php 
                $regions = R::findAll("regions");
                if($regions){
            ?>
                <div class="form-group">
                    <label>Регион</label>
                    <select name="region_id" class="form-control" id="select-region">
                        <option value="">Выберите</option>
                        <?php 
                            if(isset($regions)){
                                foreach($regions as $region){ ?>
                                    <option <?php if($_GET['region_id'] != "") { if($region['id'] == $_GET['region_id']){ echo 'selected'; } } ?> value="<?php echo $region['id'] ?>"><?php echo $region['name']; ?></option>
                                <?php }
                            }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <a href="/create_region.php">
                        <button type="button" class="btn btn-sm text-center btn-success">Создать новый регион</button>
                    </a>
                </div>
            <?php 
            }   
            ?>

            <div class="form-group">
                <button class="btn btn-lg btn-success w-50" type="submit" name="create_country">Создать страну</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</body>
</html>