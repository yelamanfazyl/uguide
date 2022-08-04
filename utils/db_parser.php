<?php
    ini_set('max_execution_time', 0);

    require $_SERVER['DOCUMENT_ROOT'].'/assets/libs/redbean/rb.php';

    $host = 'localhost';
    $dbname = 'howard';
    $username = 'root';
    $password = 'root';

    R::setup('mysql:host='.$host.';dbname='.$dbname,$username,$password);
    
    function europe_parser(){
        $string = file_get_contents($_SERVER['DOCUMENT_ROOT']."/assets/json/fake.json");
        $univers = json_decode($string, true);
        foreach($univers as $univer){
            $univerd = R::dispense("univers");
            $univerd->name = $univer["Institution Name"];
            $univerd->country = $univer["Country"];
            $univerd->min_toefl = $univer["Minimum TOEFL"];
            $univerd->min_ielts = $univer["Minimum IELTS"];
            $univerd->avg_2nd_school_gpa = $univer["Average Secondary School GPA"];
            $univerd->int_underg_perc = $univer["International Undergraduates Percentage"];
            $univerd->accept_rate = $univer["Acceptance Rate"];
            $univerd->application_due = $univer["Application due"];
            $univerd->univer_ranking = $univer["QS World University Rankings"];
            $univerd->sat_score = $univer["SAT Score"];
            $univerd->act_score = $univer["ACT Score"];
            $univerd->sat_essay = $univer["SAT Essay"];
            $univerd->image = $univer["Image"];
            $univerd->sat_subjects = $univer["Additional"]["SAT Subjects"];
            $univerd->apply_link = $univer["Additional"]["Apply link"];
            $additional = "Applicants total: " . $univer["Additional"]["Applicants total"] . "|";
            $additional .= "Admissions total: " . $univer["Additional"]["Admissions total"] . "|";

            $scholarships_name = explode("|",$univer["Scholarships"]);
            if((isset($scholarships_name)) && ($scholarships_name != "null")){
                $additional .= "|Scholarships: ";
                foreach($scholarships_name as $scholarship_name){
                    $additional .= $scholarship_name . ",";
                }
            }
            
            $examinations = explode("|",$univer["Additional"]["Required Examination"]);
            if((isset($examinations)) && ($examinations != "null")){
                $additional .= "|Required Examinations: ";
                foreach($examinations as $examination){
                    $additional .= $examination . ",";
                }
            }
            
            $ibs = explode("|",$univer["Additional"]["International Baccalaureate Diploma (IB)"]);
            if((isset($ibs)) && ($ibs != "null")){
                $additional .= "|International Baccalaureate Diploma (IB): ";
                foreach($ibs as $ib){
                    $additional .= $ib . ",";
                }
            }
            
            $features = explode("|",$univer["Additional"]["Features"]);
            if((isset($features)) && ($features != "null")){
                $additional .= "|Features: ";
                foreach($features as $feature){
                    $additional .= $feature . "|";
                }
            }
            
            $univerd->additional = $additional;

            R::store($univerd);
           
            $majors = explode("|",$univer['Undergraduate Majors']);
            foreach($majors as $major){
                $majord = R::dispense("majors");
                $majord->name = $major;
                $majord->univer_id = $univerd->id;
                R::store($majord);
            }
        }
    }

    function usa_parser(){
        $string = file_get_contents($_SERVER['DOCUMENT_ROOT']."/assets/json/USA.json");
        $univers = json_decode($string, true);

        // $scholarship_table = R::dispense("usscholarshipnames");
        // $scholarship_table->name = "Merit";
        // R::store($scholarship_table);
        
        // $scholarship_table = R::dispense("usscholarshipnames");
        // $scholarship_table->name = "Need-based";
        // R::store($scholarship_table);

        foreach($univers as $univer){
            $univerd = R::dispense("univers");
            $univerd->name = $univer["Institution Name"];
            $univerd->country = $univer["Country"];
            $univerd->min_toefl = $univer["Minimum TOEFL"];
            $univerd->min_ielts = $univer["Minimum IELTS"];
            $univerd->avg_2nd_school_gpa = $univer["Average Secondary School GPA"];
            $univerd->int_underg_perc = $univer["International Undergraduates Percentage"];
            $univerd->accept_rate = $univer["Acceptance Rate"];
            $univerd->application_due = $univer["Application due"];
            $univerd->univer_ranking = $univer["QS World University Rankings"];
            $univerd->sat_score = $univer["SAT Score"];
            $univerd->act_score = $univer["ACT Score"];
            $univerd->sat_essay = $univer["SAT Essay"];
            $univerd->image = $univer["Image"];
            $univerd->sat_subjects = $univer["Additional"]["SAT Subjects"];
            $univerd->apply_link = $univer["Additional"]["Apply link"];
            $univerd->sat_read_write_25 = $univer["Additional"]["SAT Evidence-Based Reading and Writing 25th percentile score (ADM2019)"];
            $univerd->sat_read_write_75 = $univer["Additional"]["SAT Evidence-Based Reading and Writing 75th percentile score (ADM2019)"];
            $univerd->sat_math_25 = $univer["Additional"]["SAT Math 25th percentile score (ADM2019)"];
            $univerd->sat_math_75 = $univer["Additional"]["SAT Math 75th percentile score (ADM2019)"];
            $univerd->act_composite_25 = $univer["Additional"]["ACT Composite 25th percentile score (ADM2019)"];
            $univerd->act_composite_75 = $univer["Additional"]["ACT Composite 75th percentile score (ADM2019)"];
            $univerd->act_english_25 = $univer["Additional"]["ACT English 25th percentile score (ADM2019)"];
            $univerd->act_english_75 = $univer["Additional"]["ACT English 75th percentile score (ADM2019)"];
            $univerd->act_math_25 = $univer["Additional"]["ACT Math 25th percentile score (ADM2019)"];
            $univerd->act_math_75 = $univer["Additional"]["ACT Math 75th percentile score (ADM2019)"];

            $additional = "Applicants total: " . $univer["Additional"]["Applicants total"] . "|";
            $additional .= "Admissions total: " . $univer["Additional"]["Admissions total"] . "|";
            if($univer["Additional"]["Average Aid Award"] != "null"){
                $additional .= "Average Aid Award: " . $univer["Additional"]["Average Aid Award"] . "|";
            }
            if($univer["Additional"]["Enrolled total (ADM2019)"] != "null"){
                $additional .= "Enrolled total (ADM2019): " . $univer["Additional"]["Enrolled total (ADM2019)"] . "|";
            }
            if($univer["Additional"]["Enrolled full time total (ADM2019)"] != "null"){
                $additional .= "Enrolled full time total (ADM2019): " . $univer["Additional"]["Enrolled full time total (ADM2019)"] . "|";
            }
            if($univer["Additional"]["Enrolled part time total (ADM2019)"] != "null"){
                $additional .= "Enrolled part time total (ADM2019): " . $univer["Additional"]["Enrolled part time total (ADM2019)"] . "|";
            }
            if($univer["Additional"]["Published in-district tuition and fees 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Published in-district tuition and fees 2019-20 (IC2019_AY): " . $univer["Additional"]["Published in-district tuition and fees 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Published in-state tuition and fees 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Published in-state tuition and fees 2019-20 (IC2019_AY): " . $univer["Additional"]["Published in-state tuition and fees 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Published out-of-state tuition and fees 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Published out-of-state tuition and fees 2019-20 (IC2019_AY): " . $univer["Additional"]["Published out-of-state tuition and fees 2019-20 (IC2019_AY)"] . "|";
            }            
            if($univer["Additional"]["Books and supplies 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Books and supplies 2019-20 (IC2019_AY): " . $univer["Additional"]["Books and supplies 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["On campus  room and board 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "On campus  room and board 2019-20 (IC2019_AY): " . $univer["Additional"]["On campus  room and board 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["On campus  other expenses 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "On campus  other expenses 2019-20 (IC2019_AY): " . $univer["Additional"]["On campus  other expenses 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Off campus (not with family)  room and board 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Off campus (not with family)  room and board 2019-20 (IC2019_AY): " . $univer["Additional"]["Off campus (not with family)  room and board 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Off campus (not with family)  other expenses 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Off campus (not with family)  other expenses 2019-20 (IC2019_AY): " . $univer["Additional"]["Off campus (not with family)  other expenses 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Off campus (with family)  other expenses 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Off campus (with family)  other expenses 2019-20 (IC2019_AY): " . $univer["Additional"]["Off campus (with family)  other expenses 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Published out-of-state tuition 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Published out-of-state tuition 2019-20 (IC2019_AY): " . $univer["Additional"]["Published out-of-state tuition 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Published out-of-state fees 2019-20 (IC2019_AY)"] != "null"){
                $additional .= "Published out-of-state fees 2019-20 (IC2019_AY): " . $univer["Additional"]["Published out-of-state fees 2019-20 (IC2019_AY)"] . "|";
            }
            if($univer["Additional"]["Total price for out-of-state students living on campus 2019-20 (DRVIC2019)"] != "null"){
                $additional .= "Total price for out-of-state students living on campus 2019-20 (DRVIC2019): " . $univer["Additional"]["Total price for out-of-state students living on campus 2019-20 (DRVIC2019)"] . "|";
            }
            if($univer["Additional"]["Total price for out-of-state students living off campus (not with family)  2019-20 (DRVIC2019)"] != "null"){
                $additional .= "Total price for out-of-state students living off campus (not with family)  2019-20 (DRVIC2019): " . $univer["Additional"]["Total price for out-of-state students living off campus (not with family)  2019-20 (DRVIC2019)"] . "|";
            }
            if($univer["Additional"]["Total price for out-of-state students living off campus (with family)  2019-20 (DRVIC2019)"] != "null"){
                $additional .= "Total price for out-of-state students living off campus (with family)  2019-20 (DRVIC2019): " . $univer["Additional"]["Total price for out-of-state students living off campus (with family)  2019-20 (DRVIC2019)"] . "|";
            }
            
            $scholarships_name = $univer["Scholarships"];
            if((isset($scholarships_name)) && ($scholarships_name != "null")){
                $additional .= "|Scholarships: ";
                if(is_array($scholarships_name)){
                    $additional .= "Merit, Need-based|";
                } elseif ($scholarships_name == "Merit") {
                    $additional .= "Merit|";
                } elseif ($scholarships_name == "Need-based") {
                    $additional .= "Need-based|";
                }
            }

            $univerd->additional = $additional;

            R::store($univerd);
            $majors = explode("|",$univer['Undergraduate Majors']);
            foreach($majors as $major){
                $majord = R::dispense("majors");
                $majord->name = $major;
                $majord->univer_id = $univerd->id;
                R::store($majord);
            }
        }
    }

    // usa_parser();
    europe_parser();

    // $string = file_get_contents("USA.json");
    // $univers = json_decode($string, true);
    // foreach($univers as $univer){
    //     echo $univer["Institution Name"];
    //     echo "<br>";
    //     echo '"Undergraduate Majors":"';
    //     $majors = explode(", ",$univer['Undergraduate Majors']);
    //     foreach($majors as $major){
    //         echo $major;
    //         echo "|";
    //     }
    //     echo '",';
    //     echo "<br>";
    //     echo "<br>";
    // }
?>