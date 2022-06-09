<?
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'localhost:5000/chat/sendmessage/5491151138878',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'message=*Solicitud de reserva generada* %0aCódigo de reserva: *XXXXXX* %0aPuede autorizar la reserva ingresando a%0ahttps://aereos.eviajes.online',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

echo $response;