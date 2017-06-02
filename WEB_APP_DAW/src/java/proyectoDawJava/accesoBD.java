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
import static java.lang.System.out;
import java.sql.*;

public class accesoBD {

    Connection conexionBD;

    public accesoBD() {
        conexionBD = null;
    }

    public void abrirConexionBD() {
        if (conexionBD == null) { // daw es el nombre de la base de datos que hemos creado con anterioridad.
            String nombreConexionBD = "jdbc:mysql://localhost/daw_tienda_buena";
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
        int existencias = 0;
        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "SELECT existencias FROM productos WHERE id = " + String.valueOf(id);
            resultados = s.executeQuery(con);

            if (resultados.next()) {
                existencias = resultados.getInt("existencias");
            } else {
                existencias = 0;
            }

        } catch (Exception e) {
            System.out.println("Error ejecutando la consulta a la BB.DD....");
        }

        return existencias;
    }

    public void registrarUsuario(String nombre, String nombreUsuario, String contraseña) {
        abrirConexionBD();

        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "INSERT INTO usuarios(nombre,nombreUsuario, contrasenya,activado) VALUES ('" + nombre + "','" + nombreUsuario + "', '" + contraseña + "', '1');";
            s.executeUpdate(con);
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
            String c = "";
            Statement s = conexionBD.createStatement();
            con = "SELECT contrasenya FROM usuarios WHERE nombreUsuario LIKE '" + nombreUsuario + "';";
            resultados = s.executeQuery(con);

            if (resultados.next()) {
                c = resultados.getString("contrasenya");
            }

            if (c.equals(pass)) {
                ok = true;
            }

        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Error ejecutando la consulta a la BB.DD....");
        }
        //String contraseña = resultados.getString("contrasenya");

        return ok;
    }

    public int obtenerID(String nombreUsuario) throws SQLException {
        abrirConexionBD();

        ResultSet resultados = null;
        String con;
        int id = 0;
        try {

            Statement s = conexionBD.createStatement();
            con = "SELECT id FROM usuarios WHERE nombreUsuario LIKE '" + nombreUsuario + "';";
            resultados = s.executeQuery(con);

            if (resultados.next()) {
                id = resultados.getInt("id");
            }

        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Error ejecutando la consulta a la BB.DD....");
        }
        //String contraseña = resultados.getString("contrasenya");

        return id;
    }

    public void registrarPedido(int idCliente, String fecha, String estado, float precioTotal) {
        abrirConexionBD();

        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "INSERT INTO pedido(id_cliente, fecha, estado, importe) VALUES ('" + idCliente + "','" + fecha + "', '" + estado + "','" + precioTotal + "' );";
            s.executeUpdate(con);
        } catch (Exception e) {
            System.out.println("Error al insertar user a la BB.DD....");
        }

    }
    
     public ResultSet obtenerPedidosBD() {
        abrirConexionBD();
        ResultSet resultados = null;
        try {
            String con;
            Statement s = conexionBD.createStatement();
            con = "SELECT id, id_cliente, fecha ,estado,importe FROM pedido";
            resultados = s.executeQuery(con);
        } catch (Exception e) {
            System.out.println("Error ejecutando la consulta a la BB.DD....");
        }
        return resultados;
    }

}
