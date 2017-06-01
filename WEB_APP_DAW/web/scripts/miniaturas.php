<?php
function resizejpg($imagefile,$w,$h) 
{ 
	// check path to prevent illegal access to other files
	//if(substr($imagefile, 0, 1) != '.' || strstr($imagefile, "..")) {
	//echo "Acceso Ilegal!!";exit;
	//}
	
    $hw = getimagesize($imagefile);
	$ext = substr($imagefile, -3);
	
    if( $hw[2] == 1) { 
      if (!$src_img = imagecreatefromgif($imagefile)) {
        echo "Error Abriendo Archivo de Imagen!!";exit;
      }
    }
	else if( $hw[2] == 2) {
      if (!$src_img = imagecreatefromjpeg($imagefile)) {
        echo "Error Abriendo Archivo de Imagen!!";exit;
      }
    } 
	elseif ( $hw[2] == 3) {
      if (!$src_img = imagecreatefrompng($imagefile)) {
        echo "Error Abriendo Archivo de Imagen!!";exit;
      }
    }
	else {
      echo "=>".$imagefile."<=\n";
	  echo "Error Tipo de archivo no Soportado!!";exit;

    }

	if (($hw[0]/$hw[1])>($w/$h)){ 
	    $new_w = $w; 
    	$new_h = $hw["1"]/($hw["0"]/$w);
	}else{
	    $new_h = $h; 
    	$new_w = $hw["0"]/($hw["1"]/$h);
		
	}
	
	// truecolor supported only in GD 2.0 or later
    $newimage = imagecreatetruecolor($new_w, $new_h);
	imagecolortransparent($newimage, imagecolorallocatealpha($newimage, 0, 0, 0, 127));
	imagealphablending($newimage , false);
	imagesavealpha($newimage, true);
	imagecopyresampled($newimage,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
	
	/*$colorTransparent = imagecolortransparent($src_img );
    $newimage = @imagecreatetruecolor($new_w, $new_h);
    imagealphablending($newimage , false);
	imagepalettecopy($newimage,$src_img );
	imagefill($newimage,0,0,$colorTransparent);
	imagecolortransparent($newimage, $colorTransparent);
    imagecopyresampled($newimage,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));*/
 
 	if(!$newimage) {
      $newimage = imageCreate($new_w, $new_h);
    }
	
	//$pattern    = "/\d+\.(jpg|JPG)/";
	//preg_match($pattern,$imagefile,$matches);
	//$filename      = $matches[0];

    //imagejpeg($newimage); 

	if($hw[2]==1){header("Content-type: image/gif");imagegif($newimage);} 
	if($hw[2]==2){header("Content-type: image/jpeg");imagejpeg($newimage);} 
	if($hw[2]==3){header("Content-type: image/png");imagepng($newimage);} 
	
	//para dejar una copia en el servidor
	/*
 	$thumb="./imagenes/obras/peque/$filename";
	$old_w=@getimagesize($thumb);
	if (!file_exists ($thumb) || ($new_w <> $old_w[0]))
	{ 
	     imagejpeg($newimage,$thumb,90); 
	}
	*/
	
    ImageDestroy($src_img);
    //ImageDestroy($newimage);
	
	return $newimage;
}
$image=$_GET['imagen'];
$w=$_GET['ancho'];
$h=$_GET['alto'];

if (isset($image) && (isset($w) || isset($h))) {
	resizejpg($image,$w,$h);
}
?>
