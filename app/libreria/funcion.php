<?php

function tiempo($fecha, $formato='d/m/Y h:i A') {
    if ($fecha !== ""){
        $time = strtotime($fecha);
    }else{
        $time = strtotime(date());
    }
    return date($formato, $time);
}

function fechaToMysql($fecha) {
    $arr = explode('/', $fecha);
    return $arr[2] . '-' . $arr[1] . '-' . $arr[0];
}

function fechaToMysqlFromTimePicker($fechaCompleta){
    $fechaCompleta = explode(" ", $fechaCompleta);
    $fecha = fechaToMysql($fechaCompleta[0]);
    return $fecha." ".$fechaCompleta[1];
}
function fechaToMysqlFromTimePicker2($fechaSinHOra){
    $fecha = fechaToMysql($fechaSinHOra);
    return $fecha;
}
function fechaToMysql2($fecha) {
    $arr = explode('-', $fecha);
    return $arr[2] . '-' . $arr[1] . '-' . $arr[0];
}
function formatoFecha($fecha) {
    $arr = explode('-', $fecha);
    return $arr[2] . '/' . $arr[1] . '/' . $arr[0];
}
function array_to_associative ($array,$indice){
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $response[$value[$indice]][] = $value;
          }
    }else{
        $response = '';
    }

  return $response;
}
function queryArray($query)
{
  $arr=array();
  foreach ($query as $key=>$value) {
    array_push($arr,$value);
  }
  return $arr;
}

function enviarCorreo($variables, $to, $subject = "Empresa.com", $body = "", $adjunto = ''){

    require_once APP . DS . 'libreria' . DS . 'class.phpmailer.php';
    require_once APP . DS . 'libreria' . DS . 'fpdf181/fpdf.php';
    require_once APP . DS . 'core' . DS . 'PDF.class' . '.php';


    /* EMAIL SETTING */
    $mail = new PHPMailer();
    $mail->IsSMTP(); // send via SMTP
    $mail->SMTPDebug = 3;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true; // turn on SMTP authentication
    $mail->Username = ""; // SMTP username
    $mail->Password = '';
    $mail->IsHTML(true);
    $de = "conta.deiconsac@gmail.com";
    $mail->From = $de;
    $mail->FromName = "conta.deiconsac@gmail.com";
    $mail->addAddress($to);
    $mail->addReplyTo('integradorgofacturas@gmail.com', 'conta.deiconsac@gmail.com');
    $mail->Subject = utf8_decode($subject);

    //$mail->AddStringAttachment('http://portal.centromedicodozone.com/app/cargas/comprobante.pdf','comprobante.pdf', 'base64','application/pdf');

    $mail->Body = utf8_decode(
        '<div style="width: 100%; margin: 0 auto;">
            <div style="width: 80%; text-align: center; padding: 40px 20px 10px; margin: 0 auto;">
                <img src="'.RUTA_LOGO.'" width="200px" />
            </div>
            <div>
                <h3> Estimado :</h3>
                <p>Mediante la presente, le enviamos la información correspondiente a su COMPROBANTE ELECTRÓNICO
                Para descargar su comprobante electrónico, por favor ingresar a esta dirección:
                <a href="'.URL_PDF_2.'?id_empresa='.$variables["id_empresa"].'&serie_cor='.$variables["serie_cor"].'&monto='.$variables["monto"].'&fecha_emision='.$variables["fecha_emision"].'" download="comprobante.pdf">LINK COMPROBANTE</a> </p>
                <p>Si el link no abre, por favor copia la dirección en un navegador web.</p>


            </div>
            <div style="width: 70%; padding: 20px; margin: 0 auto; color: #093655;">
                <div>
                    <p>&nbsp;</p>
                    <p style="color: #093655; text-align: center;">Si tienes alguna consulta adicional, no dudes en hacérmelo saber y con gusto intentare ponerme en contacto contigo a la brevedad.</p>
                    <p style="color: #CCC; text-align: center;"><small>Copyright ' . date("Y") . '  PORTAL DE CONSULTA . Todos los derechos reservados </small></p>
                </div>
            </div>
        </div>'
    );

    return $mail->Send();


}
function imprimirPDF($variables,$tipo, $estilos){


    require_once APP . DS . 'libreria' . DS . 'fpdf181/fpdf.php';
    require_once APP . DS . 'core' . DS . 'PDF.class' . '.php';
    require_once APP . DS . 'libreria' . DS . 'phpqrcode/qrlib.php';

    $arr = [];
    $font_size = '11';
    $bg_color_cabecera = '#ffffff';
    $margin_left_right = '0';
    $margin_top_bottom = '10';
      foreach ($estilos as $key => $value) {
        if( $value["codigo"] == 'font-size'){
            $font_size = urldecode($value["valor"]);
        }
        if( $value["codigo"] == 'background-color-cabecera'){
            $bg_color_cabecera = '#'.urldecode($value["valor"]);
        }
        if( $value["codigo"] == 'margin-left-right'){
            $margin_left_right = urldecode($value["valor"]);
        }
        if( $value["codigo"] == 'margin-top-bottom'){
            $margin_top_bottom = urldecode($value["valor"]);
        }
      }

        if(isset($_COOKIE['1'])){
            $font_size = $_COOKIE['1'];
        }
        if(isset($_COOKIE['2'])){
            $bg_color_cabecera = '#'.$_COOKIE['2'];
        }
        if(isset($_COOKIE['3'])){
            $margin_left_right = $_COOKIE['3'];
        }
        if(isset($_COOKIE['4'])){
            $margin_top_bottom = $_COOKIE['4'];
        }
    //$tipoDoc = strtoupper( $variables["tipo_documento"] );
    $serie_cor = strtoupper( $variables["serie_cor"] );
    $fecha_emision = $variables["fecha_emision"];
    $ruc_empresa = strtoupper( $variables["ruc_empresa"] );
    $name_moneda = $variables["name_moneda"];
    $numero_en_letras = numberToWord($variables["total"]);
    $numero_en_letras = strtoupper( $numero_en_letras );
    $nombre_cliente = strtoupper( urldecode($variables["nombre_cliente"]) );
    $direccion_cliente = strtoupper( urldecode($variables["direccion_cliente"]) );
    $ruc_cliente = strtoupper( $variables["ruc_cliente"] );
    $tipo_doc = strtoupper(urldecode($variables["tipo_doc"]));
    $tipo_doc_code = strtoupper(urldecode($variables["tipo_doc_code"]));
    $name_company = strtoupper(urldecode($variables['name_company']));
    $direction_company = strtoupper(urldecode($variables['direction_company']));
    $distrito_company = strtoupper(urldecode($variables['distrito_company']));
    $document_type = strtoupper($variables['document_type']);
    $id_empresa = ($variables['id_empresa']);

    // get the HTML
    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      ini_set('log_errors', 1);
    //include(APP.DS.'modulo/portal/vista.portal.pdf.php');
    include(APP . DS . 'libreria' . DS . 'html2pdf/pdffile.php');
    $content = ob_get_clean();
    $content = '<!DOCTYPE html>
    <html lang="en" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title></title>
      </head>
      <body>
        señor__
      </body>
    </html>';

     // how to configure pixel "zoom" factor
     //qr
     $tempDir = APP . DS . 'cargas/';
    /* El ruc de la empresa, igv, total, documento cliente, tipocliente(dni o ruc), tipodocumento,serie,correlativo y fecha emision del comprobante*/
     $codeContents = "$ruc_empresa | 01 | ".explode("-",$serie_cor)[0]." | ".explode("-",$serie_cor)[1]." | ".$variables["total"]." | ".$variables["total_igv"]." | ".$fecha_emision." | 6 | $ruc_cliente |";
        $fecha_emision_ = date('Y-m-d',strtotime($fecha_emision));

            $name_qr = $ruc_cliente."-".$serie_cor.'-'.$fecha_emision_;


     // generating
     QRcode::png($codeContents, $tempDir.'qr-'.$name_qr.'.png', QR_ECLEVEL_L, 1);

    // convert in PDF
    require_once(APP . DS . 'libreria' . DS . 'html2pdf/html2pdf.class.php');
    $detalle = '';$i = 0;
    foreach($variables["detalles"] AS $item ){
        $i++;
      $detalle .='<tr>
        <td align="center">'.$i.'</td>
        <td align="center">'.$item["codigo"].'</td>
        <td align="left">'.urldecode($item["nombre"]).'</td>
        <td  align="center" >'.$item["unidad_codigo"].'</td>
        <td align="right">'.$item["cantidad"].'</td>
        <td align="right">'.$item["valor_unitario"].'</td>
        <td align="right">'.$item["subtotal_product"].'</td>
        </tr>';
    }

    try
    {

        $buffer = '<!DOCTYPE html>
        <html lang="en" dir="ltr">
            <head>
              <meta charset="utf-8">
              <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

              <title>Comprobante Electronico</title>
            </head>
            <body style="font-size:'.$font_size.'px;">
                <div style="margin:'.$margin_top_bottom.'px '.$margin_left_right.'px;width: 720px;">
                    <table style="width:700px" >
                        <tr>
                            <td style="width:10px;text-align: center;padding:10px 10px">
                                <img src="'.RUTA_LOGO.'" height="100" class="">
                            </td>
                            <td style="width:300px;text-align: center;">
                                <br>
                                <span style="font-size: 7pt;margin-top:4px;text-align:center;">
                                '.$name_company.'
                                <br>
                                <br>
                                '.$direction_company.'
                                <br>
                                <br>
                                '.$distrito_company.'
                                </span>
                            </td>
                            <td valign="top" style="width:170px;font-size:16px;">
                                <div style="border:2px solid;text-align:center;width:100%;padding:10px;">
                                RUC: '.$ruc_empresa.' <br><br>
                                '.$tipo_doc.'<br>
                                '. utf8_encode('ELECTRONICA<br>') .'
                                '. $serie_cor .'<br>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table style="width:700px;" >
                        <tr>
                            <td style="width:90px;">Señor(es)</td>
                            <td style="width:370px;">'.$nombre_cliente.'</td>
                            <td style="width:90px;" align="right">Moneda</td>
                            <td><div style="width:140px;text-align:right">SOLES</div></td>
                        </tr>
                        <tr>
                            <td style="width:90px;">'.$document_type.'</td>
                            <td>'.$ruc_cliente.'</td>
                            <td align="right">F. Emisión</td>
                            <td><div style="width:140px;text-align:right">'.$fecha_emision.'</div></td>
                        </tr>
                        <tr>
                            <td>Dirección</td>
                            <td><div style="width: 370px;">'.$direccion_cliente.'</div></td>
                            <td align="right">F. Vencimiento</td>
                            <td><div style="width:140px;text-align:right">'.$fecha_emision.'</div></td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 100%;" >
                        <tr style="background-color:'.$bg_color_cabecera.'; font-color:black;"  >
                            <td style="height:18px;" bgcolor="" border="1"  width="30"  align="center"  valign="middle">ITEM</td>
                            <td style="height:18px;" bgcolor="" border="1"  width="70"  align="center"  valign="middle">CODIGO</td>
                            <td style="height:18px;" bgcolor="" border="1"  width="330"  align="center"  valign="middle">DESCRIPCION</td>
                            <td style="height:18px;" bgcolor="" border="1"  width="30"   align="center"  valign="middle">UNI</td>
                            <td style="height:18px;" bgcolor="" border="1"  width="40"   align="center"  valign="middle">CANT.</td>
                            <td style="height:18px;" bgcolor="" border="1"  width="75"   align="center"  valign="middle">V. VENTA</td>
                            <td style="height:18px;" bgcolor="" border="1"  width="70"   align="center"  valign="middle">IMPORTE</td>
                        </tr>
                        '.$detalle.'
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5"> <span class=""> </span> </td>
                        </tr>
                        <tr>
                            <td colspan="" align="center"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td  colspan="2" bgcolor="" border="1" align="right">SUB TOTAL '.$variables["symbol"].'&nbsp;</td>
                            <td><div style="width:70px;" align="right">'.($variables['subtotal']).'</div></td>
                        </tr>
                        <tr>
                            <td colspan="" align="center"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>

                            <td  colspan="2" bgcolor="" border="1" align="right">IGV '.$variables["symbol"].'&nbsp;</td>
                            <td><div style="width:70px;" align="right">'.$variables["total_igv"].'</div></td>
                        </tr>
                        <tr>
                            <td colspan="" align="center"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>

                            <td colspan="2" bgcolor="" border="1" align="right">TOTAL '.$variables["symbol"].'&nbsp;</td>
                            <td><div style="width:70px;" align="right">'.($variables["total"]).'</div></td>
                        </tr>
                    </table>
                    <br>
                    <span style="border-top:1px dotted #000;">SON '.$numero_en_letras.' '.$name_moneda.'</span><br>
                    <hr>
                    <table border="0">
                        <tr>
                            <td>
                                <div style="padding-left: 5px;padding-top: 10px;width:520px;text-align: left;">
                                Resumen: -sajdkfnms¡=jsiadji=874h4yrnbz
                                <br>
                                Representación impresa de la '.(($tipo_doc_code=='03')?'Boleta Electrónica ':'Factura Electrónica ' ). $serie_cor.'<br>
                                Resolución de Superintendencia N° 155-2017/SUNAT.<br>
                                Puede consultar este comprobante en <a href="www.deinconsac.com" target="_blank">www.deinconsac.com</a>
                                <br> <br><br>Desarrollado por <a href="www.GoFacturas.com" target="_blank">GoFacturas.com</a>
                                </div>
                            </td>
                            <td>
                            <div style="width:150px;text-align: center;float:right;right:0">
                                <img style="float:right;right:0;" border= "2" width="120" height="120" src="'.$tempDir.'qr-'.$name_qr.'.png'.'" alt="" id="RemoveImg">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </body>
        </html>
        ';

        //url pdf en session

        $html2pdf = new HTML2PDF('P', 'A4', 'es',true, 'UTF-8',array(10, 10, 15, 5));
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($buffer);
        $fecha_emision = date('Y-m-d',strtotime($fecha_emision));
        if($tipo == 2){
            $html2pdf->output($serie_cor.'-'.$fecha_emision.'-comprobante.pdf', 'I');
        }else{
            $html2pdf->output( APP . DS . 'cargas/'.$serie_cor.'-'.$fecha_emision.'-'.$id_empresa.'-comprobante.pdf', 'F');
        }

    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

}
function archivoExcel(){
  require_once APP . DS . 'libreria' . DS . 'phpExcel/PHPExcel/IOFactory.php';
$upload_folder  = dirname(__FILE__).'/excels/';
$nombre_archivo = $_FILES['archivo']['name'];
$tipo_archivo   = $_FILES['archivo']['type'];
$tamano_archivo = $_FILES['archivo']['size'];
$tmp_archivo    = $_FILES['archivo']['tmp_name'];
$extension		= pathinfo($nombre_archivo, PATHINFO_EXTENSION);
$result=[];
$fichero_subido = $upload_folder . basename($nombre_archivo);
if (strtolower($extension) == "xlsx" || strtolower($extension) == "xls")
{
  if (move_uploaded_file($tmp_archivo, $fichero_subido))
  {
    $objPHPExcel = PHPExcel_IOFactory::load($fichero_subido);
    $objPHPExcel = $objPHPExcel->setActiveSheetIndex(0);
      //----------Filas----------//
      $CuentasVer=[];
      $columna ='A';
      $dia = date('d');
        $mes = date('m');
        $anio = date('Y');
        $today = $anio."-".$mes."-".$dia ;
      $ultimaFila = $objPHPExcel->getHighestRow();

      $columns = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG"];
      for ($column = 2; $column < count($columns); $column++)
      {
        $i=0;
        $response2=[];

        for ($row=1; $row <=$ultimaFila ; $row++)
        {
            if ($row==1) {
              /*$response[$i] = $Cabeceras[$i];
              $i++;*/
            }else{
              if ($column>1) {
                $value = $objPHPExcel->getCell($columns[$column].$row)->getCalculatedValue();
                $response2[] = [($columns[$column].$row),$value];
                $i++;
              }
            }
          }
        if ($column>1)
        {
          array_push($CuentasVer,$response2);
        }
        //----------Columnas----------//
      }
      //----------Filas----------//
      $result=array('Error'=>'','Success'=>$CuentasVer);
    unlink($fichero_subido);
  }else{
    $result= array('Error' => ["El archivo no ha podido ser leido correctamente, por favor verifique que el archivo excel cumpla con todos los estandares"] );
  }

}else{
  $result=array('Error'=>["Solo se permiten archivos de formato excel (.xlsx, .xls)"]);
}
return json_encode($result);
}
function numberToWord($numero) {
 // Primero tomamos el numero y le quitamos los caracteres especiales y extras
 // Dejando solamente el punto "." que separa los decimales
 // Si encuentra mas de un punto, devuelve error.

 $extras= array("/[\$]/","/ /","/,/","/-/");
 $limpio=preg_replace($extras,"",$numero);
 $partes=explode(".",$limpio);
 if (count($partes)>2) {
     return "Error, el n&uacute;mero no es correcto";
    // exit();
 }

 // Ahora explotamos la parte del numero en elementos de un array que
 // llamaremos $digitos, y contamos los grupos de tres digitos
 // resultantes

 $digitos_piezas=chunk_split ($partes[0],1,"#");
  $digitos_piezas=substr($digitos_piezas,0,strlen($digitos_piezas)-1);
 $digitos=explode("#",$digitos_piezas);
 $todos=count($digitos);
 $grupos=ceil (count($digitos)/3);

 // comenzamos a dar formato a cada grupo

 $unidad = array    ('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve');
 $decenas = array ('diez','once','doce', 'trece','catorce','quince');
 $decena = array    ('dieci','veinti','treinta','cuarenta','cincuenta','sesenta','setenta','ochenta','noventa');
 $centena = array    ('ciento','doscientos','trescientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos');
 $resto=$todos;

 for ($i=1; $i<=$grupos; $i++) {

     // Hacemos el grupo
     if ($resto>=3) {
         $corte=3; } else {
         $corte=$resto;
     }
         $offset=(($i*3)-3)+$corte;
         $offset=$offset*(-1);

     // corremos el codigo posteado por

     $num=implode("",array_slice ($digitos,$offset,$corte));
     $resultado[$i] = "";
     $cen = (int) ($num / 100);              //Cifra de las centenas
     $doble = $num - ($cen*100);             //Cifras de las decenas y unidades
     $dec = (int)($num / 10) - ($cen*10);    //Cifra de laa decenas
     $uni = $num - ($dec*10) - ($cen*100);   //Cifra de las unidades
     if ($cen > 0) {
        if ($num == 100) $resultado[$i] = "cien";
        else $resultado[$i] = $centena[$cen-1].' ';
     }//end if
     if ($doble>0) {
        if ($doble == 20) {
           $resultado[$i] .= " veinte";
        }elseif (($doble < 16) and ($doble>9)) {
           $resultado[$i] .= $decenas[$doble-10];
        }else {
     if($dec==0)
     {}
     else
     {
     $resultado[$i] .=' '. $decena[$dec-1];
     }

        }//end if
        if ($dec>2 and $uni<>0) $resultado[$i] .=' y ';
        if (($uni>0) and ($doble>15) or ($dec==0)) {
           if ($i==1 && $uni == 1) $resultado[$i].="uno";
           else $resultado[$i].=$unidad[$uni-1];
        }
     }

     // Le agregamos la terminacion del grupo
     switch ($i) {
         case 2:
         $resultado[$i].= ($resultado[$i]=="") ? "" : " mil ";
         break;
         case 3:
         //$resultado[$i].= ($num==1) ? " mill&oacute;n " : " millones ";
   $resultado[$i].= ($num==1) ? " millÓn " : " millones ";
         break;

     }
     $resto-=$corte;
 }

 // Sacamos el resultado (primero invertimos el array)
 $resultado_inv= array_reverse($resultado, TRUE);
 $final="";
 foreach ($resultado_inv as $parte){
     $final.=$parte;
 }
    $posicion_punto= strrpos($numero,".");
    if($posicion_punto){
    $dec_2=substr($numero,$posicion_punto + 1 ,2);
    }else {
    $dec_2='00';
    }
    //$posicion_punto=strpos('.',$dec_2);
    if(!$final){
    $final='CERO ';
    }else{
    if(strlen($dec_2)==1){$dec_2=$dec_2."0";};
    $final = $final." con ".$dec_2."/100  ";
    }
    return strtoupper($final);

}

function s($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

function a_romano($integer, $upcase = true)
    {
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100,
                       'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9,
                       'V'=>5, 'IV'=>4, 'I'=>1);
        $return = '';
        while($integer > 0)
        {
            foreach($table as $rom=>$arb)
            {
                if($integer >= $arb)
                {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }
        return $return;
    }
    function createRandomPassword() {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;

    }
