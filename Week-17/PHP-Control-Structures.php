<?php
//$time = date("H:i:s");

//if($time > 6 and $time < 18) echo "Day Time";
//else echo "Night Time";

//if($time > 6 and $time < 18) {
//    echo "Day Time";
//} else {
//    echo "Night Time";
//}

//Alternative Syntax

//if($time > 6 and $time < 18):
//    echo "Day Time";
//else:
//    echo "Night Time";
//endif;

//$day = date("D"); //Sun, Mon, Tue
//if($day === "Sun") {
//    echo "Today is Sunday";
//} elseif ($day === "Sat") {
//    echo "Today is Saturday";
//} else {
//    echo "TOday is a weekday.";
//}

//Switch Statement - ==
//$day = date("D");
//switch ($day) {
//    case "Sat":
//    case "Sun":
//        echo "Weekend";
//        break;
//    case "Fri":
//        echo "TGIF";
//        break;
//    default:
//        echo "Weekday";
//}

//match() -> ===, "5" === 5
//$day = date("D");
//$result = match ($day) {
//    "Sat", "Sun" => "Weekend",
//    "Fri" => "TGIF",
//    default => "Weekday"
//};
//echo $result;

//while, do-while, for Statement
//while-> condition checked before executing the loop body
//$nums = [12, 42, -2, 8, 621];
//$i = 0;
//$result = 0;
//while ($i < count($nums)) {
//    $result += $nums[$i];
//    $i++;
//}
//echo $result;
//array_sum(), array_reduce()

//$i = 0;
//$result = 0;
//
//while($i < count($nums)) {
//    if($nums[$i] < 0) {
//        $i++;
//        continue;
//    }
//    $result += $nums[$i];
//    $i++;
//}
//echo $result;

//while ($i < count($nums)) {
//    if($nums[$i] < 0) break;
//    $result += $nums[$i];
//    $i++;
//}
//echo $result;

//do-while
//$nums = [12, 42, -2, 8, 621];
//$i = 0;
//$result = 0;
//
//do {
//    $result += $nums[$i];
//    $i++;
//} while ($i < count($nums));
//echo $result;

//for
//$nums = [12, 42, -2, 8, 621];
//$result = 0;
//for ($i = 0; $i < count($nums); $i++) {
//    $result += $nums[$i];
//}
//echo $result;

//foreach
//$nums = [12, 42, -2, 8, 621];
//$result = 0;
//
//foreach ($nums as $num) {
//    $result += $num;
//}
//echo $result;

//$user = [ "alice" => 98, "bob" => 95];
//$result = [];
//
//foreach ($user as $name => $point) {
//    $result[] = $name;
//}
//print_r($result);

//array_keys()