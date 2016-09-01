
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-xs-10">
      <hr style="border: none;height: 1px;background-color: #bbb;background-image: -webkit-linear-gradient(0deg, #CDE6ED, #000, #CDE6ED);">
      <h5 class="text-center" style="margin-bottom:50px">
        <p class="heart">
                       Made with Codeigniter 3 in Rayleblog v1.0
                    </p>
                    <p class="copyright">
                        © 2014-2016 Rayle | Images: <a href="https://github.com/">Rayleblog</a> & <a href="https://github.com/Rayle0801/Rayleblog">Github</a>
          </p>

      </h5>
    </div>     
  </div>
</div>
<script>
 function centerModals(){
    $('.modal').each(function(i){
        var $clone = $(this).clone().css('display', 'block').appendTo('body');    var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
        top = top > 0 ? top : 0;
        $clone.remove();
        $(this).find('.modal-content').css("margin-top", top);
    });
}
$('.modal').on('show.bs.modal', centerModals);
$(window).on('resize', centerModals);
</script>
</body>

</html>
