<%-- 
    Document   : pedidos
    Created on : 02-jun-2017, 21:10:29
    Author     : Usuario
--%>

<%@page import="java.sql.ResultSet"%>
<%@page import="proyectoDawJava.accesoBD"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <body>
        <div>
            <table style="border-bottom-style: double">

                
                    <%
                        accesoBD bd = new accesoBD();
                        ResultSet pedidos = bd.obtenerPedidosBD();

                        if (session.getAttribute("usuario") == null) {
                    %>
                <h2> No hay pedidos en la base de datos </h2>


                <%} else {
                    if (pedidos == null) {
                %> 
                <h2> No hay pedidos en la base de datos  </h2>
                <%} else {
                        %> 
                        <th>id</th><th>nombre cliente</th><th>fecha</th><th>&nbsp&nbsp estado</th><th>&nbsp&nbsp precio total</th>
                        <%    
                    
                    while (pedidos.next()) {
                        int id = pedidos.getInt("id");
                        int id_cli = pedidos.getInt("id_cliente");
                        String fecha = pedidos.getString("fecha");
                        String estado = pedidos.getString("estado");
                        float precio = pedidos.getFloat("importe");
                %>
                <tr>
                    <td style ="width: 120px" > <%= id%></td>
                    <td style="width: 120px"><%=session.getAttribute("usuario").toString()%></td>
                    <td style="width: 100px"><%=fecha%><td>
                    <td style="width: 120px"><%=estado%><td>
                    <td style="width: 100px"><%=precio%><td>
                </tr>
                <%
                    }
                    }  
                   }  
                    
                %>

                </tr>
            </table>

        </div>
    </body>
</html>
