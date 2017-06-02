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
    </head>
    <body>
        <div>
            <table>
                
                <%
                    
                    ArrayList<producto> carrito = (ArrayList) session.getAttribute("carrito");
                    
                    if(carrito == null)
                    {%>
                    <h1>No hay productos en el carrito</h1>  
                     <tr>
                         <th>id</th><th>nombre</th><th>precio</th><th>cantidad</th>
                    </tr>
                   <% }else{
                    producto p = null;
                    
                    for (int i = 0; i < carrito.size(); i++) {
                        p = carrito.get(i);
                    
                %>
               
                    
                <tr>
                    <td style="width: 120px"><%=p.getId()%></td>
                    <td style="width: 120px"><%=p.getNombre()%></td>
                    <td style="width: 100px"><%=p.getPrecio()%><td>
                    <td>
                        <form method="post" onsubmit="ProcesarForm(this, './tienda', 'contenido');
                                return false;">
                            <input type="hidden" name="id" value="<%=p.getId()%>">
                            <input type="text" name="cantidad" value="<%=p.getCantidad()%>"> &nbsp;&nbsp;
                            <input type="submit" value="Actualizar cantidad"> &nbsp;&nbsp;&nbsp;
                        </form>
                    </td>
                    <td>
                        <form method="post" onsubmit="ProcesarForm(this, './tienda', 'contenido');
                                return false;">
                            <input type="hidden" name="id" value="<%=p.getId()%>">
                            <input type="hidden" name="cantidad" value="0">
                            <input type="submit" value="Eliminar producto">
                        </form>
                    </td>
                </tr>
                <%
                    }
                    }
                %>
            </table>
        </div>
    </body>