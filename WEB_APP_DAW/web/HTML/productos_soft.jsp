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
            ResultSet productos = con.obtenerProductosSoftBD();
        %>

        <%
            if (productos == null) {
        %>
        <h2> No hay productos en la base de datos </h2>
       

        <%
        } else {
        %>
        <div class="lista_productos">
            <%
                while (productos.next()) {
                    int id = productos.getInt("id");
                    String nombre = productos.getString("nombre");
                    String descripcion = productos.getString("descripcion");
                    float precio = productos.getFloat("precio");
                    int cantidad = productos.getInt("existencias");
                    
                    /*Algoritmo para hacer un salto de linea en la descripcion 
                    cuando esta supera un limite de caracteres*/
                    
                    String h1;
                    String h2;
                    String palabra = " ";

                    if (descripcion.length() > 40) {
                        for (int i = 0; i < descripcion.length(); i++) {
                            if (i == 30) {

                                h1 = descripcion.substring(i - 30, i);

                                h2 = descripcion.substring(i, descripcion.length());

                                palabra = palabra + h1 + "\n" + h2;
                            }
                        }
                    }
            %>

            <div class="producto">
                <img src="recursos/logo.jpg" width="250" height="250">
                <ul>
                    <li><%=nombre%></li>
                    <li><%=precio%></li>
                    <li><%=palabra%></li>
                </ul><br><br><br>
            </div>
            <%}
                }%>

        </div>
    </div>
</body>
</html>
