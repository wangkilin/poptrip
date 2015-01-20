<div class="headArea"><img alt="logo" src="__PUBLIC__/img/logo.png"></div>
<div class="globalNav">
     <div class="<?php echo null===$TableManageIndex ? 'selected':''?>"><span class="home"><a href="{:U(GROUP_NAME.'/'.MODULE_NAME .'/index')}">Home</a></span></div>

     <volist name="ManageInfos" key="_id" id="_info">
        <if condition="($_id-1) heq $TableManageIndex">
          <div class="selected">
          <span class="virtualClass">
           <a href="{:U(GROUP_NAME.'/'.MODULE_NAME .'/index', array('tableId'=>($_id-1)))}#">{$_info.desc}</a>
          </span>
          </div>
        <else />
          <div class="">
          <span class="virtualClass">
           <a href="{:U(GROUP_NAME.'/'.MODULE_NAME .'/index', array('tableId'=>($_id-1)))}">{$_info.desc}</a>
          </span>
          </div>
        </if>
     </volist>

</div>


