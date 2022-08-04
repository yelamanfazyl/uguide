<?php 
    require $_SERVER['DOCUMENT_ROOT']."/utils/db.php";
    // error_reporting(0);
    if($_GET['page'] == ""){
        $number = 0;
    } else {
        $number = $_GET['page'];
    }
    
    $sql = "SELECT * FROM univers";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $name = $_GET['name'];
        $region = $_GET['region_id'];
        $country = $_GET['country_id'];
        $deadline = $_GET['application_date'];
        $sat = $_GET['min_sat'];
        $sat_essay = $_GET['sat_essay'];
        $sat_subject = $_GET['sat_subject'];
        $act = $_GET['min_act'];
        $ielts = $_GET['min_ielts'];
        $toefl = $_GET['min_toefl'];
        $gpa = $_GET['min_gpa'];
        $acceptance_rate = $_GET['min_accept_rate'];
        $ranking = $_GET['min_world_ranking'];
        $int_perc = $_GET['int_underg_perc'];
        
        $conditions = array();
        
        // echo $deadline . "<br>" ;

        if(!empty($name)){
            $conditions[] = "(name LIKE '%" . $name . "%')"; 
        }
        if(!empty($region)){
            $conditions[] = "(region_id =" . $region . ")"; 
        }
        if(!empty($country)){
            $conditions[] = "(country_id =" . $country . ")"; 
        }
        if(!empty($deadline)){
            $conditions[] = "(DATEDIFF(application_due,\"" . $deadline . "\") >= 0)"; 
        }
        if(!empty($sat)){
            $conditions[] = "(sat_score <=" . $sat . " OR sat_score IS NULL)"; 
        }
        if(!empty($act)){
            $conditions[] = "(act_score <=" . $act . " OR act_score IS NULL)"; 
        }
        if(!empty($ielts)){
            $conditions[] = "(min_ielts <=" . $ielts . " OR min_ielts IS NULL)"; 
        }
        if(!empty($toefl)){
            $conditions[] = "(min_toefl <=" . $toefl . " OR min_toefl IS NULL)"; 
        }
        if(!empty($gpa)){
            $conditions[] = "(avg_2nd_school_gpa <=" . $gpa . " OR avg_2nd_school_gpa IS NULL)"; 
        }
        if(!empty($acceptance_rate)){
            $conditions[] = "(accept_rate >=" . $acceptance_rate . " OR accept_rate IS NULL)"; 
        }
        if(!empty($ranking)){
            $conditions[] = "(univer_ranking <=" . $ranking . " OR univer_ranking IS NULL)"; 
        }
        if(!empty($int_perc)){
            $conditions[] = "(int_underg_perc >=" . $int_perc . " OR int_underg_perc IS NULL)"; 
        }
        if($sat_essay){
            $conditions[] = "(sat_essay IS NOT NULL)"; 
        }
        if($sat_subject){
            $conditions[] = "(sat_subjects IS NOT NULL)"; 
        }
        
        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
    }

    $h = $number*4;
    $sql1 = $sql . " LIMIT " . $h . ", 4";

    if(($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST["deletebutt"])))
    {
        $univer_id = $_POST['univer_id'];
        $univer = R::findOne("univers","id = ?",array($univer_id));
        if($univer){
            $majors=R::find('majors', 'univer_id=?', array($univer_id));
            if($majors){
                foreach($majors as $major){
                    R::trash($major);
                }
            }
            R::trash($univer);
        }
    }
    echo $sql1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Поиск университетов</title>
</head>
<body>
    <div class="container">
        <div class="container col-12 my-4">
            <h2 class="text-center">Поиск</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get" id="form-main">
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
                                        <option <?php if($region['id'] == $_GET['region_id']){ echo 'selected'; } ?> value="<?php echo $region['id'] ?>"><?php echo $region['name']; ?></option>
                                    <?php }
                                }
                            ?>
                        </select>
                    </div>
                <?php 
                }   
                ?>

                <?php 
                $countries = R::find("countries", "region_id = ?", array($_GET['region_id']));
                if($countries){ 
                ?>
                    <div class="form-group">
                        <label>Страны</label>
                        <select name="country_id" class="form-control" id="select-country">
                            <option value="">Выберите</option>
                            <?php 
                                if(isset($countries)){
                                    foreach($countries as $country){ ?>
                                        <option <?php if($country['id'] == $_GET['country_id']){ echo 'selected'; } ?> value="<?php echo $country['id'] ?>"><?php echo $country['name']; ?></option>
                                    <?php }
                                }
                            ?>
                        </select>
                    </div>
                <?php 
                } 
                ?>
                
                <div class="form-group">
                    <label>Название университета</label>
                    <input type="text" name="name" class="form-control" value="<?php echo @$_GET['name']; ?>">
                </div>

                <div class="form-group">
                    <button class="btn btn-sm btn-info" type="button" id="filters_button">More filters</button>
                </div>

                <div id="filters_div" style="display: none">

                    <div class="form-group">
                        <label>Дедлайн подачи</label>
                        <input type="date" name="application_date" class="form-control" value="<?php echo @$_GET['application_date']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Минимальный SAT</label>
                        <input type="number" name="min_sat" class="form-control" value="<?php echo @$_GET['min_sat']; ?>">
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="sat_essay[]" class="form-check-input" <?php if ($_GET['sat_essay']) { echo "checked"; } ?>>
                            <label class="form-check-label">Requires SAT Essay</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="sat_subject[]" class="form-check-input" <?php if ($_GET['sat_subject']) { echo "checked"; } ?>>
                            <label class="form-check-label">Requires SAT subject</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Минимальный ACT</label>
                        <input type="number" name="min_act" class="form-control" value="<?php echo @$_GET['min_act']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Минимальный IELTS</label>
                        <input type="number" name="min_ielts" class="form-control" value="<?php echo @$_GET['min_ielts']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Минимальный TOEFL</label>
                        <input type="number" name="min_toefl" class="form-control" value="<?php echo @$_GET['min_toefl']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Минимальный GPA</label>
                        <input type="number" name="min_gpa" class="form-control" value="<?php echo @$_GET['min_gpa']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Минимальный acceptance rate</label>
                        <input type="number" name="min_accept_rate" class="form-control" value="<?php echo @$_GET['min_accept_rate']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Минимальное место в рейтинге университетов</label>
                        <input type="number" name="min_world_ranking" class="form-control" value="<?php echo @$_GET['min_world_ranking']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Процент иностранных студентов</label>
                        <input type="number" name="int_underg_perc" class="form-control" value="<?php echo $univer->int_underg_perc; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="hidden" name="page" value="0">
                    <button type="submit" name="search" class="w-100 btn btn-lg btn-success">Поиск</button>
                </div>
            </form>
        </div>
        <div class="container">
        <div class="col-12 row py-5">
        <?php
            $univers = R::getAll($sql1);
            if($univers){
                $k = count($univers);
                for($i = 0; $i<$k; $i++){
        ?>
                    <div class="col-3">
                        <h3><?php echo $univers[$i]['name']; ?></h3>
                        <p>IELTS: <?php echo $univers[$i]['min_ielts']; ?></p>
                        <p>TOEFL: <?php echo $univers[$i]['min_toefl']; ?></p>
                        <p>SAT: <?php echo $univers[$i]['sat_score']; ?></p>
                        <p>ACT: <?php echo $univers[$i]['act_score']; ?></p>
                        <p>Дедлайн подачи: <?php echo $univers[$i]['application_due']; ?></p>
                        <p>Страна: <?php $country = R::load("countries",$univers[$i]['country_id']); echo $country->name; ?></p>
                        <p class="text-center"><a href="/univer.php?univer_id=<?php echo $univers[$i]['id']; ?>"><button type="button" class="w-100 btn-lg btn-primary">Перейти</button></a></p>
                        <p class="text-center"><a href="/edits/edit_univer.php?univer_id=<?php echo $univers[$i]['id']; ?>"><button type="button" class="w-100 btn-lg btn-secondary">Изменить</button></a></p>
                        <p>
                            <form class="text-center" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                                <input type="hidden" name="univer_id" value="<?php echo $univers[$i]['id']; ?>">
                                <button type="submit" name="deletebutt" class="w-100 btn-lg btn-danger">Удалить</button>
                            </form>
                        </p>
                    </div>
        <?php
                } 
            }
        ?>
        </div>
            <div class="container div-4 text-center">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
                    <?php
                        $k = R::exec($sql);
                        //$k = count($univers);
                        $n = ceil($k / 4);
                        for($i = 0;$i<$n;$i++){ ?>
                            <a href="
                                <?php
                                    if(isset($_GET['page'])){
                                        $u=$_GET['page']; 
                                        $link = preg_replace("/page=$u/","page=$i",$_SERVER['REQUEST_URI'],-1); 
                                        echo $link; 
                                    } else {
                                        $link = $_SERVER['REQUEST_URI'] . "?page=" . $i;
                                        echo $link;
                                    }
                                ?>">
                                <button type="button" name="page" class="m-1 btn btn-sm btn-secondary">
                                    <?php echo $i+1; ?>
                                </button>
                            </a>
                    <?php
                        }
                    ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
    $('#filters_button').click(function (e) {
        e.preventDefault();
        $('#filters_div').toggle('show');
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