<%-- 
    Document   : carrito
    Created on : 11-may-2017, 13:22:02
    Author     : Usuario
--%>

<%@page import="proyectoDawJava.producto"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>JSP Page</title>
    </head>
    <body>
        <%
            producto p = new producto();
            p.setId(Integer.parseInt(request.getParameter("id")));
            p.setNombre(request.getParameter("nombre"));
            p.setPrecio(Float.parseFloat(request.getParameter("precio")));
            p.setCantidad(Integer.parseInt(request.getAttribute("existencias").toString()));
        %>
        <h1>Se ha añadido/modificado en la cesta el producto</h1>
        <table>
            <tr><th>id</th><th>nombre</th><th>precio</th><th>cantidad</th></tr>
            <tr><td><%=p.getId()%></td><td><%=p.getNombre()%></td><td><%=p.getPrecio()%></td>
                <td><%=p.getCantidad()%></td></tr>
        </table>
        <table><tr>
                <td><input type="button" value="Seguir comprando" onclick="Cargar('productos.jsp', 'cuerpo');"></td>
                <td><input type="button" value="Carrito de la compra" onclick="Cargar('tienda.jsp', 'cuerpo');"></td>
            </tr></table>
    </body>