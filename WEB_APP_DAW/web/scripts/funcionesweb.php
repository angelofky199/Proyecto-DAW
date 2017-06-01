<?php //---FUNCIONES WEB ESSENZ

session_start();

if (!isset($_SESSION['pedido']['envio_zona_gastos'])) {
    $_SESSION['pedido']['envio_zona_gastos'] = "5";
}
$ezg = filter_input(INPUT_GET, 'ezg');

if (isset($ezg) && ($ezg == 0 || $ezg == 7 || $ezg == 8)) {
    $_SESSION['pedido']['envio_zona_gastos'] = $ezg;
    calculaimporte();
}

function existe($nombre)
{
    $valor = "";
    if (isset($_GET[$nombre])) {
        $valor = $_GET[$nombre];
        $valor = trim($valor);
        if (empty($valor)) $valor = "";
    }
    return $valor;
}

//--------------------recibe las ordenes de la pagina-----------------------------------------------------------

if (!isset($_SESSION['pedido']['importe'])) $_SESSION['pedido']['importe'] = 0;
if (!isset($_SESSION['pedido']['numart'])) $_SESSION['pedido']['numart'] = 0;

if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'esp';
if (existe('lang')) $_SESSION['lang'] = $_GET['lang'];

if (existe('act') == "comprar") {
    if (isset($_GET['opc'])) {
        introduce_articulo($_GET['ref'], $_GET['opc']);
        $cab = "location:articulo.php?comprado=true&ref=" . $_GET['ref'] . '&opc=' . $_GET['opc'];
    } else {
        introduce_articulo($_GET['ref'], NULL);
        $cab = "location:articulo.php?comprado=true&ref=" . $_GET['ref'];
    }
    header($cab);
}

if (existe('act') == "modificar") {
    if ($_POST['eliminar']) $_POST['unidades'] = 0;
    if (isset($_GET['opc'])) modifica_unidades($_GET['ref'], $_POST['unidades'], $_GET['opc']);
    else modifica_unidades($_GET['ref'], $_POST['unidades'], false);
    header("location:cesta.php");
}

if (isset($_POST['pago'])) $_SESSION['pedido']['pagado'] = $_POST['pago'];

//----------------------------conexion y lectura de la base de datos y carrito-----------------------------------------------

function texto($rel)
{
    $res = lee("select texto" . $_SESSION['lang'] . " from textos where rel='" . $rel . "'");
    return $res['texto' . $_SESSION['lang']];
    unset($res);
}

function exe($sql)
{//-------conecta con la base de datos y ejecuta una sentencia pasada como argumento
    mysql_connect("localhost", "admin_essenz", "3S53nc14") or die("No se ha podido conectar: " . mysql_error());
    mysql_select_db("admin_essenz");
    $dato = mysql_query($sql);//-----ejecutamos sentencia y guardamos su salida
    if ($dato) {
        return $dato;//-----devolvemos la salida
        unset($dato);
        mysql_close();
    } else return false;
}//------------------fin lee sentencia

function leevarios($sql)
{//-------conecta con la base de datos y lee una sentencia pasada como argumento que devuelve varios valores
    $cont = 0;
    $datos = false;
    $res = exe($sql);//-----ejecutamos sentencia y guardamos su salida en un array bidimensional asociativo
    if ($res) {
        while ($dato = mysql_fetch_assoc($res)) {
            $datos[$cont] = $dato;
            $cont++;
        }
        unset($res);
        unset($dato);
        return $datos;//-----devolvemos los datos en un array bidimensional asociativo
        unset($datos);
    }
}//------------------fin lee sentencia

function lee($sql)
{//-------conecta con la base de datos y lee una sentencia pasada como argumento que devuelve un solo valor
    $res = exe($sql);//-----ejecutamos sentencia y guardamos su salida en un array asociativo
    if ($res) {
        $dato = mysql_fetch_assoc($res);
        unset($res);
        return $dato;//-----devolvemos los datos en un array asociativo
        unset($dato);
    }
}//------------------fin lee sentencia

function introduce_articulo($articulo, $opcion)
{//------introduce un articulo en el carrito, luego aumenta en 1 el numero de articulos
    $esta = false;
    $clave = 0;
    if (isset($opcion)) {
        $opc = lee("select nombre from opciones where codigo='" . $opcion . "'");
        if (count($_SESSION['linped']) > 0) {
            foreach ($_SESSION['linped'] as $i => $linea) {
                if (($articulo == $linea['articulo']) && ($opc['nombre'] == $linea['opcion'])) {
                    $esta = true;
                    $clave = $i;
                }
            }
        } else $_SESSION['pedido']['numart'] = 0;
    } else {
        if (count($_SESSION['linped']) > 0) {
            foreach ($_SESSION['linped'] as $i => $linea) {
                if ($articulo == $linea['articulo']) {
                    $esta = true;
                    $clave = $i;
                }
            }
        } else $_SESSION['pedido']['numart'] = 0;
    }
    if ($esta) {//-----si el articulo ya esta en el carrito aumentamos las unidades en uno
        $_SESSION['linped'][$clave]['unidades'] = ($_SESSION['linped'][$clave]['unidades'] + 1);
    } else {//---------si no esta añadimos una linea nueva
        $_SESSION['linped'][$_SESSION['pedido']['numart']]['articulo'] = $articulo;
        $_SESSION['linped'][$_SESSION['pedido']['numart']]['unidades'] = 1;
        $art = lee("select precio, nombre, imagen, price_amount, price_percentage  from articulos where ref='" . $articulo . "'");
        if (isset($opcion)) {
            $opc = lee("select nombre, imagen from opciones where codigo='" . $opcion . "'");
            $_SESSION['linped'][$_SESSION['pedido']['numart']]['imagen'] = $opc['imagen'];
            $_SESSION['linped'][$_SESSION['pedido']['numart']]['opcion'] = $opc['nombre'];
        } else {
            $_SESSION['linped'][$_SESSION['pedido']['numart']]['imagen'] = $art['imagen'];
            $_SESSION['linped'][$_SESSION['pedido']['numart']]['opcion'] = '';
        }
        include_once './class/article.php';
        $article = new article($art['precio'], $art['price_amount'], $art['price_percentage']);

        $_SESSION['linped'][$_SESSION['pedido']['numart']]['precio'] = number_format($article->getPriceFinal(), 2);
        $_SESSION['linped'][$_SESSION['pedido']['numart']]['nombre'] = $art['nombre'];
        $_SESSION['pedido']['numart'] += 1;
    } //-----------fin else
    calculaimporte();
}//------fin introduce un articulo


function modifica_unidades($art, $uds, $opc)
{//--------funcion updatea, cambia las unidades de un articulo existente
    if (existe($opc)) {
        $opcion = lee("select nombre from opciones where codigo='" . $opc . "'");
        foreach ($_SESSION['linped'] as $i => $linea) {
            if ((($art == $linea['articulo']) && ($opcion['nombre'] == $linea['opcion']))) {
                $_SESSION['linped'][$i]['unidades'] = $uds;
                if ($uds == 0) {
                    unset($_SESSION['linped'][$i]);
                    $_SESSION['pedido']['numart'] -= 1;
                }
            }
        }//---fin for
    } else {
        foreach ($_SESSION['linped'] as $i => $linea) {
            if ($art == $linea['articulo']) {
                $_SESSION['linped'][$i]['unidades'] = $uds;
                if ($uds == 0) {
                    unset($_SESSION['linped'][$i]);
                    $_SESSION['pedido']['numart'] -= 1;
                }
            }
        }//---fin for
    }
    calculaimporte();
}//---fin modifica uds

function calculaimporte()
{//----recalcula el importe total tras una insercion, borrado o actualizacion y los gastos de envío
    $_SESSION['pedido']['importe'] = 0;
    foreach ($_SESSION['linped'] as $linea) $_SESSION['pedido']['importe'] += $linea['precio'] * $linea['unidades'];
    $_SESSION['pedido']['numart'] = count($_SESSION['linped']);

    if ($_SESSION['pedido']['envio_zona_gastos'] == 0) {
        if ($_SESSION['pedido']['importe'] < 100) {
            $_SESSION['pedido']['envio'] = 0;
            $_SESSION['pedido']['genvio'] = 'Consultar';
        } else {
            $_SESSION['pedido']['envio'] = 0;
            $_SESSION['pedido']['genvio'] = 'Gratis!!';
        }
    } elseif ($_SESSION['pedido']['envio_zona_gastos'] == 7) {
        if ($_SESSION['pedido']['importe'] < 50) {
            $_SESSION['pedido']['envio'] = 7;
            $_SESSION['pedido']['genvio'] = $_SESSION['pedido']['envio'] . '&nbsp;Euros';
        } else {
            $_SESSION['pedido']['envio'] = 0;
            $_SESSION['pedido']['genvio'] = 'Gratis!!';
        }
    }
}//-------fin calcula importe y gastos de envío

function mete_datos()
{//-----------------------mete los datos del formulario en pedido
    $_SESSION['pedido']['nombre'] = $_POST['nombre'];
    $_SESSION['pedido']['apellidos'] = $_POST['apellidos'];
    $_SESSION['pedido']['direccion'] = $_POST['direccion'];
    $_SESSION['pedido']['cpostal'] = $_POST['cpostal'];
    $_SESSION['pedido']['poblacion'] = $_POST['poblacion'];
    $_SESSION['pedido']['provincia'] = $_POST['provincia'];
    $_SESSION['pedido']['pais'] = $_POST['pais'];
    $_SESSION['pedido']['telefono'] = $_POST['telefono'];
    $_SESSION['pedido']['email'] = $_POST['email'];
    $_SESSION['pedido']['pedido'] = $_POST['email'];
    $_SESSION['pedido']['fecha'] = date("Y-m-d");
    $_SESSION['pedido']['fpago'] = $_POST['fpago'];
    $_SESSION['pedido']['comentarios'] = $_POST['comentarios'];
    $_SESSION['pedido']['comocon'] = $_POST['comocon'];
    if (isset($_POST['clausula'])) $_SESSION['pedido']['clausula'] = $_POST['clausula'];
    $_SESSION['pedido']['pagado'] = NULL;
}//--------fin mete datos


function terminapedido()
{//-----------------realiza el pedido

    enviapedido();//-------enviamos pedido por email
    enviaconfirmacion();//--enviamos email de confirmacion al cliente

    exe("UPDATE pedidos SET terminado='1' WHERE  codigo='" . $_SESSION['pedido']['codigo'] . "'");

    session_destroy();//---reseteamos la sesion

    echo '<script type="text/javascript">
<!--
parent.$("cesta").innerHTML="Cesta:&nbsp;0&euro;";
--></script>';

}//-------------------fin realizapedido


function insertapedido()
{//----inserta el formulario en la BD
    mysql_connect("localhost", "admin_essenz", "3S53nc14") or die("No se ha podido conectar: " . mysql_error());
    mysql_select_db("admin_essenz");
    $fecha = date("Y-m-d");
    $sql = "INSERT INTO pedidos VALUES ('','" . $_SESSION['pedido']['nombre'] . "','" . $_SESSION['pedido']['apellidos'] . "','" . $_SESSION['pedido']['email'] . "','" . $_SESSION['pedido']['cpostal'] . "','" . $_SESSION['pedido']['direccion'] . "','" . $_SESSION['pedido']['poblacion'] . "','" . $_SESSION['pedido']['provincia'] . "','" . $_SESSION['pedido']['pais'] . "','" . $_SESSION['pedido']['telefono'] . "','" . $_SESSION['pedido']['fpago'] . "','" . $fecha . "','" . $_SESSION['pedido']['comentarios'] . "','" . $_SESSION['pedido']['importe'] . "','" . $_SESSION['pedido']['comocon'] . "','0')";
    mysql_query($sql);
    $_SESSION['pedido']['codigo'] = mysql_insert_id();
    mysql_close();
    if ($_SESSION['linped']) foreach ($_SESSION['linped'] as $linea) {
        exe("INSERT INTO linped VALUES ('','" . $_SESSION['pedido']['codigo'] . "','" . $linea['articulo'] . "','" . $linea['opcion'] . "','" . $linea['unidades'] . "')");
    }
}//----fin inserta pedido

if (!function_exists('htmlspecialchars_decode')) {
    function htmlspecialchars_decode($text)
    {
        return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
    }
}

function caracteres_html($texto)
{
    $texto = htmlentities($texto, ENT_NOQUOTES, 'UTF-8'); // Convertir caracteres especiales a entidades
    $texto = htmlspecialchars_decode($texto, ENT_NOQUOTES); // Dejar <, & y > como estaban
    return $texto;
}

function valida()
{//------valida el formulario
    $mal = false;
    $_SESSION["error"] = '';
    if (!$_SESSION['pedido']['nombre']) {
        $mal = true;
        $_SESSION["error"]['nombre'] = true;
    }
    if (!$_SESSION['pedido']['apellidos']) {
        $mal = true;
        $_SESSION["error"]['apellidos'] = true;
    }
    if (!$_SESSION['pedido']['direccion']) {
        $mal = true;
        $_SESSION["error"]['direccion'] = true;
    }
    if (!$_SESSION['pedido']['cpostal']) {
        $mal = true;
        $_SESSION["error"]['cpostal'] = true;
    }
    if (!$_SESSION['pedido']['poblacion']) {
        $mal = true;
        $_SESSION["error"]['poblacion'] = true;
    }
    if (!$_SESSION['pedido']['telefono']) {
        $mal = true;
        $_SESSION["error"]['telefono'] = true;
    }
    if (!$_SESSION['pedido']['email']) {
        $mal = true;
        $_SESSION["error"]['email'] = true;
    }
    if (!$_SESSION['pedido']['provincia']) {
        $mal = true;
        $_SESSION["error"]['provincia'] = true;
    }
    if (!$_SESSION['pedido']['pais']) {
        $mal = true;
        $_SESSION["error"]['pais'] = true;
    }
    if (!isset($_SESSION['pedido']['clausula'])) {
        $mal = true;
        $_SESSION["error"]['clausula'] = true;
    }
    if (($_SESSION['pedido']['pagado']) && ($_SESSION['pedido']['pagado'] == 'ko')) {
        $mal = true;
        $_SESSION["error"]['pagado'] = true;
    }
    return $mal;
}//------------fin valida


function enviaconfirmacion()
{

    $mensaje = '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body><br>
<font size="3" color="#000">
Hola ' . $_SESSION['pedido']['nombre'] . ', esto solo es una confirmaci&oacute;n de que tu pedido N&deg; ' . $_SESSION['pedido']['codigo'] . ' ha sido enviado correctamente.<br><br>
En breve un comercial nuestro se pondr&aacute; en contacto contigo para confirmar<br>
que tenemos disponibles los art&iacute;culos en stock e informarte de como realizar el pago.<br><br>
No envies Dinero hasta que te confirmemos la disponibilidad.<br><br>
El pedido no saldr&aacute; hasta que confirmemos la direccion por telefono o email.<br><br></font>
<font size="4" color="#000">Saludos.<br></font>
<a href="http://www.essenzvap.com">
<img src="http://www.essenzvap.com/imagenes/logo2-negro.png" height="80" width="400" alt="EssenZ" hspace=0 
align=baseline border=0 ></a>
</body>
</html>';

    $mensaje = caracteres_html($mensaje);

    @mail($_SESSION['pedido']['email'], "Realizado Pedido Nº " . $_SESSION['pedido']['codigo'] . " Essenz.", $mensaje, "FROM:pedidosweb@essenzvap.com\nReply-To:pedidosweb@essenzvap.com\nMIME-Version: 1.0\nContent-type: text/html\n");

}//---fin enviaconfirmacion


function enviapedido()
{

    $direccion = "pedidosweb@essenzvap.com";//------------formateamos el email------------
    $asunto = 'Pedido Nº ' . $_SESSION['pedido']['codigo'] . ' de: ' . $_SESSION['pedido']['nombre'] . ' ' . $_SESSION['pedido']['apellidos'];
    $de = $_SESSION['pedido']['email'];
    $mensaje = '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body >
<font size="4" face="Georgia" color=":#999" style="font-weight:bold"><p>PEDIDO Nº ' . $_SESSION['pedido']['codigo'] . '  Essenz</p></font>
<font size="4" face="Georgia" color="black" style="font-weight:bold"><p>Datos Cliente:</p></font>
<font size="3" face="Georgia" color="black">
Nombre&nbsp;:&nbsp;' . $_SESSION['pedido']['nombre'] . ' <p>
Apellidos&nbsp;:&nbsp;' . $_SESSION['pedido']['apellidos'] . '<p>
Direccion&nbsp;:&nbsp;' . $_SESSION['pedido']['direccion'] . '<p>
' . $_SESSION['pedido']['cpostal'] . '&nbsp;-&nbsp;' . $_SESSION['pedido']['poblacion'] . '<p>
' . $_SESSION['pedido']['provincia'] . '&nbsp;-&nbsp;' . $_SESSION['pedido']['pais'] . '<p>
Telefono&nbsp;:&nbsp;' . $_SESSION['pedido']['telefono'] . '<p>
E-mail&nbsp;:&nbsp;<a href="mailto:' . $_SESSION['pedido']['email'] . '">' . $_SESSION['pedido']['email'] . '</a><p>
Idioma&nbsp;:&nbsp;' . $_SESSION['lang'] . '<p>
Forma de Pago&nbsp;:&nbsp;' . $_SESSION['pedido']['fpago'] . '<p>
Forma de Env&iacute;o&nbsp;:&nbsp;Agencia<p>
Idioma&nbsp;:&nbsp;' . $_SESSION['lang'] . '<p>
Comentarios&nbsp;:&nbsp;' . $_SESSION['pedido']['comentarios'] . '<p></font>
<font size="4" face="Georgia" color="black" style="font-weight:bold"><p>Pedido:</p></font>
<p align="justify">';
    if ($_SESSION['linped']) foreach ($_SESSION['linped'] as $i => $linea) {
        $mensaje .= '
<font size="3" face="Georgia" color="black">-&nbsp;' . ($i + 1) . ':&nbsp;' . $linea['articulo'] . '&nbsp;-&nbsp;' . $linea['nombre'] . '&nbsp;-&nbsp;' . $linea['opcion'] . ' &nbsp;-&nbsp; Uds:&nbsp;</font>
<font size="3" face="Georgia" color="red" style="font-weight:bold">' . $linea['unidades'] . '</font>
<font size="3" face="Georgia" color="black">&nbsp;-&nbsp;Precio unidad:&nbsp;</font>
<font size="3" face="Georgia" color="red" style="font-weight:bold">' . $linea['precio'] . '</font>
<font size="3" face="Georgia" color="black" >&nbsp;-&nbsp;Precio&nbsp;total:&nbsp;</font>
<font size="3" face="Georgia" color="red" style="font-weight:bold">' . ($linea['precio'] * $linea['unidades']) . '&nbsp;</font><br />';
    }//-------fin foreach
    $mensaje .= '</p>
<font size="3" face="Georgia" color="black">Importe&nbsp;:&nbsp;</font>
<font size="3" face="Georgia" color="red" style="font-weight:bold">' . $_SESSION['pedido']['importe'] . '&nbsp;Euros<br /><br /></font>
<font size="3" face="Georgia" color="black">Gastos de env&iacute;o&nbsp;:&nbsp;</font>
<font size="3" face="Georgia" color="red" style="font-weight:bold">' . $_SESSION['pedido']['genvio'] . '&nbsp;<br /><br /></font>
<font size="3" face="Georgia" color="black">Importe Total del Pedido&nbsp;:&nbsp;</font>
<font size="3" face="Georgia" color="red" style="font-weight:bold">' . ($_SESSION['pedido']['importe'] + $_SESSION['pedido']['envio']) . '&nbsp;Euros<br /><br /></font>
<font size="3" face="Georgia" color="black">Como nos Conociste&nbsp;:&nbsp;</font>
<font size="3" face="Georgia" color="#999" style="font-weight:bold">' . $_SESSION['pedido']['comocon'] . '<br /><br /></font>
</body>
</html>';

    $headers = "From: " . $de . "\nReply-To: " . $de . "\nMIME-Version: 1.0\nContent-type: text/html\n";

    $mensaje = caracteres_html($mensaje);

    @mail($direccion, $asunto, $mensaje, $headers);//----y lo enviamos
}


//---------------------------------contacto------------------------

if (existe('enviar')) {
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $fecha = getdate();

    $hora = $fecha["hours"] . ":" . $fecha["minutes"] . " Hs.";
    $fecha = $dias[$fecha["wday"]] . ", " . $fecha["mday"] . " de " . $meses[$fecha["mon"] - 1] . " de " . $fecha["year"] . " ";

    $para = 'info@essenzvap.com';
    $asunto = 'Mensaje de ' . $_POST['nombre'];

    $mensaje = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
</head>
<body>
<table align='center' width='350' border='1' cellpadding='5' cellspacing='0' bordercolor='#1FA22E' bgcolor='#FFFFFF'>
  <tr  bgcolor='#1FA22E'> 
      <td colspan='2' align='center'><strong><font color='#FFFFFF' size='3' face='Arial'>Mensaje enviado desde Pagina Web</font></strong></td>
    </tr>
    <tr> 
      <td width='84' bgcolor='#1FA22E'><font face='Arial' size='2' color='#FFFFFF'>Nombre</font></td>
      <td width='252'><strong><font color='#000' size='2' face='Arial'>" . $_POST['nombre'] . "</font></strong></td>
    </tr>
    <tr> 
      <td><font color='#FFFFFF' size='2' face='Arial'>Tel&eacute;fono</font></td>
      <td><strong><font color='#000' size='2' face='Arial'>" . $_POST['telefono'] . "</font></strong></td>
    </tr>
    <tr> 
      <td><font face='Verdana' size='2' color='#FFFFFF'>Mail</font></td>
      <td><a href='mailto:" . $_POST['email'] . "'><strong><font color='#000' size='2' face='Arial'>" . $_POST['email'] . "</font></strong></a></td>
    </tr>
     <tr> 
      <td><font face='Verdana' size='2' color='#FFFFFF'>Fecha</font></td>
      <td><strong><font color='#000' size='2' face='Arial'>" . $fecha . "</font></strong></td>
    </tr>
    <tr> 
      <td><font face='Verdana' size='2' color='#FFFFFF'>Hora</font></td>
      <td><strong><font color='#000' size='2' face='Arial'>" . $hora . "</font></strong></td>
    </tr>
    <tr bgcolor='#1FA22E'> 
      <td colspan='2' align='center'><font color='#FFFFFF' size='3' face='Arial'>Mensaje</font></td>
    </tr>
    <tr> 
      <td colspan='2'><font color='#000' size='2' face='Arial'><strong>" . $_POST['comentario'] . "</strong></font></td>
    </tr>
  </table>
 </body>
</html>";

    $cabeceras = "FROM:Web Essenz <info@essenzvap.com>\nReply-To: " . $_POST['email'] . "\nMIME-Version: 1.0\nContent-type: text/html; charset=utf-8\n";

    $mensaje = caracteres_html($mensaje);

    @mail($para, $asunto, $mensaje, $cabeceras);

    $fecha = date("Y-m-d");
    exe("INSERT INTO mensajes VALUES ('','" . $fecha . "','" . $_POST['nombre'] . "','" . $_POST['telefono'] . "','" . $_POST['email'] . "','" . $_POST['comentario'] . "')");
}
?>
