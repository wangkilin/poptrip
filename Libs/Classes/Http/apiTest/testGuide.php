<?php
/**
 * $Id$
 * $Revision$
 * $Author$
 * $LastChangedDate$
 *
 * @package
 * @version
 * @author Kilin WANG <zaixin.wang@tellmemore.cn>
 */
?>
<SCRIPT language="javascript">
function submit_form()
{
        document.form1.submit();
}

</SCRIPT>
<form name="form1" id="form1" method="post" action="">
 <br>
 <table>
 <?php
 foreach($testItems as $testAction => $testPage) {
 ?>
 <tr>
    <td>
        <input id="test_<?php echo $testAction;?>" name="action" type="radio" value="<?PHP echo $testAction; ?>"></input>
    </td>
    <td><label for="test_<?php echo $testAction;?>"><?php echo $testAction;?></label></td>
 </tr>
 <?php
 }
 ?>
 <tr>
    <td colspan=2>
      <input type="button" value="send" name="doTest" onclick="javascript:submit_form()">
    </td>
 </tr>
 </table>
</form>