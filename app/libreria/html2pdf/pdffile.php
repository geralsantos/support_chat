
<html>
	<head>
	  <meta charset="utf-8">
	  <title>Comprobante Electronico</title>
	  <style>
	  .resize{width: 200px; height: auto;}
	  .resize_rectangle_vertical{width: auto; height: 180px;}
	  </style>
	</head>
<body style="font-size:11px;">
	<div style="margin-top: 10px;margin-left: -27px;width: 730px;">

			<table style="width:700px">
			<tr>
			  <td style="width:50px;text-align: center;padding:10px 0px">
			    <img src="https://seeklogo.com/images/B/blue-style-building-logo-A8D8AEAB86-seeklogo.com.png" class="<?php echo $AddClass;?>">
			  </td>
			  <td style="width:200px;text-align: right;">
			    <br>
			    <span style="font-size: 7pt;margin-top:4px">
			  </span>
			  </td>
			  <td valign="top" style="width:8px;"></td>
			  <td valign="top" style="float: right;border:2px;text-align: center;font-size:16px;">
			    <b>
			      RUC: 2060123456 <br><br>
			      FACTURA<br>
			      ELECTR&Oacute;NICA<br>
			      F010-409<br>
			    </b>
			  </td>
			</tr>
			</table>
			<br>
			<table style="width:700px" >
			<tr>
			  <td style="width:90px;"><b>Señor(es) </b></td>
			  <td style="width:370px;">CITRIX S.A.C.</td>
			  <td style="width:90px;" align="right"><b>Moneda</b></td>
			  <td><div style="width:140px;text-align:right">SOLES</div></td>
			</tr>
			<tr>
			  <td><b>RUC</b></td>
			  <td>2060123456</td>
			  <td align="right"><b>F. Emisión</b></td>
			  <td><div style="width:140px;text-align:right"><?php echo date('d-m-Y')?></div></td>
			</tr>
			<tr>
			  <td><b>Dirección</b></td>
			  <td><div style="width: 370px;">LA CASTELLANA, SANTIAGO DE SURCO</div></td>
			  <td align="right"><b>F. Vencimiento</b></td>
			    <td><div style="width:140px;text-align:right"><?php echo date('d-m-Y')?></div></td>
			 
			</tr>

			</table>

			<br>

			<table style="width: 100%;">
			  <tr  bgcolor="<?=$colorP?>" >
			    <td style="height:18px;" bgcolor="<?=$colorP?>" border="1"  width="85"  align="center"  valign="middle"><b>CODIGO</b></td>
			   
			    <td style="height:18px;" bgcolor="<?=$colorP?>" border="1"  width="80"  align="center"  valign="middle"><b>CODIGO<br>SECUNDARIO</b></td>
			    
			    <td style="height:18px;" bgcolor="<?=$colorP?>" border="1"  width="<?php echo $widths ?>"   align="center"  valign="middle">
			      <b>DESCRIPCI&Oacute;N</b>
			    </td>
			    <td style="height:18px;" bgcolor="<?=$colorP?>" border="1"  width="50"   align="center"  valign="middle"><b>UNI</b></td>
			    <td style="height:18px;" bgcolor="<?=$colorP?>" border="1"  width="50"   align="center"  valign="middle"><b>CANT.</b></td>
			    <td style="height:18px;" bgcolor="<?=$colorP?>" border="1"  width="55"   align="center"  valign="middle"><b>V. VENTA</b></td>
			    <td style="height:18px;" bgcolor="<?=$colorP?>" border="1"  width="50"   align="center"  valign="middle"><b>IMPORTE</b></td>
			  </tr>

			    <tr>
			        <td align="center">SEC001</td>
			     
			        <td align="center"> SEC002</td>
			    
			        <td align="left">
			          <div style="width:<?php echo $widths ?>;">
			           SECADO
			            <br>
			            <!-- str_replace("<", "&lt;", $campo) -->
			            
			          </div>
			        </td>
			        <td  align="center" >  Uni</td>
			        <td align="right">  545.00</td>
			        <td align="right"> 0.930</td>
			        <td align="right">  506.85</td>
			    </tr>


			    <tr><td></td></tr>
			      <tr>
			        <td colspan="5"> <span class=""> </span> </td>
			    </tr>
			  
			    <tr>
			          <td colspan="<?php echo   $colspanx  ?>" align="center"></td>
			          <td align="right"></td>
			          <td align="right"></td>
			          <td  colspan="2" bgcolor="<?=$colorP?>" border="1" align="right"><b>SUB TOTAL S/</b></td>
			          <td><div style="width:70px;" align="right">506.85</div></td>
			    </tr>
			    <tr>
			          <td colspan="<?php echo   $colspanx  ?>" align="center"></td>
			          <td align="right"></td>
			          <td align="right"></td>
			          <td  colspan="2" bgcolor="<?=$colorP?>" border="1" align="right"><b>IGV S/</b></td>
			          <td><div style="width:70px;" align="right">91.23</div></td>
			    </tr>
			    <tr>
			          <td colspan="<?php echo   $colspanx  ?>" align="center"></td>
			          <td align="right"></td>
			          <td align="right"></td>
			          <td colspan="2" bgcolor="<?=$colorP?>" border="1" align="right"><b>TOTAL S/</b></td>
			          <td><div style="width:70px;" align="right">598.08</div></td>
			    </tr>
			  </table>
			<br>

			<span style="border-top:1px dotted #000;">SON QUINIENTOS NOVENTA Y OCHO CON 08/100 SOLES</span><br>
			<hr>


			<table border="0">


			  	<tr>
			      <td >
			          <div style="padding-left: 5px;padding-top: 10px;width:520px;text-align: left;">

			            <b>Resumen: -sajdkfnms¡=jsiadji=874h4yrnbz</b>
			            <br>
			            Representación impresa de la  Factura Electrónica F010-409<br>
			           
			            Para consultar sus comprobantes electrónicos ingrese al <a href="#" target="_blank"><b><u>Portal</u></b></a>
			            <br> <br><br>Gestionado por <a href="#" target="_blank"> LGSoft</a>

			          </div>
			      </td>
			      <td>
			        <div style="width:150px;text-align: center;">
			            <img border= "2" width="120" height="120" src="http://www.claudionasajon.com.br/wp-content/uploads/2012/04/qr-code.jpg" alt="" id="RemoveImg">
			      	</div>
			      </td>
			    </tr>
			</table>


		</div>
	</body>
</html>
