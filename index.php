<?php
$ip="192.68.0.10"; //your device ip
$device = 'IP:' .$ip ;
$sdate = $edate = date('Y-m-d') ;

$number = "";
for ($i = 1; $i <= 1000; $i++) {
    $number .= ($i . ",");
}
$number = substr($number, 0, strlen($number) - 1);
$url = "http://".$ip."/form/Download?uid=" . $number . "&sdate=".$sdate."&edate=".$edate; //attendance download url for the device zkteco uFace 302

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close($ch);

$record = explode("\n", $server_output);

$attData = array();
foreach ($record as $idx =>  $r) {
    $r = str_replace("\t", " ", $r);
    $arr = explode(" ",$r);
    //array_push(  $attData, $isi);
    $arrLength = count($arr);
    if($arrLength > 0 && isset($arr[$arrLength-3]) && $arr[$arrLength-4]){
        $empCode = $arr[0];
        $accessDate= $arr[$arrLength-4];
        $accessTime = $arr[$arrLength-3];
        $attData[] = $postData = [
            'SL_NO' => $idx + 1,
            'DEVICE_NAME' => $device,
            'ACCESS_MODE' =>  'IN',
            'EMP_CODE' => $empCode,
            'ACCESS_DATE_TIME' => date('Y/m/d h:iA', strtotime($accessDate. ' '.$accessTime))
        ]; 
    }
}
echo json_encode($attData);
