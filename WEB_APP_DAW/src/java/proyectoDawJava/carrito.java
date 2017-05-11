/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package proyectoDawJava;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 *
 * @author Usuario
 */
public class carrito extends HttpServlet {

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException, SQLException {
        response.setContentType("text/html;charset=UTF-8");
        PrintWriter out = response.getWriter();

        producto p = new producto();
        p.setId(Integer.parseInt(request.getParameter("id")));
        p.setNombre(request.getParameter("nombre"));
        p.setPrecio(Float.parseFloat(request.getParameter("precio")));
        HttpSession sesion = request.getSession(true); // Accedemos al entorno de sesión
        ArrayList<producto> carrito = (ArrayList) sesion.getAttribute("carrito"); // Carrito
        if (carrito == null) {
            carrito = new <producto> ArrayList();
            sesion.setAttribute("carrito", carrito);
        }
        int i = 0;
        while (i < carrito.size() && carrito.get(i).getId() != p.getId()) {
            i++;
        }
        if (i < carrito.size()) {
            accesoBD bd = new accesoBD();
            int existencias = bd.existenciasProductoBD(p.getId()); // Existencias del producto
            int actual = carrito.get(i).getCantidad();
            if (actual < existencias) {
                carrito.get(i).setCantidad(actual + 1);
            }
            p.setCantidad(carrito.get(i).getCantidad());
        } else {
            p.setCantidad(1);
            carrito.add(p);
        }
        request.setAttribute("cantidad", p.getCantidad());
        RequestDispatcher dispatcher = getServletContext().getRequestDispatcher("/carrito.jsp");
        dispatcher.forward(request, response);

    
    }

// <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
/**
 * Handles the HTTP <code>GET</code> method.
 *
 * @param request servlet request
 * @param response servlet response
 * @throws ServletException if a servlet-specific error occurs
 * @throws IOException if an I/O error occurs
 */
@Override
        protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        try {
            processRequest(request, response);
        } catch (SQLException ex) {
            Logger.getLogger(carrito.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * Handles the HTTP <code>POST</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
        protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        try {
            processRequest(request, response);
        } catch (SQLException ex) {
            Logger.getLogger(carrito.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
        public String getServletInfo() {
        return "Short description";
    }// </editor-fold>

}
