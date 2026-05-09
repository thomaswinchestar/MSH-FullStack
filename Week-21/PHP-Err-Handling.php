<?php
// 1. E_PARSE - Syntax Error
// 2. E_ERROR - Fatal Error, Stop the Program
// 3. E_WARNING - Warning, Continue the Program
// 4. E_NOTICE - Notice, Continue the Program
// 5. E_STRICT - Strict Mode
// 6. E_DEPRECATED
// 7. E_ALL

//declare(strict_types = 1);
//// E_STRICT, Strict Error
//function add( int $a, int $b) {
//    echo $a + $b;
//}
//add( 1, "2" );

//php.ini
// window - C:\xampp\php,
//custom error handling
// error_reporting(0), error_reporting(E_ALL), error_reporting(-1)
// error_reporting(E_PARSE | E_ERROR | E_WARNING);
// error_reporting(E_ALL & ~E_NOTICE);

//PHP Exception Handling
function add($nums) {
    if(!is_array($nums)) {
        throw new Exception("Argument must be an array");
    }
    return array_sum($nums);
}
//try {
//    echo add(1);
//} catch (Exception $e) {
//    echo $e->getMessage();
//}

//try {
//    echo add([1, 2, 3]);
//} catch(Exception $e) {
//    echo $e->getMessage();
//} finally {
//    echo "</br>";
//    echo "Done";
//}