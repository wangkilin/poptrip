<!-- Default filter template -->
<fieldset>
 <legend>请选择</legend>
<?php
// $StepInfos = array( array('step_1' => array('aaa'=>'1', 'bbbb'=>'2'),
//                           'step_2' => array('a2'=>'1', 'b2'=>2)
//                          ),
//                     'step2' => array('a2'=>'1', 'b2'=>2)
//              );
$stepIndex = 1;
$stepHiddenParam = array();
foreach($FilterSteps as $_stepKey=>$_stepInfo) {
    echo '<form method="get" class="filterForm">';
    echo '<div class="fitlerStep filterStep_'.$_stepKey.'">';

    $stepHiddenParam[$stepIndex] = '';
    $onChange = '';
    if(is_array($_stepInfo)) {
        foreach($_stepInfo as $__stepKey=>$__stepInfo) {
            if(!isset($FilterData[$__stepInfo])) {
                break;
            }
            $defaultValue = I($__stepInfo, null);
            $defaultValue = $defaultValue===null?session($TableManageIndex.'/'.$__stepInfo) : $defaultValue;
            $onChange = isset($FilterStepsSubmit[$__stepInfo]) &&!empty($FilterStepsSubmit[$__stepInfo]['autoSubmit']) ? ' onchange="autoSubmit(this);" ' : '';
            $stepHiddenParam[$stepIndex] .= '<input type="hidden" name="'.$__stepInfo.'" value="'.$defaultValue.'"/>';
            echo '<div class="subStep filterStep_'.$__stepInfo.'">';
            if(isset($StepsInfo[$__stepInfo]['label'])) {
                echo '<label for="'.$__stepInfo.'" id="'.$__stepInfo.'_'.$stepIndex.'">' . $StepsInfo[$__stepInfo]['label'] . '</label>';
            }
            echo '<select name="'.$__stepInfo.'" id="'.$__stepInfo.'_'.$stepIndex.'" '.$onChange.'>';
            echo isset($FilterStepsSubmit[$__stepInfo]) &&!empty($FilterStepsSubmit[$__stepInfo]['hasEmptyOption']) ? ' <option value="">&nbsp;</option> ' : '';
            foreach($FilterData[$__stepInfo] as $_k=>$_v) {
                if(is_array($_v)) {
                    $_k = array_shift($_v);
                    $_v = array_shift($_v);
                }
                $selected = $_k==$defaultValue ? ' selected="selected" ' : '';
                echo '<option value="'.$_k.'" '.$selected.'>' . $_v . '</option>';
            }
            echo '</select>';
            echo '</div>';
        }
    } else {
        if(!isset($FilterData[$_stepInfo])) {
            break;
        }
        $onChange = isset($FilterStepsSubmit[$_stepInfo]) && !empty($FilterStepsSubmit[$_stepInfo]['autoSubmit']) ? ' onchange="autoSubmit(this);" ' : '';
        $defaultValue = I($_stepInfo, null);
        $defaultValue = $defaultValue===null?session($TableManageIndex.'/'.$_stepInfo) : $defaultValue;
        $stepHiddenParam[$stepIndex] .= '<input type="hidden" name="'.$_stepInfo.'" value="'.$defaultValue.'"/>';
        if(isset($StepsInfo[$_stepInfo]['label'])) {
            echo '<label for="'.$_stepInfo.'" id="'.$_stepInfo.'_'.$stepIndex.'">' . $StepsInfo[$_stepInfo]['label'] . ': </label>';
        }
        echo '<select name="'.$_stepInfo.'" id="'.$_stepInfo.'" id="'.$_stepInfo.'_'.$stepIndex.'" '.$onChange.'>';
        echo isset($FilterStepsSubmit[$_stepInfo]) &&!empty($FilterStepsSubmit[$_stepInfo]['hasEmptyOption']) ? ' <option value="">&nbsp;</option> ' : '';
        foreach($FilterData[$_stepInfo] as $_k=>$_v) {
            if(is_array($_v)) {
                $_k = array_shift($_v);
                $_v = array_shift($_v);
            }
            $selected = $_k==$defaultValue ? ' selected="selected" ' : '';
            echo '<option value="'.$_k.'" '.$selected.'>' . $_v . '</option>';
        }
        echo '</select>';
    }

    $i = 1;
    while(isset($stepHiddenParam[$stepIndex-$i])) {
        echo $stepHiddenParam[$stepIndex-$i];
        $i++;
    }

    echo '<input type="hidden" name="stepIndex" value="'.$stepIndex.'"/>';
    if(! $onChange) {
        echo '<input type="submit" class="filterFormSubmit" value="Submit"/>';
    }
    echo '</form>';
    echo '</div>';
    $stepIndex++;
}
?>
</fieldset>
