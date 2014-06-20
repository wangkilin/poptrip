<?php if (!defined('THINK_PATH')) exit(); $linkParams = array('tableId'=>$TableManageIndex, 'stepIndex'=>I('stepIndex', 0)); foreach($FilterSteps as $_step) { if(is_string($_step)) $_step = array($_step); foreach($_step as $__step) { $linkParams[$__step] = I($__step, ''); } } if(C('VAR_PAGE', null) && I(C('VAR_PAGE'), null)) { $linkParams[C('VAR_PAGE')] = I(C('VAR_PAGE')); } ?>
          <div class="list">
		    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                <tr><td><a href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/addItem', $linkParams);?>" class="topButtonOnMain">Add</a></td></tr>
            </table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0"
				class="table">
				<tbody>
					<tr class="header">
						<?php
 foreach($ListColumns as $_key=>$_column) { if(is_array($_column)) { $_column = $_key; } ?>
						<th><?php echo isset($ColumnTitles[$_column]) ? $ColumnTitles[$_column] : $_column;?></th>
						<?php
 }?>
						<th>&nbsp;</th>
					</tr>
					<?php
 foreach($Data as $_data) { ?>
					<tr>
					<?php
 foreach($ListColumns as $_key=>$_column) { $handleInfo = null; if(is_array($_column)) { $handleInfo = $_column; $_column = $_key; } $_data[$_column] = isset($_data[$_column]) ? $_data[$_column] : '!-!'; $_data[$_column] = isset($ReplaceMap[$_column], $ReplaceRef[$ReplaceMap[$_column]]) ? $ReplaceRef[$ReplaceMap[$_column]][$_data[$_column]] : $_data[$_column]; ?>
						<td><div><?php
 if(!isset($_data[$_column]) || ''===$_data[$_column]) { echo '&nbsp;'; } else if($handleInfo) { switch(strtolower($handleInfo['type'])) { case 'image': case 'img': echo '<img src="'.$_data[$_column].'" '.(isset($handleInfo['class']) ? (' class="'.$handleInfo['class'].'" ') : '').'/>'; break; default: echo $_data[$_column]; break; } } else { echo $_data[$_column]; } ?></div></td>
					<?php
 foreach($CrudKey as $_keyName) { $linkParams[$_keyName] = $_data[$_keyName]; } } ?>  <td><a class="editItemLink" href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/editItem', $linkParams);?>">Edit</a> &nbsp;
                            <a class="deleteItemLink" onclick="return confirmDeleteItem();" href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/deleteItem', $linkParams);?>">Delete</a>
                        <?php
 if(isset($ManageInfos[$TableManageIndex]['list']['moreLinks'])) { $moreLinks = $ManageInfos[$TableManageIndex]['list']['moreLinks']; foreach($moreLinks as $_linkInfo) { $_linkParams = array(); foreach($_linkInfo['urlParam'] as $_key=>$_value) { if(is_numeric($_key)) { $_linkParams[$_value] = I($_value, ''); } else if(in_array($_value, $CrudKey)){ $_linkParams[$_key] = $_data[$_value]; } else { $_linkParams[$_key] = $_value; } } echo '<a class="'.(isset($_linkInfo['class'])?$_linkInfo['class']:'') .'" href="'.U($_linkInfo['url'], $_linkParams).'">'.$_linkInfo['text'].'</a> &nbsp;'; } }?>
                        </td>
					</tr>
					<?php
 } ?>
				</tbody>
			</table>
			<div class="bottomArea">
				<div class="pager" style="float: right">
					<div class="paginator">
						<div class="pager">
						<?php
 echo isset($PageGuide) ? $PageGuide : ''; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script language="javascript">
		function confirmDeleteItem()
		{
			return confirm('Confirm to delete this line?');
		}
		</script>