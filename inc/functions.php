<?php
/* ################################################################################
    Functions file with spanish names & comments.
################################################################################ */


/**
* Función que encripta una password utilizando SHA-512 y 10000 vueltas por defecto
* 
* @param string $password
* @param int $vueltas Número de vueltas, opcional. Por defecto 10000.
* @return string
*/
function encriptar($password, $vueltas = 10000) {
    // Empleamos SHA-512 con 10000 vueltas por defecto.
    $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);

    return crypt((string) $password, '$6$' . "rounds=$vueltas\$$salt\$");
}


/**
* Función que comprueba si la contraseña insertada por el usuario es la correcta.
* @param string $passRecibidaFormulario
* @param string $passwordAlmacenadaEncriptada
*/
function comprobarPassword($passRecibidaFormulario, $passwordAlmacenadaEncriptada) {
    if (hash_equals(crypt($passRecibidaFormulario, $passwordAlmacenadaEncriptada), $passwordAlmacenadaEncriptada))
        return true;
    return false;
}


/**
 * @param array Se le pasa un array y lo devuelve filtrado.
 */
function limpiarFiltrar($arrayFormulario)
{
    foreach ($arrayFormulario as $campo => $valor)
    {
        $arrayFormulario[$campo]=htmlspecialchars(trim($arrayFormulario[$campo]));
    }

    return $arrayFormulario;
}


/**
 * Función que comprueba los campos obligatorios de un formulario y los filtra.
 * @param array [Array con los campos que se consideran obligatorios. Ejemplo: array('nombre','apellidos','dni') ]
 * @param metodoRecepcion   post|get
 */

function camposObligatoriosOK($arrayCamposObligatorios,$metodoRecepcion)
{

    if (strtoupper($metodoRecepcion)=='POST')
        $arrayRecibidos=&$_POST;
    else
        $arrayRecibidos=&$_GET;

    for($i=0; $i<count($arrayCamposObligatorios); $i++)
    {
        $campo=$arrayCamposObligatorios[$i];

        if (!isset($arrayRecibidos[$campo]))
            return false;

        if (isset($arrayRecibidos[$campo]) && $arrayRecibidos[$campo]=='')
            return false;
    }

    return true;
}


/**
 * Función que devuelve el mensaje flash pedido.
 */
function flashMsg($msg, $alertType) {
    return "<div class=\"alert {$alertType}\" role=\"alert\">{$msg}</div>";
}


/**
 * Función que sube la imagen al servidor.
 */
function subirFichero($directorio, $fichero) {
    $fileName = $_FILES[$fichero]['name'];
    $md5 = md5(time().$fileName);
    $ext = explode(".", $fileName);
    $lowext = strtolower(end($ext));
    $newFileName = $_SESSION['id']."_".$md5.'.'.$lowext;
    $_SESSION['fichero'] = $fichero;
    if(move_uploaded_file($_FILES[$fichero]['tmp_name'], $directorio.$newFileName))
        return $newFileName;
    else
        return false;
}


/**
 * Función que convierte la fecha.
 */
function convertirFecha($fecha) {
    $fecha = explode("-", $fecha);
    if (strlen($fecha[0]) > 2)
        return $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
    return $fecha[0].'/'.$fecha[1].'/'.$fecha[2];
}
