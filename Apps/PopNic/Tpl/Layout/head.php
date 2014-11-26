<div class="headArea"><img alt="logo" src="__PUBLIC__/img/logo.png"></div>
<div class="globalNav">
     <div class="<?php echo null===$TableManageIndex ? 'selected':''?>"><span class="home"><a href="{:U(GROUP_NAME.'/'.MODULE_NAME .'/index')}">Home</a></span></div>
     <foreach name="ManageInfos" key="_id" item="_info">
        <if condition="(NULL nheq $TableManageIndex) AND ($_id eq $TableManageIndex)">
          <div class="selected">
          <span class="virtualClass">
            <a href="{:U(GROUP_NAME.'/'.MODULE_NAME .'/manageTable', array('tableId'=>$_id))}#">{$_info.title}</a></div>
          </span>
        <else />
          <div class="">
            <span class="virtualClass">
            <a href="{:U(GROUP_NAME.'/'.MODULE_NAME .'/manageTable', array('tableId'=>$_id))}" class="menuLink">
            {$_info.title}
            </a>
            </span>
          </div>
        </if>
     </foreach>
     <!--
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
     -->

</div>


