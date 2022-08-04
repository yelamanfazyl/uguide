<?php 
    require $_SERVER['DOCUMENT_ROOT']."/utils/db.php";

    $univer_id=$_GET['univer_id'];
    $univer=R::findOne('univers', 'id=?', array($univer_id));   

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['update_univer'])) {
        $univer_id = $_GET['univer_id'];

        if(trim($univer_id) == ""){
            $errors[] = "Id неправильный";
        }

        $univer_name = $_GET['univer_name'];

        if((trim($univer_name) == "")){
            $errors[] = "Введите название курса";
        }
        
        $region_id = $_GET['region_id'];

        if((trim($region_id) == "")){
            $errors[] = "Введите название курса";
        }

        $country_id = $_GET['country_id'];

        if((trim($country_id) == "")){
            $errors[] = "Введите название курса";
        }

        if(empty($_FILES['fileToUpload'])){
            if(empty($errors)) {
                $univer=R::findOne('univers', 'id=?', array($univer_id));
                $univer->name = $univer_name;
                $univer->region_id = $region_id;
                $univer->country_id = $country_id;
                $univer->sat_score = $_GET['min_sat'];
                $univer->sat_essay = $_GET['sat_essay'];
                $univer->sat_subjects = $_GET['sat_subject'];
                $univer->act_score = $_GET['min_act'];
                $univer->min_ielts = $_GET['min_ielts'];
                $univer->min_toefl = $_GET['min_toefl'];
                $univer->avg_2nd_school_gpa = $_GET['min_gpa'];
                $univer->accept_rate = $_GET['min_accept_rate'];
                $univer->int_underg_perc = $_GET['int_underg_perc'];
                $univer->univer_ranking = $_GET['min_world_ranking'];
                $univer->apply_link = $_GET['apply_link'];
                $univer->additional = $_GET['additional'];
                $univer->application_due = $_GET['application_date'];

                if((!is_null($_GET['sat_read_write_25'])) && ($_GET['sat_read_write_25'] != "")){
                    $univer->sat_read_write_25 = $_GET['sat_read_write_25'];
                }
                if((!is_null($_GET['sat_read_write_75'])) && ($_GET['sat_read_write_75'] != "")){
                    $univer->sat_read_write_75 = $_GET['sat_read_write_75'];
                }
                if((!is_null($_GET['sat_math_25'])) && ($_GET['sat_math_25'] != "")){
                    $univer->sat_math_25 = $_GET['sat_math_25'];
                }
                if((!is_null($_GET['sat_math_75'])) && ($_GET['sat_math_75'] != "")){
                    $univer->sat_math_75 = $_GET['sat_math_75'];
                }
                if((!is_null($_GET['act_composite_25'])) && ($_GET['act_composite_25'] != "")){
                    $univer->act_composite_25 = $_GET['act_composite_25'];
                }
                if((!is_null($_GET['act_composite_75'])) && ($_GET['act_composite_75'] != "")){
                    $univer->act_composite_75 = $_GET['act_composite_75'];
                }
                if((!is_null($_GET['act_english_25'])) && ($_GET['act_english_25'] != "")){
                    $univer->act_english_25 = $_GET['act_english_25'];
                }
                if((!is_null($_GET['act_english_75'])) && ($_GET['act_english_75'] != "")){
                    $univer->act_english_75 = $_GET['act_english_75'];
                }
                if((!is_null($_GET['act_math_25'])) && ($_GET['act_math_25'] != "")){
                    $univer->act_english_75 = $_GET['act_english_75'];
                }
                if((!is_null($_GET['act_math_75'])) && ($_GET['act_math_75'] != "")){
                    $univer->act_english_75 = $_GET['act_english_75'];
                }
                R::store($univer);

                $majors = R::find("majors","univer_id = ?",array($univer->id));
                if($majors){
                    foreach($majors as $major){
                        $major1 = R::findOne("majors","id = ?",array($major->id));
                        $major1->name = $_GET[$major->id];
                        R::store($major1);
                    }
                }
                
                $success[] = "Университет был изменен";
            
            }
        } elseif (count($_FILES['fileToUpload']['name']) > 0) {
            $count = count($_FILES['fileToUpload']['name']);
            $filename = '';
        
            for($i=0; $i < $count; $i++){
                if(!empty($_FILES['fileToUpload']['name'][$i])){            
                    $target_dir = "uploads/univers/";
                    //Uploading files                    
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                                        
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    echo $imageFileType;
                    echo $target_file;
                    // Check if file already exists
                    $uploadOk = 1;
                    if (file_exists($target_file)) 
                    {
                        $errors[]="Файл с именем ". basename( $_FILES["fileToUpload"]["name"]). " уже сущесвует.";
                        $uploadOk = 0;
                    }
                    
                    if($imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "bmp" && $imageFileType != "jpg") {
                        $errors[]="Sorry, only JPEG, PNG, BMP & JPG files are allowed.";
                        $uploadOk = 0;
                    }
                }    
            }
            var_dump($uploadOk);
            //Checking that all data correct
            if ($uploadOk == 1) 
            {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
                {
                    try{
                        if(empty($errors)){
                            $univer=R::findOne('courses', 'id=?', array($univer_id));
                            $univer->name = $univer_name;
                            $univer->region_id = $region_id;
                            $univer->country_id = $country_id;
                            $univer->sat_score = $_GET['min_sat'];
                            $univer->sat_essay = $_GET['sat_essay'];
                            $univer->sat_subjects = $_GET['sat_subject'];
                            $univer->act_score = $_GET['min_act'];
                            $univer->min_ielts = $_GET['min_ielts'];
                            $univer->min_toefl = $_GET['min_toefl'];
                            $univer->avg_2nd_school_gpa = $_GET['min_gpa'];
                            $univer->accept_rate = $_GET['min_accept_rate'];
                            $univer->int_underg_perc = $_GET['int_underg_perc'];
                            $univer->univer_ranking = $_GET['min_world_ranking'];
                            $univer->apply_link = $_GET['apply_link'];
                            $univer->additional = $_GET['additional'];
                            $univer->application_due = $_GET['application_date'];
                            
                            if((!is_null($_GET['sat_read_write_25'])) && ($_GET['sat_read_write_25'] != "")){
                                $univer->sat_read_write_25 = $_GET['sat_read_write_25'];
                            }
                            if((!is_null($_GET['sat_read_write_75'])) && ($_GET['sat_read_write_75'] != "")){
                                $univer->sat_read_write_75 = $_GET['sat_read_write_75'];
                            }
                            if((!is_null($_GET['sat_math_25'])) && ($_GET['sat_math_25'] != "")){
                                $univer->sat_math_25 = $_GET['sat_math_25'];
                            }
                            if((!is_null($_GET['sat_math_75'])) && ($_GET['sat_math_75'] != "")){
                                $univer->sat_math_75 = $_GET['sat_math_75'];
                            }
                            if((!is_null($_GET['act_composite_25'])) && ($_GET['act_composite_25'] != "")){
                                $univer->act_composite_25 = $_GET['act_composite_25'];
                            }
                            if((!is_null($_GET['act_composite_75'])) && ($_GET['act_composite_75'] != "")){
                                $univer->act_composite_75 = $_GET['act_composite_75'];
                            }
                            if((!is_null($_GET['act_english_25'])) && ($_GET['act_english_25'] != "")){
                                $univer->act_english_25 = $_GET['act_english_25'];
                            }
                            if((!is_null($_GET['act_english_75'])) && ($_GET['act_english_75'] != "")){
                                $univer->act_english_75 = $_GET['act_english_75'];
                            }
                            if((!is_null($_GET['act_math_25'])) && ($_GET['act_math_25'] != "")){
                                $univer->act_english_75 = $_GET['act_english_75'];
                            }
                            if((!is_null($_GET['act_math_75'])) && ($_GET['act_math_75'] != "")){
                                $univer->act_english_75 = $_GET['act_english_75'];
                            }
                            R::store($univer);
                            
                            $majors = R::find("majors","univer_id = ?",array($univer->id));
                            if($majors){
                                foreach($majors as $major){
                                    $major1 = R::findOne("majors","id = ?",array($major->id));
                                    $major1->name = $_GET[$major->id];
                                    R::store($major1);
                                }
                            }

                            $success[] = "Университет был изменен";
                        }
                    } catch (\Throwable $th) {
                        throw $th;
                        die();                
                    }   
                } else 
                {
                    //Outputting error message
                    $errors[]="Произошла ошибка при загрузке файла ". basename( $_FILES["fileToUpload"]["name"]). "";
                }  
            }                 
        }   
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Изменить информацию об университетах</title>
    <link type="text/css" rel="stylesheet" href="/assets/libs/img_upload/image-uploader.min.css">
</head>
<body>
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
    <div class="container col-8 my-4">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="form-main" class="text-center" method="GET" enctype="multipart/form-data">
            <h3 class="my-3">Обновить университет</h3>
            <input type="hidden" name="univer_id" value="<?php echo $univer->id; ?>">
            <div class="form-group">
                <label>Название университета:</label>
                <input class="form-control" type="text" name="univer_name" value="<?php echo $univer->name; ?>">
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
                                    <option <?php if($_GET['region_id'] != "") { if($region['id'] == $_GET['region_id']){ echo 'selected'; } } elseif($region['id'] == $univer->region_id){ echo 'selected'; } ?> value="<?php echo $region['id'] ?>"><?php echo $region['name']; ?></option>
                                <?php }
                            }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <a href="/creates/create_region.php">
                        <button type="button" class="btn btn-sm text-center btn-success">Создать новый регион</button>
                    </a>
                </div>
            <?php 
            }   
            ?>
            <?php
            if($_GET['region_id'] != "") {
                $countries = R::find("countries", "region_id = ?", array($_GET['region_id']));
            } else {
                $countries = R::find("countries", "region_id = ?", array($univer->region_id));
            }

            if($countries){ 
            ?>
                <div class="form-group">
                    <label>Страны</label>
                    <select name="country_id" class="form-control" id="select-country">
                        <option value="">Выберите</option>
                        <?php 
                            if(isset($countries)){
                                foreach($countries as $country){ ?>
                                    <option <?php if($_GET['country_id'] != "") { if($country['id'] == $_GET['country_id']){ echo 'selected'; } } elseif($country['id'] == $univer->country_id){ echo 'selected'; } ?> value="<?php echo $country['id'] ?>"><?php echo $country['name']; ?></option>
                                <?php }
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <a href="/creates/create_сountry.php">
                        <button type="button" class="btn btn-sm text-center btn-success">Создать новую страну</button>
                    </a>
                </div>
            <?php 
            } 
            ?>
            <div class="form-group">
                <h5>Загрузите картинку</h5>
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>

            <div class="form-group">
                <label>Дедлайн подачи</label>
                <input type="date" name="application_date" class="form-control" value="<?php echo $univer->application_due; ?>">
            </div>
                    
            <div class="form-group">
                <label>Минимальный SAT</label>
                <input type="number" name="min_sat" class="form-control" value="<?php echo $univer->sat_score; ?>">
            </div>

            <div class="form-group">
                <label>Requirements for SAT Essay (leave blank if not required)</label>
                <textarea name="sat_essay" class="form-control"><?php $univer->sat_essay; ?></textarea>
            </div>

            <div class="form-group">
                <label>Requirements for SAT subject (leave blank if not required)</label>
                <textarea name="sat_subject" class="form-control"><?php $univer->sat_subjects; ?></textarea>
            </div>

            <div class="form-group">
                <label>Минимальный ACT</label>
                <input type="number" name="min_act" class="form-control" value="<?php echo $univer->act_score; ?>">
            </div>
            
            <div class="form-group">
                <label>Минимальный IELTS</label>
                <input type="number" name="min_ielts" step="0.5" class="form-control" value="<?php echo $univer->min_ielts; ?>">
            </div>

            <div class="form-group">
                <label>Минимальный TOEFL</label>
                <input type="number" name="min_toefl" class="form-control" value="<?php echo $univer->min_toefl; ?>">
            </div>

            <div class="form-group">
                <label>Минимальный GPA</label>
                <input type="number" name="min_gpa" step="0.01" class="form-control" value="<?php echo $univer->avg_2nd_school_gpa; ?>">
            </div>

            <div class="form-group">
                <label>Минимальный acceptance rate</label>
                <input type="number" name="min_accept_rate" step="0.1" class="form-control" value="<?php echo $univer->accept_rate; ?>">
            </div>

            <div class="form-group">
                <label>Минимальное место в рейтинге университетов</label>
                <input type="number" name="min_world_ranking" class="form-control" value="<?php echo $univer->univer_ranking; ?>">
            </div>

            <div class="form-group">
                <label>Процент иностранных студентов</label>
                <input type="number" name="int_underg_perc" step="0.1" class="form-control" value="<?php echo $univer->int_underg_perc; ?>">
            </div>

            <div class="form-group">
                <label>Ссылка на сайт:</label>
                <input class="form-control" type="text" name="apply_link" value="<?php echo $univer->apply_link; ?>">
            </div>
            
            <div class="form-group">
                <label>Дополнительная информация:</label>
                <textarea class="form-control" name="additional"><?php echo $univer->additional; ?></textarea>
            </div>

            <?php if((!is_null($univer->sat_read_write_25)) && ($univer->sat_read_write_25 != "")){ ?>
                <div class="form-group">
                    <label>SAT Evidence-Based Reading and Writing 25th percentile score (ADM2019):</label>
                    <input type="number" name="sat_read_write_25" class="form-control" value="<?php echo $univer->sat_read_write_25; ?>">
                </div>
            <?php } ?>

            <?php if((!is_null($univer->sat_read_write_75)) && ($univer->sat_read_write_75 != "")){ ?>
                <div class="form-group">
                    <label>SAT Evidence-Based Reading and Writing 75th percentile score (ADM2019):</label>
                    <input type="number" name="sat_read_write_75" class="form-control" value="<?php echo $univer->sat_read_write_75; ?>">
                </div>
            <?php } ?>

            <?php if((!is_null($univer->sat_math_25)) && ($univer->sat_math_25 != "")){ ?>
                <div class="form-group">
                    <label>SAT Math 25th percentile score (ADM2019):</label>
                    <input type="number" name="sat_math_25" class="form-control" value="<?php echo $univer->sat_math_25; ?>">
                </div>
            <?php } ?>

            <?php if((!is_null($univer->sat_math_75)) && ($univer->sat_math_75 != "")){ ?>
                <div class="form-group">
                    <label>SAT Math 75th percentile score (ADM2019):</label>
                    <input type="number" name="sat_math_75" class="form-control" value="<?php echo $univer->sat_math_75; ?>">
                </div>
            <?php } ?>

            <?php if((!is_null($univer->act_composite_25)) && ($univer->act_composite_25 != "")){ ?>
                <div class="form-group">
                    <label>ACT Composite 25th percentile score (ADM2019):</label>
                    <input type="number" name="act_composite_25" class="form-control" value="<?php echo $univer->act_composite_25; ?>">
                </div>
            <?php } ?>
            
            <?php if((!is_null($univer->act_composite_75)) && ($univer->act_composite_75 != "")){ ?>
                <div class="form-group">
                    <label>ACT Composite 75th percentile score (ADM2019):</label>
                    <input type="number" name="act_composite_75" class="form-control" value="<?php echo $univer->act_composite_75; ?>">
                </div>
            <?php } ?>
            
            <?php if((!is_null($univer->act_english_25)) && ($univer->act_english_25 != "")){ ?>
                <div class="form-group">
                    <label>ACT English 25th percentile score (ADM2019):</label>
                    <input type="number" name="act_english_25" class="form-control" value="<?php echo $univer->act_english_25; ?>">
                </div>
            <?php } ?>
            
            <?php if((!is_null($univer->act_english_75)) && ($univer->act_english_75 != "")){ ?>
                <div class="form-group">
                    <label>ACT English 75th percentile score (ADM2019):</label>
                    <input type="number" name="act_english_75" class="form-control" value="<?php echo $univer->act_english_75; ?>">
                </div>
            <?php } ?>
            
            <?php if((!is_null($univer->act_english_75)) && ($univer->act_english_75 != "")){ ?>
                <div class="form-group">
                    <label>ACT English 75th percentile score (ADM2019):</label>
                    <input type="number" name="act_english_75" class="form-control" value="<?php echo $univer->act_english_75; ?>">
                </div>
            <?php } ?>
            
            <?php if((!is_null($univer->act_math_25)) && ($univer->act_math_25 != "")){ ?>
                <div class="form-group">
                    <label>ACT Math 25th percentile score (ADM2019):</label>
                    <input type="number" name="act_math_25" class="form-control" value="<?php echo $univer->act_math_25; ?>">
                </div>
            <?php } ?>
            
            <?php if((!is_null($univer->act_math_75)) && ($univer->act_math_75 != "")){ ?>
                <div class="form-group">
                    <label>ACT Math 75th percentile score (ADM2019):</label>
                    <input type="number" name="act_math_75" class="form-control" value="<?php echo $univer->act_math_75; ?>">
                </div>
            <?php } ?>
            <div class="form-group">
                <button class="btn btn-sm btn-info" type="button" id="majors_button">Show majors</button>
            </div>
            <div id="majors" style="display: none">
                <div class="form-group">
                    <h4>Majors:</h4>
                </div>

                <?php
                    $majors = R::find("majors","univer_id = ?",array($univer->id));
                    foreach($majors as $major){ 
                ?>
                    
                    <div class="form-group">
                        <input type="text" name="<?php echo $major->id; ?>" class="form-control" value="<?php echo $major->name; ?>">
                    </div>
                
                <?php } ?>
            </div>

            <div class="form-group">
                <button class="btn btn-lg btn-success w-50" type="submit" name="update_univer">Изменить универ</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $('#majors_button').click(function (e) {
            e.preventDefault();
            $('#majors').toggle('show');
        });

        $('#select-region').change(function (e) { 
            e.preventDefault();
            
            $('#select-country').val('');

            $('#form-main').submit();
        });

        $('#select-country').change(function (e) { 
            e.preventDefault();
            
            $('#select-univer').val('');

            $('#form-main').submit();
        });
    </script>
</body>
</html>