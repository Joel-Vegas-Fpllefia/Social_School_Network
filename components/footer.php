<footer>
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="footer-logo">
            <i class="fas fa-graduation-cap" aria-hidden="true"></i>
            SchoolGram
          </div>
          <p>La red social educativa que conecta colegios, estudiantes y familias.</p>
          <div class="social-icons">
            <a href="#" aria-label="Síguenos en Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
            <a href="#" aria-label="Síguenos en Twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
            <a href="#" aria-label="Síguenos en Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
            <a href="#" aria-label="Síguenos en LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="footer-links">
            <h5>Enlaces Rápidos</h5>
            <ul>
              <li><a href="index.php">Inicio</a></li>
              <li><a href="portfolio.php">Portfolio</a></li>
              <li><a href="testimonios.php">Testimonios</a></li>
              <li><a href="faqs.php">Preguntas Frecuentes</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="footer-links">
            <h5>Contacto</h5>
            <ul>
              <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Barcelona, España</li>
              <li><i class="fas fa-phone" aria-hidden="true"></i> +34 123 456 789</li>
              <li><i class="fas fa-envelope" aria-hidden="true"></i> info@schoolgram.com</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2024 SchoolGram. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Script para la clase 'scrolled' de la barra de navegación
    $(window).scroll(function() {
      if ($(document).scrollTop() > 50) {
        $('.navbar').addClass('scrolled');
      } else {
        $('.navbar').removeClass('scrolled');
      }
    });
  </script>
</body>

</html>