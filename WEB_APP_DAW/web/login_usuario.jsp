<%-- 
    Document   : login_usuario
    Created on : 31-may-2017, 17:18:52
    Author     : Usuario
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<form method="post" onsubmit="ProcesarForm(this, './login', 'contenido');
        return false;">
    <%
        
        if (request.getSession().getAttribute("usuario") == null) { 
            %>
            <script>alert("Hola");</script>
            <%
        }
    %>
    
    
    <div>Introduce tu nombre de usuario y contraseña</div><br>
    <table>
        <tr>
            <th>Usuario</th>
            <th><input type="text" name="usuario" ><br></th>
        </tr>
        <tr>
            <th>Contraseña</th>
            <th><input type="password" name="pass"></th>
        </tr>  
        <th><br></th>
        <tr>
            <th></th> <th><input type="submit" value="Inciar Sesion">

                <br>
    </table>

</form>
