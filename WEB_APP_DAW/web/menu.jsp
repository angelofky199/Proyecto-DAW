<%-- 
    Document   : menu
    Created on : 02-jun-2017, 1:37:34
    Author     : Usuario
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>

<div id="banner">
    <a style="text-decoration:none;color:white;" href="inicio.html">DropTech</a>
</div>
<div id="principal">

    <ul class="nav">
        <li><a href="#" onclick="Cargar('contacto.html', 'contenido');
                        return false"> Empresa </a></li>
        <li><a href="#"> Tienda Virtual</a>
            <ul>
                <li><a href="#" onclick="Cargar('productos_soft.jsp', 'contenido');
                                return false"> Productos software </a></li>
                <li><a href="#" onclick="Cargar('productos_hard.jsp', 'contenido');
                                return false" > Productos hardware</a></li>
            </ul>
        </li>
        <li><a href="#" onclick="Cargar('tienda_virtual.jsp', 'contenido');
                        return false" > Cesta de la compra</a></li>
        <li><a href="" > Usuario</a>

            <ul>
                <li><a href="" onclick="Cargar('login_usuario.jsp', 'contenido');
                                return false"> Iniciar Sesión </a></li>
                <li><a href="" onclick="Cargar('registro_usuario.jsp', 'contenido');
                                return false"> Registrarse </a></li>
            </ul>
        </li>
        <li><a href="" onclick="Cargar('login_mantenimiento.html', 'contenido');
                        return false"> Administrador </a>
        </li>
      
        <li> <a href=""> Sesion inciada por: &nbsp;<%=session.getAttribute("usuario")%> </a>
            
             <ul>
                <li><a href="" onclick="Cargar('pedidos.jsp', 'contenido');
                        return false"> Mis pedidos </a>
            </ul>
            

        </li>
        <ul>
                <li><a href="" onclick="<% session.setAttribute("usuario", null);%>"> Cerrar Sesión </a></li>
       </ul>
        
    </ul>

</div>


