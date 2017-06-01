<?php //-------------------------------------FUNCIONES ADMIN ESSENZ ------------------------------------------------

session_start();

if(!isset($_SESSION["dentro"]))$_SESSION["dentro"]=false;

if(isset($_GET['logout'])){
	session_destroy();
	header('location: index.php');
}

if (isset($_POST['entrar'])){

$link = @mysql_connect('localhost', $_POST['usuario'], $_POST['login']);
if (!$link) {
    echo '<center><font color="red" size="5">Login Incorrecto</font><center>';
}else{
$_SESSION['dentro']=true;
$_SESSION['usuario']=$_POST['usuario'];
$_SESSION['login']=$_POST['login'];
// mysql_close($link);
}
}

//-----------------------------Y.....accion!!------------------------------------------------------------------------------------------

if(!isset($_GET['bot'])) $_GET['bot']="consultar";

if($_GET['bot']=="guardar"){//----si guardar
if(guarda()){
	if(($_GET['pag']=='imagen')||($_GET['pag']=='opcion')) header('location: articulo.php?cod='.$_GET['art'].'&cat='.$_GET['cat']);
	else header('location: index.php?cat='.$_GET['cat']);
	}
else echo '<script language="Javascript">alert("No se ha podido Guardar!!");</script>';
}//------fin si guardar

if($_GET['bot']=="insertar"){//----si insertar
if(inserta()){	
	if(($_GET['pag']=='imagen')||($_GET['pag']=='opcion')) header('location: articulo.php?bot=editar&cod='.$_GET['art'].'&cat='.$_GET['cat']);
	else header('location: index.php?cat='.$_GET['cat']);
	}
else echo '<script language="Javascript">alert("No se ha podido Insertar!!");</script>';
}//------fin si insertar

if($_GET['bot']=="eliminar"){//----si eliminar
if(elimina()){
	if(($_GET['pag']=='imagen')||($_GET['pag']=='opcion')) header('location: articulo.php?bot=editar&cod='.$_GET['art'].'&cat='.$_GET['cat']);
	else header('location: index.php?cat='.$_GET['cat']);
	}
else echo '<script language="Javascript">alert("No se ha podido Eliminar!!");</script>';
}//------fin si eliminar

//------------------------------------lectura datos BD----------------------------------------------

function exe($sql){//-------conecta con la base de datos y ejecuta una sentencia pasada como argumento
mysql_connect("localhost", $_SESSION['usuario'], $_SESSION['login']) or die("No se ha podido conectar: " . mysql_error()); 
mysql_select_db("admin_essenz"); 
$dato=mysql_query($sql);//-----ejecutamos sentencia y guardamos su salida
return $dato;//-----devolvemos la salida
unset($dato);
}//------------------fin lee sentencia

function leevarios($sql){//-------conecta con la base de datos y lee una sentencia pasada como argumento que devuelve varios valores
$cont=0;
$datos=false;
$res=exe($sql);//-----ejecutamos sentencia y guardamos su salida en un array bidimensional asociativo
if($res){while ($dato=mysql_fetch_assoc($res)){
$datos[$cont]=$dato;
$cont++;
}
unset($res);
unset($dato);
return $datos;//-----devolvemos los datos en un array bidimensional asociativo
unset($datos);
}}//------------------fin lee sentencia


function lee($sql){//-------conecta con la base de datos y lee una sentencia pasada como argumento que devuelve un solo valor
$res=exe($sql);//-----ejecutamos sentencia y guardamos su salida en un array asociativo
$dato=mysql_fetch_assoc($res);
unset($res);
return $dato;//-----devolvemos los datos en un array asociativo
}//------------------fin lee sentencia

//----------------------------------zona inserciones updates y uploads------------------------
function inserta(){//-------------inserta el formulario en la tabla
if($_GET['pag']=='articulo'){
if(!empty($_FILES['img']['name']))$_POST['imagen']=subearchivo('img','../articulos/');
if(exe("INSERT INTO articulos VALUES ('".$_POST['ref']."','".$_POST['cat']."','".formatea($_POST['nombre'])."','".$_POST['imagen']."','".$_POST['precio']."','".formatea($_POST['textoesp'])."','".formatea($_POST['textoing'])."','".$_POST['activo']."')"))return true;
else return false;
}//---fin si articulo

if($_GET['pag']=='tienda'){
if(!empty($_FILES['img']['name']))$_POST['foto']=subearchivo('img','../tiendas/');
if(exe("INSERT INTO tiendas VALUES ('','".formatea($_POST['nombre'])."','".$_POST['foto']."','".formatea($_POST['direccion'])."','".$_POST['mapa']."','".$_POST['pais']."','".$_POST['provincia']."','".$_POST['cpostal']."','".$_POST['telefono']."','".$_POST['email']."','".formatea($_POST['horario'])."','".$_POST['activa']."')"))return true;
else return false;
}//---fin si tienda

if($_GET['pag']=='imagen'){
if(!empty($_FILES['img']['name']))$_POST['foto']=subearchivo('img','../articulos/');
if(exe("INSERT INTO fotos VALUES ('','".$_POST['articulo']."','".$_POST['foto']."')"))return true;
else return false;
}//---fin si fotos

if($_GET['pag']=='opcion'){
if(!empty($_FILES['img']['name']))$_POST['imagen']=subearchivo('img','../articulos/');
if(exe("INSERT INTO opciones VALUES ('','".$_POST['articulo']."','".$_POST['nombre']."','".$_POST['imagen']."','".$_POST['valor']."','".$_POST['activa']."')"))return true;
else return false;
}//---fin si fotos

}//-------------------------------------------------fin funcion inserta---

function guarda(){//--------------updatea un registro que ya existe

if($_GET['pag']=='texto'){
if(exe("UPDATE textos SET textoesp='".formatea($_POST['texesp'])."', textoing='".formatea($_POST['texing'])."' WHERE rel='".$_GET['cod']."'")) return true;
}//----fin si texto

if($_GET['pag']=='tienda'){
if(!empty($_FILES['img']['name'])){
if($_POST['foto']) borrafile($_POST['foto']);
$_POST['foto']=subearchivo('img','../tiendas/');
}
if(exe("UPDATE tiendas SET nombre='".formatea($_POST['nombre'])."', foto='".$_POST['foto']."', direccion='".formatea($_POST['direccion'])."', cpostal='".formatea($_POST['cpostal'])."', provincia='".formatea($_POST['provincia'])."', pais='".formatea($_POST['pais'])."', mapa='".formatea($_POST['mapa'])."', telefono='".$_POST['telefono']."', email='".formatea($_POST['email'])."', horario='".formatea($_POST['horario'])."', activa='".$_POST['activa']."' WHERE codigo='".$_GET['cod']."'")) return true;
}//----fin si tienda


if($_GET['pag']=='articulo'){
if(!empty($_FILES['img']['name'])){
if($_POST['imagen']) borrafile($_POST['imagen']);
$_POST['imagen']=subearchivo('img','../articulos/');
}
if (exe("UPDATE articulos SET ref='".$_POST['ref']."', cat='".$_POST['cat']."', nombre='".formatea($_POST['nombre'])."', textoesp='".formatea($_POST['textoesp'])."', textoing='".formatea($_POST['textoing'])."', imagen='".$_POST['imagen']."', precio='".$_POST['precio']."', activo='".$_POST['activo']."' WHERE ref='".$_GET['cod']."'")) return true;
else return false;
}//----fin si articulo


if($_GET['pag']=='imagen'){
if(!empty($_FILES['img']['name'])){
if($_POST['foto']) borrafile($_POST['foto']);
$_POST['foto']=subearchivo('img','../articulos/');
}
if (exe("UPDATE fotos SET foto='".$_POST['foto']."', articulo='".$_POST['articulo']."' WHERE codigo='".$_GET['cod']."'")) return true;
else return false;
}//----fin si imagen

if($_GET['pag']=='opcion'){
if(!empty($_FILES['img']['name'])){
if($_POST['imagen']) borrafile($_POST['imagen']);
$_POST['imagen']=subearchivo('img','../articulos/');
}
//if( $_POST['activa'] == "" ) $_POST['activa'] = 0;
if (exe("UPDATE opciones SET imagen='".$_POST['imagen']."', articulo='".$_POST['articulo']."', nombre='".$_POST['nombre']."', valor='".$_POST['valor']."', activa='".$_POST['activa']."' WHERE codigo='".$_GET['cod']."'")) return true;
else return false;
}//----fin si imagen

}//------------------------fin funcion guarda

function subearchivo($nom, $dir)
{//--------------------------------------------subimos el archivo por ftp---------
if(file_exists($dir)){

$archivo=$_FILES[$nom]['tmp_name'];
$archivo_remoto=$_FILES[$nom]['name'];
$ruta = $dir . $archivo_remoto ;

return $ruta; //----Quitar

/* 
$servidor_ftp='agrografic.com';
$ftp_nombre_usuario='admin';
$ftp_contrasenya='P3t4rd1ng';
$dir=preg_replace('\\.\\./','/domains/essenzvap.com/public_html/',$dir);

// configurar la conexion basica
$id_con = ftp_connect($servidor_ftp);

// iniciar sesion con nombre de usuario y contrasenya
$resultado_login = ftp_login($id_con, $ftp_nombre_usuario, $ftp_contrasenya);

if(!$resultado_login)echo '<script language="Javascript">alert("No se ha podido realizar la conexion FTP!!");</script>';

// cambia al directorio
ftp_chdir($id_con, $dir);

//---sube el archivo al servidor FTP

// trata de cargar $archivo
if (ftp_put($id_con, $archivo_remoto, $archivo, FTP_BINARY)) return $ruta;
else {
 echo '<script language="Javascript">alert("Hubo un problema durante la transferencia de '.$nom.' a '.$dir.'");</script>';
 return '';
}
// cerrar la conexion
ftp_close($id_con);
}else{
	echo '<script language="Javascript">alert("El Directorio '.$dir.' NO Existe!!");</script>';
	return false;
*/
}
}//--------------------------------------------fin subir archivo por ftp 

//------------------------------------------------zona eliminacion archivos y registros------------------------------------------

function elimina(){//---------eliminar, le pasamos la tabla y el indice.

if($_GET['pag']=='articulo'){
$dato=lee("select imagen from articulos where ref = '".$_GET['cod']."'");
if($dato['imagen'])borrafile($dato['imagen']);
if((exe("delete from articulos where ref = '".$_GET['cod']."'"))&&(exe("delete from fotos where articulo = '".$_GET['cod']."'")))return true;
else return false;
}//----fin articulo

if($_GET['pag']=='tienda'){
if(exe("delete from tiendas where codigo = '".$_GET['cod']."'"))return true;
else return false;
}//----fin tienda

if($_GET['pag']=='imagen'){
$dato=lee("select foto from fotos where codigo = '".$_GET['cod']."'");
if($dato['imagen'])borrafile($dato['foto']);
if(exe("delete from fotos where codigo = '".$_GET['cod']."'"))return true;
else return false;
}//----fin foto


}//------------------------------------------------------------------------------------fin funcion elimina
 
function borrafile($archivo){//------------------------borra un archivo, se le pasa la ruta
/*
if(file_exists($archivo)){

$servidor_ftp='agrografic.com';
$ftp_nombre_usuario='admin';
$ftp_contrasenya='P3t4rd1ng';
$dir=preg_replace('\.\./','/domains/essenzvap.com/public_html/',$dir);

// establecer conexion basica
$id_con = ftp_connect($servidor_ftp);

// iniciar sesion con nombre de usuario y contrasenya
$resultado_login = ftp_login($id_con, $ftp_nombre_usuario, $ftp_contrasenya);

// intento de eliminar $archivo
if (ftp_delete($id_con, $archivo)) return true;
else return false;

// cerrar la conexion
ftp_close($id_con);
}else return false;
*/
return true;
}//---------------------fin funcion borrar archivo

//-----------------------------------------------zona nombres, fechas y validaciones------------------------------------------

function formatea($texto){
$texto=nl2br($texto);
$texto=str_replace("\n","",$texto);
$texto=str_replace("\r","",$texto);
$texto=addslashes($texto);
//$texto=utf8_decode($texto);
return $texto;
}

function muestra($texto){
$texto=str_replace("\n","",$texto);
$texto=str_replace("\r","",$texto);
$texto=stripslashes($texto);
$texto=str_replace("<br>","\n",$texto);
$texto=str_replace("<br />","\n",$texto);
$texto=str_replace("<BR>","\n",$texto);
$texto=str_replace("<BR />","\n",$texto);
$texto=str_replace("<br/>","\n",$texto);
$texto=str_replace("<BR/>","\n",$texto);
//$texto=utf8_encode($texto);
return $texto;
}

function cambiafecha($fecha)//----------cambia una fecha dd/mm/aaaa al tipo aaaa/mm/dd para insertarlo en la base de datos
{

if ($fecha){
	$fechita=explode("-", $fecha);
	$newfecha=$fechita[2] . "-" . $fechita[1] . "-" . $fechita[0];
	date("Y-m-d", strtotime($newfecha));
	return $newfecha;
}
else return date("Y-m-d");
}//------------------------------fin cambiafecha

function muestrafecha($fecha)//-------------cambia una fecha tal como sale de la base de datos para mostrarla dd-mm-aaaa
{
$newfecha = date("d-m-Y", strtotime($fecha));
return $newfecha;
}//--------------------------fin muestra fecha

?>