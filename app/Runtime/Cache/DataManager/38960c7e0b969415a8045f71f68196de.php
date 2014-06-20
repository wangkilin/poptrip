<?php if (!defined('THINK_PATH')) exit(); $tableInfo = $ManageInfos[$TableManageIndex]; $linkParams = array('tableId'=>$TableManageIndex, 'stepIndex'=>I('stepIndex', 0)); foreach($FilterSteps as $_step) { if(is_string($_step)) $_step = array($_step); foreach($_step as $__step) { $linkParams[$__step] = I($__step, ''); } } ?>         <div class="list">
            <form action="<?php echo U(GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME, array('tableId'=>$TableManageIndex));?>" method="post">
		    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                <tr><td><a href="<?php echo U(GROUP_NAME.'/'.MODULE_NAME .'/manageTable', $linkParams);?>" class="topButtonOnMain">Return</a></td></tr>
            </table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0"
				class="table">
				<tbody>
					<tr class="header">
						<th colspan="2"><div>Edit <?php echo $tableInfo['title'];?></div></th>
					</tr>
					<?php
 foreach($TableFields as $_field) { if(isset($EditInfo['appendBefore'],$EditInfo['appendBefore'][$_field['Field']])) { foreach($EditInfo['appendBefore'][$_field['Field']] as $_selectName=>$_appendStep) { echo '<tr>'; echo '<td class="editLable">'; echo isset($tableInfo['columnTitles'][$_selectName]) ? $tableInfo['columnTitles'][$_selectName] : $_selectName; echo ' : </td>'; echo '<td class="editContent">'; echo '<select name="'.$_selectName.'" id="'.$_selectName.'" '.(in_array($_selectName, $CrudFieldChild)?('parent="'.array_search($_selectName, $CrudFieldChild).'"'):''); if(isset($tableInfo['steps'][$_appendStep]['ajax'])) { $ajaxParams = array(); foreach($tableInfo['steps'][$_appendStep]['ajax']['urlParam'] as $_ajaxKey=>$_ajaxValue) { if(is_numeric($_ajaxKey)) { $ajaxParams[$_ajaxValue] = I($_ajaxvalue, ''); } else { $ajaxParams[$_ajaxKey] = $_ajaxValue; } } echo ' ajaxUrl="'.U($tableInfo['steps'][$_appendStep]['ajax']['url'], $ajaxParams).'" '; } editFormHelper::printElementAttributeInEditForm($EditInfo, $_selectName); echo '>'; if(! isset($tableInfo['steps'][$_appendStep]['ajax'])) { foreach($PrepareData[$_appendStep] as $_option) { $_value = array_shift($_option); $_key = array_shift($_option); if($_option) { list($_parentName, $_parentId) = each($_option); } else { $_parentId = $_parentName = 'n'; } $_selected = $_value==$EditData[$_field['Field']] ? ' selected="selected" ' : ''; echo '<option value="'.$_value.'" '.$_parentName.'="'.$_parentId.'">' .$_key. '</option>'; } } echo '</select>'; echo '</td>'; echo '</tr>'; } } ?>
					<tr>
						<td class="editLable"><?php echo isset($tableInfo['columnTitles'][$_field['Field']]) ? $tableInfo['columnTitles'][$_field['Field']] : $_field['Field'];?> :</td>
                        <td class="editContent"><?php
 if(in_array($_field['Field'], $tableInfo['crudKey'])) { echo $EditData[$_field['Field']]; echo '<input type="hidden" name="'.$_field['Field'].'" value="'.$EditData[$_field['Field']].'"/>'; } else { $fieldTypeInfo = explode('(', $_field['Type'], 2); static $_isReplaceMapParsed = false; if($_isReplaceMapParsed===false && $EditInfo['replaceMap']) { foreach($EditInfo['replaceMap'] as $_replaceKey=>$_replaceValue) { $_replaceValue = explode('.', $_replaceValue); $_replaceValue[1] = isset($_replaceValue[1]) ? $_replaceValue[1] : $_replaceKey; $EditInfo['replaceMap'][$_replaceKey] = $_replaceValue; } $_isReplaceMapParsed = true; } else { $_isReplaceMapParsed = true; } switch(strtolower($fieldTypeInfo[0])) { case 'int': if(isset($EditInfo['replaceMap'][$_field['Field']], $PrepareData[ $EditInfo['replaceMap'][$_field['Field']][0] ])) { $options = $PrepareData[$EditInfo['replaceMap'][$_field['Field']][0]]; echo '<select name="'.$_field['Field'].'" id="'.$_field['Field'].'" '.(in_array($_field['Field'], $CrudFieldChild)?('parent="'.array_search($_field['Field'], $CrudFieldChild).'"'):''); if(isset($tableInfo['steps'][ $EditInfo['replaceMap'][$_field['Field']][0] ]['ajax'])) { $ajaxParams = array(); foreach($tableInfo['steps'][$EditInfo['replaceMap'][$_field['Field']][0]]['ajax']['urlParam'] as $_ajaxKey=>$_ajaxValue) { if(is_numeric($_ajaxKey)) { $ajaxParams[$_ajaxValue] = I($_ajaxvalue, ''); } else { $ajaxParams[$_ajaxKey] = $_ajaxValue; } } echo ' ajaxUrl="'.U($tableInfo['steps'][$EditInfo['replaceMap'][$_field['Field']][0]]['ajax']['url'], $ajaxParams).'" '; } editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '>'; foreach($options as $_option) { $_value = array_shift($_option); $_key = array_shift($_option); if($_option) { list($_parentName, $_parentId) = each($_option); } else { $_parentId = $_parentName = 'n'; } $_selected = $_value==$EditData[$_field['Field']] ? ' selected="selected" ' : ''; echo '<option value="'.$_value.'" '.$_parentName.'="'.$_parentId.'" '.$_selected.'>' .$_key. '</option>'; } echo '</select>'; } else { echo '<input type="text" name="'.$_field['Field'].'" value="'.$EditData[$_field['Field']].'"'; editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '/>'; } break; case 'char': case 'varchar': echo '<input type="text" name="'.$_field['Field'].'" value="'.$EditData[$_field['Field']].'"'; editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '/>'; break; case 'date': echo '<input type="text" name="'.$_field['Field'].'" value="'.$EditData[$_field['Field']].'"'; editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '/>'; break; case 'datetime': echo '<input type="text" name="'.$_field['Field'].'" value="'.$EditData[$_field['Field']].'"'; editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '/>'; break; case 'float': case 'double': echo '<input type="text" name="'.$_field['Field'].'" value="'.$EditData[$_field['Field']].'"'; editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '/>'; break; case 'text': echo '<textarea class="loadHtmlEditor" type="text" name="'.$_field['Field'].'"'; editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '>'; echo $EditData[$_field['Field']] .'</textarea>'; break; case 'bool': case 'tinyint': echo 'Yes <input type="radio" name="'.$_field['Field'].'" value="1" '.($EditData[$_field['Field']]==1?'checked="checked"':''); editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '/>'; echo ' &nbsp; '; echo 'No <input type="radio" name="'.$_field['Field'].'" value="0" '.($EditData[$_field['Field']]==0?'checked="checked"':''); editFormHelper::printElementAttributeInEditForm($EditInfo, $_field['Field']); echo '/>'; break; default: break; } } ?></td>
					</tr>
					<?php
 if(isset($EditInfo['appendAfter'],$EditInfo['appendAfter'][$_field['Field']])) { foreach($EditInfo['appendAfter'][$_field['Field']] as $_selectName=>$_appendStep) { echo '<tr>'; echo '<td class="editLable">'; echo isset($tableInfo['columnTitles'][$_selectName]) ? $tableInfo['columnTitles'][$_selectName] : $_selectName; echo ' : </td>'; echo '<td class="editContent">'; echo '<select name="'.$_selectName.'" id="'.$_selectName.'" '.(in_array($_selectName, $CrudFieldChild)?('parent="'.array_search($_selectName, $CrudFieldChild).'"'):''); if(isset($tableInfo['steps'][$_appendStep]['ajax'])) { echo ' class="'.$tableInfo['steps'][$_appendStep]['ajax']['class'].'" '; $ajaxParams = array(); foreach($tableInfo['steps'][$_appendStep]['ajax']['urlParam'] as $_ajaxKey=>$_ajaxValue) { if(is_numeric($_ajaxKey)) { $ajaxParams[$_ajaxValue] = I($_ajaxvalue, ''); } else { $ajaxParams[$_ajaxKey] = $_ajaxValue; } } echo ' ajaxUrl="'.U($tableInfo['steps'][$_appendStep]['ajax']['url'], $ajaxParams).'" '; } echo '>'; if(! isset($tableInfo['steps'][$_appendStep]['ajax'])) { foreach($PrepareData[$_appendStep] as $_option) { $_value = array_shift($_option); $_key = array_shift($_option); if($_option) { list($_parentName, $_parentId) = each($_option); } else { $_parentId = $_parentName = 'n'; } $_selected = $_value==$EditData[$_field['Field']] ? ' selected="selected" ' : ''; echo '<option value="'.$_value.'" '.$_parentName.'="'.$_parentId.'">' .$_key. '</option>'; } } echo '</select>'; echo '</td>'; echo '</tr>'; } } } ?>
                    <tr>
                      <td colspan="2">
                      <?php
 foreach($FilterSteps as $_step) { if(is_string($_step)) $_step = array($_step); foreach($_step as $__step) { echo '<input type="hidden" name="'.$__step.'" value="'.I($__step, '').'"/>'; } } ?>
                      <input type="submit" name="submitEditItem" value="Save"/>
                      &nbsp;
                      <input type="reset" value="Reset"/>
                      </td>
                    </tr>
				</tbody>
			</table>
			</form>
			<div class="bottomArea">
				<div class="pager" style="float: right">
					<div class="paginator">
						<div class="pager"></div>
					</div>
				</div>
			</div>
		</div>
		<script language="javascript">
		tinymce.init({
		    selector: "textarea.loadHtmlEditor",
		    theme : "advanced",

	        plugins : "emotions,spellchecker,advhr,insertdatetime,preview",

	        // Theme options - button# indicated the row# only
	        theme_advanced_buttons1 : "bold,italic,underline,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect,|,sub,sup,|,charmap,emotions",
	        theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,code,preview,|,insertdate,inserttime,|,spellchecker,advhr,,removeformat",
	        theme_advanced_toolbar_location : "top",
	        theme_advanced_toolbar_align : "left"


		});
		function toggleChildOptions(parentId, parentValue, childKey)
		{
			if($('#'+childKey).hasClass('loadByAjax')) {
				$('#greyDiv').css('display','block');
				var url = $('#'+childKey).attr('ajaxUrl');
				eval("var data = {"+$('#'+parentId).attr('name') + ":\"" + parentValue + "\"};");
				$.ajax(
						{
							type : 'POST',
							url : url,
							dataType : 'text',
							async : false,
							data : data,
							success: function(response) {
								$('#greyDiv').css('display','none');
								try {
									$('#'+childKey+'>option').remove();
									var data = $.parseJSON(response);
									$.each(data, function(k, v) {
										var i,key, value, hasKey=false, hasValue=false;
										for(i in v) {
											if(!hasValue) {
												value=v[i];
												hasValue = true;
											} else if (!hasKey){
												key = v[i];
												hasKey = true;
											} else {
												break;
											}
										}
										var option = '<option value="'+value+'" '+$('#'+parentId).attr('name')+'="'+$('#'+parentId).val()+'">' +key+ '</option>';
										$('#'+childKey).append(option);
									});
									$('#'+childKey).trigger('change');
								} catch(e) {
								}

							}
						}
					);

			} else {
			    loadChildOptions(parentId, parentValue, childKey);
			}
		}

		function loadChildOptions(parentId, parentValue, childKey)
		{
			/*
			childKey = '#' + childKey;
			$(childKey + '>option').hide();
			$(childKey + '>option['+parentId+'="'+parentValue+'"]').show();

			if($(childKey + '>option:selected').attr(parentId) != parentValue) {
				$(childKey).val($(childKey + '>option['+parentId+'="'+parentValue+'"]:first').attr('value'));
			}

			$('#'+childKey).trigger('change');
			*/
			var options = document.getElementById(childKey).getElementsByTagName('option');
			var i, defaultSelected=0;
			var hasSelected = false;
			for(i=0; i<options.length; i++) {
				if(options[i].getAttribute(parentId)==parentValue) {
					options[i].style.display="block";
					if(!defaultSelected) {
						defaultSelected = i;
						options[i].selected = "selected";
					}
				} else {
					options[i].style.display="none";
				}
			}
			options = null;

			$('#'+childKey).trigger('change');
		}


		function toggleParentOptions(selectId, selectValue)
		{
			selectId = '#' + selectId;
			$(selectId).val(selectValue);
			var parentId = $(selectId).attr('parent');

			if(parentId && $('#' + parentId).length>0) {
				var parentValue = $(selectId+'>option:selected').attr(parentId);
				$(selectId + '>option').hide();
				$(selectId + '>option['+parentId+'="'+parentValue+'"]').show();
				toggleParentOptions(parentId, parentValue);
			}
		}
		<?php
 $finalKeys = array(); foreach($CrudFieldChild as $_key=>$_value) { if(!isset($CrudFieldChild[$_value])) { $finalKeys[] = $_value; } echo "\$(\"#$_key\").change(function() {"; echo "toggleChildOptions($(this).attr('id'), $(this).val(), \"$_value\");"; echo ''; echo "});\r\n"; } ?>

        <?php
 foreach($finalKeys as $_key) { echo "toggleParentOptions($('#$_key').attr('id'), $('#$_key').val());"; echo "\r\n"; } ?>
		//$().
		</script>