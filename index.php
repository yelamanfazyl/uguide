<?php 
    require $_SERVER['DOCUMENT_ROOT']."/utils/db.php";
    error_reporting(0);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate'])) {
        $sat_reading = $_POST['sat_reading']; // Student's SAT Reading Score
        $sat_math = $_POST['sat_math']; // Student's SAT Math Score
        $act_composite = $_POST['act_composite']; // Student's ACT Composite Score
        $act_english = $_POST['act_english']; // Student's ACT English Score
        $act_math = $_POST['act_math']; // Student's ACT Math Score
        $univer_id = $_POST['univer_id'];
        $gpa = $_POST['gpa']; // Student's GPA
        $sat_university_acceptance_score = R::find("univers","id = ?",array($univer_id)); // SAT university acceptance score (Minimum SAT Score)
        $univer = R::findOne("univers","id = ?",array($univer_id));
        $countryd = R::find("countries","id = ?",array($univer->country_id));
        $country = $countryd->name;

        $chances = array();

        //SAT ACT

        if(($univer->sat_score != null) && ($univer->sat_score != "")){
            if(($_POST['exam1_id'] == 1) && !$noexam1){
                if ($sat_reading < 500 && $sat_math < 500) {
                    $chances["sat_chances"] = "You need to retake SAT";
                } elseif ($country = "USA") {
                    $sat_reading_percentile25 = $univer->sat_read_write_25; // SAT Reading 25 percentile
                    $sat_reading_percentile75 = $univer->sat_read_write_75; // SAT Reading 75 percentile
                    $sat_math_percentile25 = $univer->sat_math_25; // SAT Math 25 percentile
                    $sat_math_percentile75 = $univer->sat_math_75; // SAT Math 75 percentile
                    if (($sat_math >= $sat_math_percentile75) || ($sat_reading >= $sat_reading_percentile75)){
                        $chances["sat_chances"] = "You have good chances";
                    } elseif ((($sat_math >= $sat_math_percentile25) || ($sat_reading >= $sat_reading_percentile25)) && (($sat_math < $sat_math_percentile75) || ($sat_reading < $sat_reading_percentile75))) {
                        $chances["sat_chances"] = "You are in range";
                    } else {
                        $chances["sat_chances"] = "Low chances";
                    }
                } elseif ($sat_math + $sat_reading >= $sat_university_acceptance_score) {
                    $chances["sat_chances"] = "You have good chances";
                } else {
                    $chances["sat_chances"] = "Low chances";
                }
                if ((($sat_math <0 ) || ($sat_math > 800)) || (($sat_reading < 0) || ($sat_reading > 800))) {
                    $chances["sat_chances"] = "SAT Math and SAT Reading must be in a range of 0 and 800";
                }
            } elseif (($_POST['exam1_id'] == 3) && $noexam1){
                $chances["sat_chances"] = "You have to take SAT";
            }
    
        } elseif (($univer->act_score != null) && ($univer->act_score != "")){
            if (($_POST['exam1_id'] == 2) && !$noexam1){
                $act_composite_percentile25 = $univer->act_composite_25; // ACT Composite 25 percentile
                $act_composite_percentile75 = $univer->act_composite_75; // ACT Composite 75 percentile
                $act_english_percentile25 = $univer->act_english_25; // ACT English 25 percentile
                $act_english_percentile75 = $univer->act_english_75; // ACT English 75 percentile
                $act_math_percentile25 = $univer->act_math_25; // ACT Math 25 percentile
                $act_math_percentile75 = $univer->act_math_75; // ACT MAth 75 percentile
        
                if ($act_composite < 12 && $act_math < 12 && $act_english < 12) {
                    $chances["act_chances"] = "You need to retake ACT";
                } else {
                    if ($country = "USA") {
                        if (($act_composite >= $act_composite_percentile75) || ($act_math >= $sat_math_percentile75) || ($act_english >= $act_english_percentile75)){
                            $chances["act_chances"] = "You have good chances";
                        } else {
                            if ((($act_composite >= $act_composite_percentile25) || ($act_math >= $sat_math_percentile25) || ($act_english >= $act_english_percentile75)) && (($act_composite < $act_composite_percentile75) || ($act_math < $sat_math_percentile75) || ($act_english < $act_english_percentile75))) {
                                $chances["act_chances"] = "You are in range";
                            } else {
                                $chances["act_chances"] = "Low chances";
                            }
                        }
                    }
                }
                if ((($act_composite <0 ) || ($act_composite > 36)) || (($act_math < 0) || ($act_math > 36)) || (($act_reading < 0) || ($act_reading > 36))) {
                    $chances["act_chances"] = "ACT Composite, ACT Math and ACT Reading must be in a range of 0 and 36";
                }
            } elseif(($_POST['exam1_id'] == 3) && $noexam1){
                $chances["act_chances"] = "You have to take ACT";
            }
        } elseif (($univer->act_score == null) || ($univer->act_score == "")){
            $chances["act_chances"] = "No data about ACT for this University";
        } elseif (($univer->sat_score == null) || ($univer->sat_score == "")){
            $chances["sat_chances"] =  "No data about SAT for this University";
        }
        
        //IELTS TOEFL

        $ielts = $_POST['ielts']; // Student's IELTS Score
        $toefl = $_POST['toefl']; // Student's TOEFL Score
        $ielts_minimum = $univer->min_ielts; // Minimum IELTS Score required by University
        $toefl_minimum = $univer->min_toefl; // Minimum TOEFL Score required by University
        // $ielts_maximum = 9; // Maximum IELTS Score
        // $toefl_maximum = 25; // Maximum TOEFL Score
        if(($toefl_minimum != null) && ($toefl_minimum != "")){
            if(($_POST['exam2_id'] == 2) && !$noexam2){
                if ($toefl < $toefl_minimum) {
                    $chances["toefl_chances"] = "You need to retake TOEFL";
                } else {
                    if ($toefl >= $toefl_minimum) {
                        $chances["toefl_chances"] = "You have reached the acceptable degree";
                    } else {
                        if ($toefl >= $toefl_minimum + 20) {
                            $chances["toefl_chances"] = "Your results are great for this University";
                        }
                    }
                }
            } elseif (($_POST['exam2_id'] == 3) && $noexam2){
                $chances["toefl_chances"] = "You have to take TOEFL";
            }
            if (($toefl <0 ) || ($toefl > 120)) {
                $chances["toefl_chances"] = "TOEFL must be in a range of 0 and 120";
            }    
        }if (($ielts_minimum != null) && ($ielts_minimum != "")){
            if(($_POST['exam2_id'] == 1) && !$noexam2){
                if ($ielts < $ielts_minimum) {
                    $chances["ielts_chances"] = "You need to retake IELTS";
                } else {
                    if ($ielts >= $ielts_minimum) {
                        $chances["ielts_chances"] = "You have reached the acceptable degree";
                    } else {
                        if ($ielts >= $ielts_minimum + 1) {
                            $chances["ielts_chances"] = "Your results are great for this University";
                        }
                    }
                }
            } elseif (($_POST['exam2_id'] == 3) && $noexam2){
                $chances["ielts_chances"] = "You have to take IELTS";
            }
        } elseif (($ielts_minimum == null) && ($ielts_minimum == "")) {
            $chances["ielts_chances"] = "No data about IELTS for this University";
        } elseif (($toefl_minimum == null) && ($toefl_minimum == "")) {
            $chances["toefl_chances"] = "No data about TOEFL for this University";
        }
        if (($ietls <0 ) || ($ielts > 9)) {
            $chances["ielts_chances"] = "IELTS must be in a range of 0 and 9";
        }

        //GPA

        $gpa = ($_POST['gpa'] / 5) * 4; // Student's GPA in 4 scale format
        $average_university_gpa = $univer->avg_2nd_school_gpa; // Average GPA of applicants accepted by University
        
        if(($average_university_gpa != null) && ($average_university_gpa != "")){
            if ($gpa < $average_university_gpa) {
                $chances["gpa_chances"] = "Your GPA for this University has to be slightly higher";
            } else {
                $chances["gpa_chances"] = "Your GPA is great for University";
            }
        } elseif (($average_university_gpa == null) && ($average_university_gpa == "")){
            $chances["gpa_chances"] = "No Data about GPA for this University";
        }
        if (($gpa == null) || ($gpa == "")) {
            $chances["gpa_chances"] = "You did not fill out the required fields.";
        }
        if (($gpa < 0 ) || ($gpa > 4)) {
            $chances["gpa_chances"] = "GPA must be in a range of 0 and 4";
        }
        
        // Acceptance rate

        $acceptance_rate = $univer->accept_rate; // Acceptance rate of University

        if(($acceptance_rate != null) && ($acceptance_rate != "")){
            $chances["acceptance_rate"] = $acceptance_rate . "%";
        } elseif(($acceptance_rate == null) && ($acceptance_rate == "")){
            $chances["acceptance_rate"] = "No Data about acceptance rate for this University";
        }
        
        //Scholarships

        $university_scholarships = R::find("scholarships","univer_id = ?",array($univer->id)); // List of scholarships
        
        foreach($university_scholarships as $university_scholarship){
            if(($university_scholarship->name != null) && ($university_scholarship->name != "")){
                $chances["scholarships"][] = $university_scholarship->name;
            } else {
                $chances["scholarships"][] = "No data";
            }
        }
        
        $univer_info = array();
        if(($univer->name != null) && ($univer->name != "")){
            $univer_info['name'] = $univer->name;
        } else {
            $univer_info['name'] = "No data";
        }
        if(($country != null) && ($country != "")){
            $univer_info['country'] = $country;
        } else {
            $univer_info['country'] = "No data";
        }
        if(($univer->min_toefl != null) && ($univer->min_toefl != "")){
            $univer_info['min_toefl'] = $univer->min_toefl;
        } else {
            $univer_info['min_toefl'] = "No data";
        }
        if(($univer->min_ielts != null) && ($univer->min_ielts != "")){
            $univer_info['min_ielts'] = $univer->min_ielts;
        } else {
            $univer_info['min_ielts'] = "No data";
        }
        if(($univer->avg_2nd_school_gpa != null) && ($univer->avg_2nd_school_gpa != "")){
            $univer_info['gpa'] = $univer->avg_2nd_school_gpa;
        } else {
            $univer_info['gpa'] = "No data";
        }
        if(($univer->int_underg_perc != null) && ($univer->int_underg_perc != "")){
            $univer_info['int_stud'] = $univer->int_underg_perc . "%";
        } else {
            $univer_info['int_stud'] = "No data";
        }
        if(($univer->univer_ranking != null) && ($univer->univer_ranking != "")){
            $univer_info['ranking'] = $univer->univer_ranking;
        } else {
            $univer_info['ranking'] = "No data";
        }
        if(($univer->sat_score != null) && ($univer->sat_score != "")){
            $univer_info['sat'] = $univer->sat_score;
        } else {
            $univer_info['sat'] = "No data";
        }
        if(($univer->act_score != null) && ($univer->act_score != "")){
            $univer_info['act'] = $univer->act_score;
        } else {
            $univer_info['act'] = "No data";
        }
        if(($univer->sat_essay != null) && ($univer->sat_essay != "")){
            $univer_info['sat_essay'] = $univer->sat_essay;
        } else {
            $univer_info['sat_essay'] = "No data";
        }
        if(($univer->sat_subjects != null) && ($univer->sat_subjects != "")){
            $univer_info['sat_subjects'] = $univer->sat_subjects;
        } else {
            $univer_info['sat_subjects'] = "No data";
        }
        if(($univer->apply_link != null) && ($univer->apply_link != "")){
            $univer_info['link'] = $univer->apply_link;
        } else {
            $univer_info['link'] = "No data";
        }
        if(($univer->applicants_total != null) && ($univer->applicants_total != "")){
            $univer_info['applicants_total'] = $univer->applicants_total;
        } else {
            $univer_info['applicants_total'] = "No data";
        }
        if(($univer->avg_aid_award != null) && ($univer->avg_aid_award != "")){
            $univer_info['avg_aid_award'] = $univer->avg_aid_award;
        } else {
            $univer_info['avg_aid_award'] = "No data";
        }
        if(($univer->enrolled_total != null) && ($univer->enrolled_total != "")){
            $univer_info['enrolled_total'] = $univer->enrolled_total;
        } else {
            $univer_info['enrolled_total'] = "No data";
        }
        if(($univer->enrolled_full_total != null) && ($univer->enrolled_full_total != "")){
            $univer_info['enrolled_full_total'] = $univer->enrolled_full_total;
        } else {
            $univer_info['enrolled_full_total'] = "No data";
        }
        if(($univer->enrolled_part_total != null) && ($univer->enrolled_part_total != "")){
            $univer_info['enrolled_part_total'] = $univer->enrolled_part_total;
        } else {
            $univer_info['enrolled_part_total'] = "No data";
        }
        if(($univer->indistrict_tution_fees != null) && ($univer->indistrict_tution_fees != "")){
            $univer_info['indistrict_tution_fees'] = "$" . $univer->indistrict_tution_fees;
        } else {
            $univer_info['indistrict_tution_fees'] = "No data";
        }
        if(($univer->instate_tution_fees != null) && ($univer->instate_tution_fees != "")){
            $univer_info['instate_tution_fees'] = "$" . $univer->instate_tution_fees;
        } else {
            $univer_info['instate_tution_fees'] = "No data";
        }
        if(($univer->outstate_tution_fees != null) && ($univer->outstate_tution_fees != "")){
            $univer_info['outstate_tution_fees'] = "$" . $univer->outstate_tution_fees;
        } else {
            $univer_info['outstate_tution_fees'] = "No data";
        }
        if(($univer->book_supplies != null) && ($univer->book_supplies != "")){
            $univer_info['book_supplies'] = "$" . $univer->book_supplies;
        } else {
            $univer_info['book_supplies'] = "No data";
        }
        if(($univer->on_campus_room != null) && ($univer->on_campus_room != "")){
            $univer_info['on_campus_room'] = "$" . $univer->on_campus_room;
        } else {
            $univer_info['on_campus_room'] = "No data";
        }
        if(($univer->on_campus_other != null) && ($univer->on_campus_other != "")){
            $univer_info['on_campus_other'] = "$" . $univer->on_campus_other;
        } else {
            $univer_info['on_campus_other'] = "No data";
        }
        if(($univer->off_campus_nofamily_room != null) && ($univer->off_campus_nofamily_room != "")){
            $univer_info['off_campus_nofamily_room'] = "$" . $univer->off_campus_nofamily_room;
        } else {
            $univer_info['off_campus_nofamily_room'] = "No data";
        }
        if(($univer->off_campus_nofamily_other != null) && ($univer->off_campus_nofamily_other != "")){
            $univer_info['off_campus_nofamily_other'] = "$" . $univer->off_campus_nofamily_other;
        } else {
            $univer_info['off_campus_nofamily_other'] = "No data";
        }
        if(($univer->off_campus_family_other != null) && ($univer->off_campus_family_other != "")){
            $univer_info['off_campus_family_other'] = "$" . $univer->off_campus_family_other;
        } else {
            $univer_info['off_campus_family_other'] = "No data";
        }
        if(($univer->outstate_tution != null) && ($univer->outstate_tution != "")){
            $univer_info['outstate_tution'] = "$" . $univer->outstate_tution;
        } else {
            $univer_info['outstate_tution'] = "No data";
        }
        if(($univer->outstate_fees != null) && ($univer->outstate_fees != "")){
            $univer_info['outstate_fees'] = "$" . $univer->outstate_fees;
        } else {
            $univer_info['outstate_fees'] = "No data";
        }
        if(($univer->price_outstate_on_campus != null) && ($univer->price_outstate_on_campus != "")){
            $univer_info['price_outstate_on_campus'] = "$" . $univer->price_outstate_on_campus;
        } else {
            $univer_info['price_outstate_on_campus'] = "No data";
        }
        if(($univer->price_outstate_off_campus_nofamily != null) && ($univer->price_outstate_off_campus_nofamily != "")){
            $univer_info['price_outstate_off_campus_nofamily'] = "$" . $univer->price_outstate_off_campus_nofamily;
        } else {
            $univer_info['price_outstate_off_campus_nofamily'] = "No data";
        }
        if(($univer->price_outstate_off_campus_family != null) && ($univer->price_outstate_off_campus_family != "")){
            $univer_info['price_outstate_off_campus_family'] = "$" . $univer->price_outstate_off_campus_family;
        } else {
            $univer_info['price_outstate_off_campus_family'] = "No data";
        }
        if(($univer->price_outstate_off_campus_family != null) && ($univer->price_outstate_off_campus_family != "")){
            $univer_info['price_outstate_off_campus_family'] = "$" . $univer->price_outstate_off_campus_family;
        } else {
            $univer_info['price_outstate_off_campus_family'] = "No data";
        }
        if(($univer->image != null) && ($univer->image != "")){
            $univer_info['image'] = "$" . $univer->image;
        } else {
            $univer_info['image'] = "No data";
        }
        $found = true;
    }
    // $average_sat = 25; // Average SAT Score of applicants
    // $average_act = 25; // Average ACT Score of applicants
    // $average_ielts = 25; // Average IELTS Score of applicants
    // $average_toefl = 25; // Average TOEFL Score of applicants
    // $financial_aid = $_POST['financial_aid']; // Financial aid needed or not

    // if (empty($university_scholarship)) {
    //     echo "No Scholarships Available in this University";
    // } else {
    //     if ((($sat_math + $sat_reading > $average_sat) || ($act_math + $act_english + $act_composite > $average_act)) && (($ielts > $average_ielts) || ($toefl > $average_toefl))) {
    //         echo $university_scholarship[0] + "can be suitable";
    //     }
    //     if ($financial_aid = "Yes") {
    //         echo $university_scholarship[1] + "can be suitable";
    //     }
    // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>INPUT</title>
</head>
<body>
    <div class="container my-2">
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form-main" enctype="multipart/form-data">
        <?php 
            $regions = R::findAll("regions"); 

            if($regions != null){ ?>
                <div class="form-group">
                    <label>Регион</label>
                    <select name="region_id" class="form-control" id="select-region" required>
                        <option value="">Выберите</option>
                        <?php 
                            if(isset($regions)){
                                foreach($regions as $region){ ?>
                                    <option <?php if($region->id == $_POST['region_id']){ echo 'selected'; } ?> value="<?php echo $region->id; ?>"><?php echo $region->name; ?></option>
                                <?php }
                            }
                        ?>
                    </select>
                </div>
            <?php } 
        ?>  
        <?php 
            $countries = R::find("countries", "region_id = ?", array($_POST['region_id'])); 
                        
            if($countries != null){ 
            ?>
                <div class="form-group">
                    <label>Страны</label>
                    <select name="country_id" class="form-control" id="select-country" required>
                        <option value="">Выберите</option>
                        <?php 
                            if(isset($countries)){
                                foreach($countries as $country){ ?>
                                    <option <?php if($country['id'] == $_POST['country_id']){ echo 'selected'; } ?> value="<?php echo $country['id'] ?>"><?php echo $country['name']; ?></option>
                                <?php }
                            }
                        ?>
                    </select>
                </div>
            <?php } 
        ?>
        <?php 
            if(isset($_POST['country_id']) && $_POST['country_id'] != ''){ 
            ?>
                <div class="form-group">
                    <label>Университет</label>
                    <select name="univer_id" class="form-control" id="select-univer" required>
                        <option value="">Выберите</option>
                        <?php 
                        $univers = R::find("univers", "country_id = ?", array($_POST['country_id']));
                        if(isset($univers)){
                            foreach($univers as $univer){ ?>
                                <option <?php if($univer['id'] == $_POST['univer_id']){ echo 'selected'; } ?> value="<?php echo $univer['id'] ?>"><?php echo $univer['name']; ?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>
            <?php } 
        ?>
        <?php 
            if(isset($_POST['univer_id']) && $_POST['univer_id'] != ''){ 
            ?>
                <div class="form-group">
                <label>SAT/ACT</label>
                    <select name="exam1_id" class="form-control" id="select-exam1" required>
                        <option value="">Выберите</option>
                        <option <?php if($_POST['exam1_id'] == 1){ echo "selected"; } ?>  value="1">SAT</option>
                        <option <?php if($_POST['exam1_id'] == 2){ echo "selected"; } ?>  value="2">ACT</option>
                        <option <?php if($_POST['exam1_id'] == 3){ echo "selected"; } ?>  value="3">Didn't pass SAT/ACT</option>
                    </select>
                </div>
            <?php } 
        ?>
        <?php 
            if(isset($_POST['exam1_id']) && $_POST['exam1_id'] != ''){ 
            ?>
                <div class="form-group">
                <label>Language Exams</label>
                    <select name="exam2_id" class="form-control" id="select-exam2" required>
                        <option value="">Выберите</option>
                        <option <?php if($_POST['exam2_id'] == 1){ echo "selected"; } ?>  value="1">IELTS</option>
                        <option <?php if($_POST['exam2_id'] == 2){ echo "selected"; } ?>  value="2">TOEFL</option>
                        <option <?php if($_POST['exam2_id'] == 3){ echo "selected"; } ?>  value="3">Didn't pass IELTS/TOEFL</option>
                    </select>
                </div>
            <?php } 
        ?>
        <div id="user_input">
        <?php 
            if(isset($_POST['exam2_id']) && $_POST['exam2_id'] != ''){
                // if(isset($_POST['exam1_id']) && $_POST['exam1_id'] != ''){
                    if($_POST['exam1_id'] == 1){ ?>
                        <div class="sat_results form-group">
                            <label>SAT Reading Score</label>
                            <input class="form-control" type="number" name="sat_reading">
                        </div>
                        <div class="sat_results form-group">
                            <label>SAT Math Score</label>
                            <input class="form-control" type="number" name="sat_math">
                        </div>
                <?php
                    $noexam1 = false;
                    } elseif($_POST['exam1_id'] == 2){
                ?>
                        <div class="act_results form-group">
                            <label>ACT Composite Score</label>
                            <input class="form-control" type="number" name="act_composite">
                        </div>
                        <div class="act_results form-group">
                            <label>ACT English Score</label>
                            <input class="form-control" type="number" name="act_english">
                        </div>
                        <div class="act_results form-group">
                            <label>ACT Math Score</label>
                            <input class="form-control" type="number" name="act_math">
                        </div>
        <?php
                    $noexam1 = false;
                    } elseif($_POST['exam1_id'] == 3){
                        $noexam1 = true;
                    }
                //}
                    if($_POST['exam2_id'] == 1){ 
                        $noexam2 = false;
                    ?>
                        <div class="ielts_results form-group">
                            <label>IELTS score</label>
                            <input class="form-control" type="number" step= "0.5" name="ielts">
                        </div>
        <?php
                    } elseif ($_POST['exam2_id'] == 2){ 
                        $noexam2 = false;    
                    ?>
                        <div class="toefl_results form-group">
                            <label>TOEFL score</label>
                            <input class="form-control" type="number" name="toefl">
                        </div>
        <?php
                    } elseif ($_POST['exam2_id'] == 3){
                        $noexam2 = true;
                    } ?>

                    <div class="form-group avg-gpa">
                        <label>Average GPA (in 5 scale)</label>
                        <input class="form-control" type="number" step= "0.01" name="gpa">
                    </div>
                     <button class="btn btn-lg btn-block btn-success w-50 mx-auto" type="submit" name="calculate">Отправить</button>
                    <!-- <div class="form-group avg-gpa">
                        <label>Need financial aid?</label>
                        <select name="financial_aid" id="financial_aid">
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div> -->
        <?php
            } 
        ?>
        </div>
        
    </form>
    </div>
    
    <?php if($found){ ?>
        <div class="contaniner col-12 my-5 row">
            <div class="col-6 chances">
                <h2>Conclusion:</h2>
                <?php if ($chances["sat_chances"]){ ?>
                <p>SAT: <?php echo $chances["sat_chances"]; ?></p>
                <?php } ?>
                
                <?php if ($chances["act_chances"]){ ?>
                <p>ACT: <?php echo $chances["act_chances"]; ?></p>
                <?php } ?>

                <?php if ($chances["toefl_chances"]){ ?>
                <p>TOEFL: <?php echo $chances["toefl_chances"]; ?></p>
                <?php } ?>

                <?php if ($chances["ielts_chances"]){ ?>
                <p>IELTS: <?php echo $chances["ielts_chances"]; ?></p>
                <?php } ?>

                <?php if ($chances["gpa_chances"]){ ?>
                <p>GPA: <?php echo $chances["gpa_chances"]; ?></p>
                <?php } ?>

                <?php if ($chances["acceptance_rate"]){ ?>
                <p>Acceptance rate: <?php echo $chances["acceptance_rate"]; ?></p>
                <?php } ?>
                
                <?php if ($chances["scholarships"]){ ?>
                <p>
                Scholarships: 
                <?php
                    foreach($chances["scholarships"] as $scholarship){
                        echo $scholarship;
                    }
                ?>
                </p>
                <?php } ?>
            </div>
            <div class="col-6 university-info">
                <h2>Information about university</h2>

                <?php if ($univer_info["name"]){ ?>
                <p>Name: <?php echo $univer_info["name"]; ?></p>
                <?php } ?>

                <?php if ($univer_info["country"]){ ?>
                <p>Country: <?php echo $univer_info["country"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["min_toefl"]){ ?>
                <p>Min TOEFL: <?php echo $univer_info["min_toefl"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["min_ielts"]){ ?>
                <p>Min IELTS: <?php echo $univer_info["min_ielts"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["gpa"]){ ?>
                <p>Min GPA: <?php echo $univer_info["gpa"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["int_stud"]){ ?>
                <p>Internation Undergrates Percentage: <?php echo $univer_info["int_stud"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["ranking"]){ ?>
                <p>QS World University Rankings: <?php echo $univer_info["ranking"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["sat"]){ ?>
                <p>Min SAT: <?php echo $univer_info["sat"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["act"]){ ?>
                <p>Min ACT: <?php echo $univer_info["act"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["sat_essay"]){ ?>
                <p>SAT Essay: <?php echo $univer_info["sat_essay"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["sat_subjects"]){ ?>
                <p>SAT Subjects: <?php echo $univer_info["sat_subjects"]; ?></p>
                <?php } ?>

                <?php if ($univer_info["link"]){ ?>
                <p>Apply link: <a href="<?php echo $univer_info["link"]; ?>"><?php echo $univer_info["link"]; ?></a></p>
                <?php } ?>
                
                <?php if ($univer_info["applicants_total"]){ ?>
                <p>Applicants total: <?php echo $univer_info["applicants_total"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["avg_aid_award"]){ ?>
                <p>Average Aid Award: <?php echo $univer_info["avg_aid_award"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["enrolled_total"]){ ?>
                <p>Enrolled Total: <?php echo $univer_info["enrolled_total"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["enrolled_full_total"]){ ?>
                <p>Enrolled full time total: <?php echo $univer_info["enrolled_full_total"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["enrolled_part_total"]){ ?>
                <p>Enrolled part time total: <?php echo $univer_info["enrolled_part_total"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["indistrict_tution_fees"]){ ?>
                <p>Published in-district tuition and fees: <?php echo $univer_info["indistrict_tution_fees"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["instate_tution_fees"]){ ?>
                <p>Published in-state tuition and fees: <?php echo $univer_info["instate_tution_fees"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["outstate_tution_fees"]){ ?>
                <p>Published out-of-state tuition and fees: <?php echo $univer_info["outstate_tution_fees"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["book_supplies"]){ ?>
                <p>Books and supplies: <?php echo $univer_info["book_supplies"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["on_campus_room"]){ ?>
                <p>On campus room and board: <?php echo $univer_info["on_campus_room"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["on_campus_other"]){ ?>
                <p>On campus other expenses: <?php echo $univer_info["on_campus_other"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["off_campus_nofamily_room"]){ ?>
                <p>Off campus (not with family)  room and board: <?php echo $univer_info["off_campus_nofamily_room"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["off_campus_nofamily_other"]){ ?>
                <p>Off campus (not with family) other expenses: <?php echo $univer_info["off_campus_nofamily_room"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["off_campus_family_other"]){ ?>
                <p>Off campus (with family)  other expenses: <?php echo $univer_info["off_campus_family_other"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["outstate_tution"]){ ?>
                <p>Published out-of-state tuition: <?php echo $univer_info["outstate_tution"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["outstate_fees"]){ ?>
                <p>Published out-of-state fees: <?php echo $univer_info["outstate_fees"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["price_outstate_on_campus"]){ ?>
                <p>Total price for out-of-state students living on campus: <?php echo $univer_info["price_outstate_on_campus"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["price_outstate_off_campus_nofamily"]){ ?>
                <p>Total price for out-of-state students living off campus (not with family): <?php echo $univer_info["price_outstate_off_campus_nofamily"]; ?></p>
                <?php } ?>
                
                <?php if ($univer_info["price_outstate_off_campus_family"]){ ?>
                <p>Total price for out-of-state students living off campus (with family): <?php echo $univer_info["price_outstate_off_campus_family"]; ?></p>
                <?php } ?>

                <?php if ($univer_info["image"]){ ?>
                <p>Image:</p>
                <img src="<?php echo $univer_info["image"]; ?>" alt="<?php echo $univer_info["name"]; ?>" width="800">
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <script src="/assets/scripts/script.js"></script>
</body>
</html>