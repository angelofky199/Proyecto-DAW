/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package proyectoDawJava;

/**
 *
 * @author Usuario
 */
import java.sql.*;

public class accesoBD {

    Connection conexionBD;

    public accesoBD() {
        conexionBD = null;
    }

    public void abrirConexionBD() {
        if (conexionBD == null) { // daw es el nombre de la base de datos que hemos creado con anterioridad.
            String nombreConexionBD = "jdbc:mysql://localhost/daw_tienda_bd";
            try { // root y sin clave es el usuario por defecto que crea WAMPP.
                Class.forName("com.mysql.jdbc.Driver");
                conexionBD = DriverManager.getConnection(nombreConexionBD, "root", "");
            } catch (Exception e) {
                System.out.println("No se ha podido conectar a la BB.DD...");
            }
        }
    }

    public boolean comprobarAcceso() {
        abrirConexionBD();
        return conexionBD != null;
    }

    public ResultSet obtenerProductosBD() {
        abrirConexionBD();
        ResultSet resultados = null;
        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "SELECT id,nombre,descripcion,precio,existencias,tipo FROM productos";
            resultados = s.executeQuery(con);
        } catch (Exception e) {
            System.out.println("Error ejecutando la consulta a la BB.DD....");
        }
        return resultados;
    }

    public int existenciasProductoBD(int id) throws SQLException {
        abrirConexionBD();
        ResultSet resultados = null;
        int existencias;
        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "SELECT existencias FROM productos WHERE id = " + String.valueOf(id);
            resultados = s.executeQuery(con);
        } catch (Exception e) {
            System.out.println("Error ejecutando la consulta a la BB.DD....");
        }
        if (resultados == null) {
            existencias = 0;
        } else {
            existencias = resultados.getInt("existencias");
        }

        return existencias;
    }

    public void registrarUsuario(String nombre, String nombreUsuario, String contraseña) {
        abrirConexionBD();
        
        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "INSERT INTO usuarios(id,usuario,nombreUsuario, contraseña) VALUES (NULL,'" + nombre + "','" + nombreUsuario + "', '" +contraseña+"')";
            s.executeQuery(con);
        } catch (Exception e) {
            System.out.println("Error al insertar user a la BB.DD....");
        }
        
    }
    
     public boolean comprobarUsuario(String nombreUsuario, String pass) throws SQLException {
        abrirConexionBD();
        boolean ok = false;
        ResultSet resultados = null;
        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "SELECT contrasenya FROM usuarios WHERE nombre_usuario =" + nombreUsuario;
            resultados = s.executeQuery(con);
        } catch (Exception e) {
            System.out.println("Error ejecutando la consulta a la BB.DD....");
        }
        //String contraseña = resultados.getString("contrasenya");
        if( resultados.next())
            if(resultados.getString("contrasenya").equals(pass))
            ok = true;
        return ok;
    }

}
