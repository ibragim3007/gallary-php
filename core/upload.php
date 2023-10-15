<?php
require "../inc/database.php";

function answer($par){
    echo json_encode($res = [
        'answer' => $par
    ]);
}

function img_compress($img)
{
    $imagickSrc = new Imagick($img);
    $compressionList = [Imagick::COMPRESSION_JPEG2000];

    $imagickDst = new Imagick();
    $imagickDst->setCompression(80);
    $imagickDst->setCompressionQuality(60);
    $imagickDst->newPseudoImage(
        $imagickSrc->getImageWidth(),
        $imagickSrc->getImageHeight(),
        'canvas:white'
    );

    $imagickDst->compositeImage(
        $imagickSrc,
        Imagick::COMPOSITE_ATOP,
        0,
        0
    );
    $imagickDst->setImageFormat('jpg');
    $imagickDst->writeImage($img);
}

$images = $_FILES;
$comeBack = array();
$types = ["image/jpeg", "image/png"];
$getAfterMaxId = "";

foreach ($images as $image) {
    if (!in_array($image["type"], $types)) {
        echo json_encode($res = [
            'status' => false,
            'type' => $image['type'],
            'answer' => "Не поддерживаемы тип файла"
        ]);
        die();
    }

    $fileSize = $image['size'] / 1000000;
    $maxSize = 5;

    if($fileSize > $maxSize) {
        echo json_encode($res = [
            'status' => false,
            'answer' => "Слишком большой вес файла"
        ]);
        die();
    }

    $extension = pathinfo($image["name"], PATHINFO_EXTENSION);

    $fileName = rand(0, 1000).time().".$extension";

    if(!move_uploaded_file($image['tmp_name'], "../data/img/".$fileName)){
        echo json_encode($res = [
            'status' => false,
            'answer' => "Произошла ошибка во время загрузки файла"
        ]);
        die();
    }

    img_compress("../data/img/".$fileName);

    $nameFile = $image['name'];
    $time = time();
    $fullPath = "/data/img/".$fileName;

    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country'=>'', 'city'=>'');
    if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
    else $ip = $remote;
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null)
    {
        $country = $ip_data->geoplugin_countryName;
        $city    = $ip_data->geoplugin_city;
        $ip_user = $ip_data->geoplugin_request;
    }

    $MaxId = mysqli_query($connect, "SELECT MAX(`id`) FROM `file`");
    $MaxId = mysqli_fetch_assoc($MaxId);
    $MaxId = $MaxId['MAX(`id`)'] + 1;
    $compress_path = "/data/img/".$compressFileName;

    mysqli_query($connect, "INSERT INTO `file`(`name`, `date`, `path`, `size`, `user_agent_ip`, `user_agent_city`, `user_agent_country`) 
    VALUES ('$nameFile','$time','$fullPath', '$fileSize','$ip_user','$city', '$country')");

    $getAfterMaxId = mysqli_query($connect, "SELECT * FROM `file` WHERE `id` = '$MaxId'");
    array_push($comeBack, mysqli_fetch_assoc($getAfterMaxId));

}

echo json_encode($comeBack);

?>
