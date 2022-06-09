<?
/**
 * Puertos disponibles a utilizar:
 * desde el 8000 en adelante, menos el 8008 que es del SSH
 */


$data = json_decode(file_get_contents("php://input"),true);

print_r($data);

$msgReceived = $data['_data']['body'];

function sendMessage($to,$msg,$image = ''){
    $curl = curl_init();

    if($image == ''){
        $url = 'http://nodesv1.eviajes.online:9595/chat/sendmessage/'.str_replace('@c.us','',$to);
        
        $post = 'message='.$msg;
    }
    else {
        $url = 'http://nodesv1.eviajes.online:9595/chat/sendimage/'.str_replace('@c.us','',$to);
        
        $post = 'caption='.$msg.'&image='.$image;
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
}

$msg2send = array();

// %20 = espacio
// %0a = salto de linea

if($msgReceived == '!test'){
    $cmds = '!buscar 122DECBUEMIA'.'%0a';
    $cmds .= '!ayuda'.'%0a';
    
    $msg2send = array(
        'sendback' => false,
        'msg' => $cmds
    );

    sendMessage($data['from'],$cmds);
}

if($msgReceived == '!ayuda'){
    $cmds = '!cuenta = Información de cuenta y últimas reservas realizadas'.'%0a';
    $cmds .= '!reserva [codigo] = Información de una reserva especifica'.'%0a';
    $cmds .= '!buscar [fecha+origen+destino] = Búsqueda'.'%0a';
    
    $msg2send = array(
        'sendback' => false,
        'msg' => $cmds
    );

    sendMessage($data['from'],$cmds);
}

if($msgReceived == '!cuenta'){
    $cmds = '*Información de cuenta*'.'%0a';
    $cmds .= '*Nombre y Apellido*: Jorge Cancela'.'%0a';
    $cmds .= '*Últimas 5 reservas realizadas*:'.'%0a';
    $cmds .= '*XXXXX* : Buenos Aires > Miami - 12 DIC 2022 > 20 DIC 2022'.'%0a';
    $cmds .= '*XXXXX* : Buenos Aires > Córdoba - 05 NOV 2022 > 08 NOV 2022'.'%0a';
    $cmds .= '*XXXXX* : Buenos Aires > Mendoza - 01 JUL 2022 > 04 JUL 2022'.'%0a';
    $cmds .= '*XXXXX* : Buenos Aires > Sao Pablo - 03 AGO 2022 > 10 AGO 2022'.'%0a';
    $cmds .= '*XXXXX* : Buenos Aires > New York - 05 SEP 2022 > 20 SEP 2022'.'%0a';
    
    $msg2send = array(
        'sendback' => false,
        'msg' => $cmds
    );

    sendMessage($data['from'],$cmds);
}

if(strpos($msgReceived,'!reserva') !== false){
    $codRes = trim(str_replace('!reserva','',$msgReceived));

    $cmds = '*Información para la reserva '.$codRes.'*'.'%0a';
    $cmds .= $codRes.'%0a';
    $cmds .= '1.1JORGE/CANCELA'.'%0a';
    $cmds .= '1 AR1884Y 22MAY S AEPUSH HK1 940A 115P /DCAR*XWIHYL /E'.'%0a';
    $cmds .= 'TKT/TIME LIMIT'.'%0a';
    $cmds .= '1.TAWK86H19MAY009/1159P'.'%0a';
    $cmds .= 'PHONES'.'%0a';
    $cmds .= '1.BUE44441111-H'.'%0a';
    $cmds .= '2.BUE54 11 4323 1555 CONSOLID VIA WEB-A'.'%0a';
    $cmds .= 'PASSENGER EMAIL DATA EXISTS *PE TO DISPLAY ALL'.'%0a';
    $cmds .= 'PRICE QUOTE RECORD EXISTS - SYSTEM'.'%0a';
    $cmds .= 'SECURITY INFO EXISTS *P3D OR *P4D TO DISPLAY'.'%0a';
    $cmds .= 'REMARKS'.'%0a';
    $cmds .= '1.H-ASD/AP/PNR/APTEK GENERATED PNR'.'%0a';
    $cmds .= '2.H-ADT - BUE AR USH23751.00ARS23751.00END-ARS 23751.00-ARS 2'.'%0a';
    $cmds .= ' 5582.74'.'%0a';
    $cmds .= '3.H-ASD/AP/PNR/APTEK GENERATED PNR'.'%0a';
    $cmds .= '4.H-PRECIO TOTAL - ARS 25582.74'.'%0a';
    $cmds .= '5.H-NOMBRE DE EMPRESA - APTEK TURISMO'.'%0a';
    $cmds .= '6.H-NOMBRE DE USUARIO GENERADOR - JORGE.CANCELA'.'%0a';
    $cmds .= 'RECEIVED FROM - JORGE CANCELA'.'%0a';
    $cmds .= 'K86H.K86H*AWS 1034/19MAY22 XWIGMJ H'.'%0a';

    $msg2send = array(
        'sendback' => false,
        'msg' => $cmds
    );

    sendMessage($data['from'],$cmds);
}

if(strpos($msgReceived,'!buscar') !== false){
    $cmds = trim(str_replace('!buscar','',$msgReceived));

    //Curl post message
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://aereos.eviajes.online/services/testSabreCmdWsp.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('id' => 31, 'msg' => $cmds)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    curl_close ($ch);

    $msg2send = array(
        'sendback' => false,
        'msg' => $server_output
    );

    sendMessage($data['from'],$server_output);
}

echo json_Encode($msg2send);
?>