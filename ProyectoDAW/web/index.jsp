<%-- 
    Document   : index
    Created on : 30-mar-2017, 12:19:02
    Author     : Usuario
--%>


<%@page contentType="text/html" import="proyectoDawJava.*" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>JSP Page</title>
    </head>
    <body>
        <%
            accesoBD con = new accesoBD();
            boolean res;
            res = con.comprobarAcceso();
        %>
        <h1><%=res%></h1>
    </body>
</html>
