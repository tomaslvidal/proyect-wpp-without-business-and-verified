<?
function sendMessage($to,$msg,$image = ''){
    $curl = curl_init();

    if($image==''){
        $url = 'http://nodesv1.eviajes.online:9595/chat/sendmessage/'.str_replace('@c.us','',$to);
        $post = 'message='.$msg;
    } else {
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

if(isset($_POST)){
    if($_POST['telefono']<>''){
        $mensaje = '*Nueva solicitud de reserva!*'.'%0a';
        $mensaje .= 'El usuario *Jorge Cancela* solicito una reserva'.'%0a %0a';
        $mensaje .= 'Puede acceder a la misma ingresando al siguiente link'.'%0a';
        $mensaje .= 'https://nodesv1.eviajes.online/demo/showRes.php'.'%0a';

        $filetempname = rand(000000,999999);
        $tmpname = 'temp_capture'.$filetempname.'.png';

        file_put_contents($tmpname, file_get_contents($_POST['capture']));

        sendMessage($_POST['telefono'],$mensaje,'https://nodesv1.eviajes.online/demo/'.$tmpname);

        // unlink($tmpname);
        
        echo 'Mensaje enviado correctamente!';
    }
}
?>
<!doctype html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Whatsapp API Demo v0.1</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Whatsapp Notification Api Demo</h2>
            </div>
            
            <div class="col-12">
                <div style="height:1px;overflow:hidden;">
                    <?
                    include('mail-template.php');
                    ?>
                </div>
            </div>

            <div class="col-12">
                <form id="sendForm" action="" method="POST">
                    <div class="form-group">
                        <label>Número de teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value=""> <?//5491151138878?>
                        <input type="hidden" name="capture" id="capture" value="">
                    </div>
                    <button type="button" id="btnSave" class="btn btn-success mt-2">Simular envío</button>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.2/dist/FileSaver.min.js"></script>

    <script>
    $(function() { 
        $("#btnSave").click(function() { 
            html = $("#capturaReserva");
            html2canvas(html, {
                onrendered: function(canvas) {
                    theCanvas = canvas;


                    canvas.toBlob(function(blob) {
                        // console.log(blob);
                        // saveAs(blob, "Dashboard.png"); 

                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = function () {
                            var base64String = reader.result;
                            console.log('Base64 String - ', base64String);
                            $('#capture').val(base64String);
                        
                            $('#sendForm').submit();
                            // Simply Print the Base64 Encoded String,
                            // without additional data: Attributes.
                            // console.log('Base64 String without Tags- ', base64String.substr(base64String.indexOf(', ') + 1));
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>