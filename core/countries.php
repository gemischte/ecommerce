<?php

require_once __DIR__ . '/init.php';

use App\Utils\Logger;

$countries = file_get_contents(__DIR__ . '/../data/countries.json');
$data = json_decode($countries, true);

$countries = "INSERT INTO countries (id,name,iso2,created_at,calling_codes) 
VALUES(?,?,?,?,?)";
$stmt = $conn->prepare($countries);
if(!$stmt){
    Logger::error("Prepare failed: " . $conn->error);
}

$id = $name = $iso2 = $created_at = $calling_codes = "";
$created_at = date("Y-m-d H:i:s");
$stmt->bind_param("sssss", 
$id, $name, $iso2, $created_at, $calling_codes);

foreach ($data as $country)
{
    // $id = $country["id"];
    $name = $country["name"];
    $iso2 = $country["alpha2Code"];
    $calling_codes = '+' . $country["callingCodes"][0];
    if(!$stmt->execute()){
        Logger::error("Execute failed:" . $stmt->error);
    }

}
echo"success";
?>
