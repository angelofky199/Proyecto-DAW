<%@page import="java.sql.ResultSet"%>
<%@page contentType="text/html" import="proyectoDawJava.*" pageEncoding="UTF-8"%>

<%
    accesoBD con = new accesoBD();
    ResultSet productos = con.obtenerProductosBD();
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
            String tipo = productos.getString("tipo");

            
            if (tipo.equals("soft")) {
                

                /*Algoritmo para hacer un salto de linea en la descripcion 
                 cuando esta supera un limite de caracteres
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
                 }*/
    %>

    <div class="col-lg-4">
        <div class="col-lg-12">
            <img class="img-rounded" src="recursos/logo.jpg" width="250" height="250" style="box-shadow: 6px 4px 3px #ccc; ">
        </div>
        <div class="col-lg-6"><h4><%=nombre%></h4></div>
        <div class="col-lg-6"><h3><%=precio%>€<h3></div>
        <div class="col-lg-12 text-justify"><p><%=descripcion%></p></div>
        <div class="col-lg-12 text-justify">
            <form method="post" onsubmit="ProcesarForm(this, 'carrito')">
            <%=nombre%>
            <%=precio%>
            <input type="submit" value="Añadir al carrito">
           
        </form>
        
        </div>
        
    </div>
                    
                <%}%>
            <%}%>
        <%}%>            </div>

