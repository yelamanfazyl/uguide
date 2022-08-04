<?php 
    require $_SERVER['DOCUMENT_ROOT']."/utils/db.php";
    // error_reporting(0);
    $univer_id = $_GET['univer_id'];

    if(!isset($univer_id) && $univer_id == ""){
        $errors[] = "Университет не был выбран";
    }

    if(empty($errors)){
        $univer = R::load("univers",$univer_id);
        if(!$univer){
            $errors[] = "Неправильно выбран университет";
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
    <title>Поиск университетов</title>
</head>
<body>
    <div class="container">
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
            if(isset($univer)){
        ?>   
            <div class="col-12 container py-5">
                <h2>Institution name: <?php echo $univer->name; ?></h2>
                <p>Country: <?php $country = R::load("countries", $univer->country_id); echo $country->name; ?></p>
                <p>Required TOEFL: <?php echo $univer->min_toefl; ?></p>
                <p>Required IELTS: <?php echo $univer->min_ielts; ?></p>
                <p>Required GPA in secondary school: <?php echo $univer->avg_2nd_school_gpa; ?></p>
                <p>International Undergraduate Students percentage: <?php echo $univer->int_underg_perc; ?></p>
                <p>Accept rate: <?php echo $univer->accept_rate; ?>%</p>
                <p>Application deadline: <?php echo $univer->application_due; ?></p>
                <p>QS World University Rankings: <?php echo $univer->univer_ranking; ?></p>
                <p>Required SAT Score: <?php echo $univer->sat_score; ?></p>
                <p>Required ACT Score: <?php echo $univer->act_score; ?></p>
                <p>SAT Essay: <?php echo $univer->sat_essay; ?></p>
                <p>SAT Subjects: <?php echo $univer->sat_subjects; ?></p>
                <p>Apply link: <a href="<?php echo $univer->apply_link; ?>"><?php echo $univer->apply_link; ?></a></p>
                <p>Majors: <br> <?php $majors = R::find("majors","univer_id = ?",array($univer->id)); foreach($majors as $major){ echo $major->name . "<br>" ; } ?></p>
                <p>Additional: <br> <?php $additional = str_replace("|","<br>",$univer->additional); echo $additional; ?></p>
                <img class="img-fluid" src="<?php echo $univer->image; ?>">
            </div>
        <?php 
            }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>