<%-- 
    Document   : resgistro_usuario
    Created on : 01-jun-2017, 14:15:15
    Author     : Usuario
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>JSP Page</title>
    </head>
    <body>
        <form method="post" onsubmit="ProcesarForm(this, './registro', 'contenido');
                return false;">
            <div class="formulario">
                <div>Introduce los siguientes datos:</div><br>
                <table>
                    <tr>
                        <th>Nombre</th>
                        <th><input type="text" name="nombre"><br></th>
                    </tr>
                    <tr>
                        <th>Nombre de usuario</th>
                        <th><input type="text" name="nom_usuario"></th>
                    </tr>
                    <tr>
                        <th>Correo electrónico</th>
                        <th><input type="email" name="correo"></th>
                    </tr>
                    <tr>
                        <th>Contraseña</th>
                        <th><input type="password" name="pass"></th>
                    </tr>
                    <tr>
                        <th>Repite contraseña</th>
                        <th><input type="password" name="r_pass"></th>
                    </tr>
                    <th><br></th>
                    <tr>
                        <th></th> <th><input type="submit" value="Registrarse">

                            <br>
                </table>
            </div>
        </form>
    </body>
</html>
