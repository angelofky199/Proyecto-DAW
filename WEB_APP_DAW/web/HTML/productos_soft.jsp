<%-- 
    Document   : productos_soft
    Created on : 06-abr-2017, 13:30:22
    Author     : Usuario
--%>

<%@page import="java.sql.ResultSet"%>
<%@page contentType="text/html" import="proyectoDawJava.*" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href=../CSS/productos.css type="text/css"/>
        <script type="text/javascript" src="./js/libCapas.js"></script>
    </head>
    <body>
        <%
            accesoBD con = new accesoBD();
            ResultSet productos = con.obtenerProductosBD();
        %>

        <%
            while (productos.next()) {
                int id = productos.getInt("id");
                String nombre = productos.getString("nombre");
                float precio = productos.getFloat("precio");
                int cantidad = productos.getInt("existencias");
                String descripcion = productos.getString("descripcion");
        %>
        <div class="lista_productos">
            <div class="producto">
                <img src="recursos/logo.jpg" width="250" height="250">
                <ul>
                    <li><%=nombre%></li>
                    <li><%=precio%></li>
                    <li><%=descripcion%></li>
                </ul>
            </div>

            <div class="producto">
                <img src="recursos/logo.jpg" width="250" height="250">
                <ul>
                    <li>Precio</li>
                    <li>Descripcion</li>
                </ul>
            </div>

            <div class="producto">
                <img src="recursos/logo.jpg" width="250" height="250">
                <ul>
                    <li>Precio</li>
                    <li>Descripcion</li>
                </ul>
            </div>

            <div class="producto">
                <img src="recursos/logo.jpg" width="250" height="250">
                <ul>
                    <li>Precio</li>
                    <li>Descripcion</li>
                </ul>
            </div>

            <div class="producto">
                <img src="recursos/logo.jpg" width="250" height="250">
                <ul>
                    <li>Precio</li>
                    <li>Descripcion </li>
                </ul>
            </div>

            <div class="producto">
                <img src="recursos/logo.jpg" width="250" height="250">
                <ul>
                    <li>Precio</li>
                    <li>Descripcion</li>
                </ul>
            </div>
        </div>
    </body>
</html>
