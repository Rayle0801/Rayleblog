<div class="menu">
    <div class="col-sm-8 col-sm-offset-2 col-xs-12"style="padding-top:15px;">

      <div class="row">
          <div class="col-sm-9">
            <ul class="nav nav-pills" style="line-height: 25px;">
             <li class="logo"/><a href="<?php echo base_url()?>"><img src="<?php echo base_url('/static/img/title.png')?>" width=170 height="25"> </a></li>
            <li class="<?php echo $cur_title[0];?>"><?php echo anchor("Articles/index","Home","")?></li>

          </div>
           <div class="col-sm-3" style="float:right;margin-top:5px">
              <form class="input-group" method="post" action='<?php echo site_url('Search/show')?>'>
                 <input type="text" class="input-medium form-control" placeholder="搜索..." name='pattern'>
                 <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                       Search
                    </button>
                 </span>
              </form><!-- /input-group -->
              
           </div><!-- /.col-lg-6 -->
          
        </div>
        <div class="row">
          <hr style="border: none;height: 1px;background-color: #bbb;background-image: -webkit-linear-gradient(0deg, #EEE, #000, #EEE);">
        </div>

      </div>
    </div>
    