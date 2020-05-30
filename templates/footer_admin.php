

		</main>
 	</div>
</div>



<script src="https://code.jquery.com/jquery-3.3.1.js" ></script>
<!--<script src="/shoppingapp/static/datatables/jquery.dataTables.js"></script>
<script src="/shoppingapp/static/datatables/dataTables.bootstrap4.js"></script>-->


<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>


$(document).ready(function() {
  $('#dataTable').DataTable();
});

/*

$(function() {
  
  // elementos de la lista
  var menues = $(".navbar-nav li a"); 

  // manejador de click sobre todos los elementos
  menues.click(function(e) {
     // eliminamos active de todos los elementos
     e.preventDefault();
     menues.removeClass("active");
     console.log(menues.prevObject.URL);
     // activamos el elemento clicado.
     $(this).addClass("active");
  });

});
$('#navbarResponsive > ul>li>a').click(function ver(e) {

    var $this = $(this);
    $this.parent().siblings().removeClass('active').end().addClass('active');
    e.preventDefault();
    $('#navbarResponsive').load($this.href());
});*/



/*$(document).ready(function(){
   $("#idpaises").click(function(evento){
      evento.preventDefault();
      $("#LogoaAdmin").hide();
      $("#caja").load("/shoppingapp/Paises/");
   });
   $("#idciudades").click(function(evento){
      evento.preventDefault();
      $("#LogoaAdmin").hide();
      $("#caja").load("/shoppingapp/Ciudades/");

   });
})*/

</script>
<footer class="bg-dark">
    <div class="copyright text-center text-white">
      Copyright &copy; 2017 <span>Shoppingapp</span>
    </div>
  </footer>
</body>
</html>
<script type="text/javascript">
function googleTranslateElementInit() {
	new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'es,ca,eu,gl,en,fr,it,pt,de', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true}, 'google_translate_element');
        }
</script>

<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </div>