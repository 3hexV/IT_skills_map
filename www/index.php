<?php
$grade_list = ["danger", "warning", "info", "primary"];
$swich_flag = 0;

$len_per = 3;
$info_data = Action();

$handle_tmp0 = fopen("./res/tmp/temp_0.tmp", "r");
$handle_tmp1 = fopen("./res/tmp/temp_1.tmp", "r");
$handle_tmp2 = fopen("./res/tmp/temp_2.tmp", "r");
$handle_tmp3 = fopen("./res/tmp/temp_3.tmp", "r");


$handle_index_html = fopen("./index.html", "w");

fwrite($handle_index_html, fread($handle_tmp0, filesize("./res/tmp/temp_0.tmp")));
fclose($handle_tmp0);


$part_0 = fread($handle_tmp1, filesize("./res/tmp/temp_1.tmp"));
$part_1 = fread($handle_tmp2, filesize("./res/tmp/temp_2.tmp"));

for ($i = 0; $i < count($info_data); $i++) {
    $str_0 = "";
    $class_now = $info_data[$i][0];
    $skill_name = $info_data[$i][1];
    $value_now = $info_data[$i][2];
    $is_new =  $info_data[$i][3];
    // echo $class_now;
    // echo $skill_name;
    // echo $value_now;
    // echo $is_new;
    // echo  "<br>";
    if ($class_now != $swich_flag) {
        fwrite($handle_index_html, "\n</ul>\n</div>\n");
        // echo $class_now .":" .  $swich_flag . "<br>";
        if ($swich_flag != 0) {
            $num = "ci_r_" . $class_now;
            $temp_part_0 = str_replace("show_info", "hidden_info", $part_0);
            $temp_part_0 = str_replace("ci_r_1", $num, $temp_part_0);
            fwrite($handle_index_html, $temp_part_0);
            
            fwrite($handle_index_html, $part_1);
        } else {
            fwrite($handle_index_html, $part_0);
            fwrite($handle_index_html, $part_1);
        }
        
        $swich_flag = $class_now;
    }

    
    $grade_index = 0;
    $new_str = "";
    if($value_now>100) $value_now = 100;
    else if($value_now<0) $value_now = 0;

    switch($value_now/10)
    {
        case 9 :case 10: $grade_index = 3; break;
        case 8:case 7:case 6:case 5: $grade_index = 2; break;
        case 4:case 3: $grade_index = 1; break;
        default: $grade_index = 0; break;
    }

    if ($is_new == 1) {
        $new_str = "New";
    } else {
        $new_str = "";
    }

    $str_0 =
            "\n<li class=\"list-group-item\">
        <br>
        <!-- 1.最新 -->
        <span class=\"badge\" style=\"background-color: red;\">"
        . $new_str . 
        "</span>
        <!-- 2.技能名称 -->
        <label class=\"col-xs-3 col-sm-3 col-md-3 col-lg-3\">"
        . $skill_name .
         "</label>
        <!-- 3.技能熟练度 -->
        <div class=\"progress progress-striped active\" style=\"margin-right: 5%;\">
        <div class=\"progress-bar progress-bar-"
        . $grade_list[$grade_index] .
        "\" role=\"progressbar\" aria-valuenow=\""
        . $value_now .
        "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: "
        . $value_now .
        "%;\"></div></div>\n</li>\n";

    fwrite($handle_index_html, $str_0);
    $str_0 = "";
}

fclose($handle_tmp1);
fclose($handle_tmp2);

fwrite($handle_index_html, "\n</ul>\n</div>\n");
fwrite($handle_index_html, fread($handle_tmp3, filesize("./res/tmp/temp_3.tmp")));
fclose($handle_tmp3);

fclose($handle_index_html);

// $skill_name = " ";
// $value_now = " ";

header("Location: ./index.html");   

function Action()
{
    $filePath = './res/info.csv';
    $data = getCsvData($filePath);
    // var_export($data);
    return $data;
}

function getCsvData($filePath)
{
    $handle = fopen($filePath, "rb");
    $data = [];
    while (!feof($handle)) {
        $data[] = fgetcsv($handle);
    }
    fclose($handle);
    return $data;
}
