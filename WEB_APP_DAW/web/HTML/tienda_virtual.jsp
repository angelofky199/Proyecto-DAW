<%-- 
    Document   : tienda_virtual
    Created on : 11-may-2017, 13:43:07
    Author     : Usuario
--%>

<%@page import="java.util.ArrayList"%>
<%@page import="proyectoDawJava.producto"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>JSP Page</title>
        <script type="text/javascript" src="./js/libCapas.js"></script>
    </head>
    <body>
        <div>
            <table>
                <tr>
                    <th>id</th><th>nombre</th><th>precio</th><th>cantidad</th>
                </tr>
                <%
                    ArrayList<producto> carrito = (ArrayList) session.getAttribute("carrito");
                    producto p = null;
                    for (int i = 0; i < carrito.size(); i++) {
                        p = carrito.get(i);
                %>
                <tr>
                    <td><%=p.getId()%></td>
                    <td><%=p.getNombre()%></td>
                    <td><%=p.getPrecio()%><td>
                    <td>
                        <form method="post" onsubmit="ProcesarForm(this, 'tienda', 'cuerpo');
                                return false;">
                            <input type="hidden" name="id" value="<%=p.getId()%>">
                            <input type="text" name="cantidad" value="<%=p.getCantidad()%>">
                            <input type="submit" value="Actualizar cantidad">
                        </form>
                    </td>
                    <td>
                        <form method="post" onsubmit="ProcesarForm(this, 'tienda', 'cuerpo');
                                return false;">
                            <input type="hidden" name="id" value="<%=p.getId()%>">
                            <input type="hidden" name="cantidad" value="0">
                            <input type="submit" value="Eliminar producto">
                        </form>
                    </td>
                </tr>
                <%
                    }
                %>
            </table>
        </div>
    </body>