

$().ready(function() {
   $('textarea.tinymce').tinymce({
      script_url : '../js/tiny_mce/tiny_mce.js',
     // theme : "simple",

      // bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull
      // bullist,numlist,outdent,indent,cut,copy,paste,undo,redo,link,unlink,image,cleanup
      // help,code,hr,removeformat,formatselect,fontselect,fontsizeselect,styleselect,sub,sup
      // forecolor,backcolor,forecolorpicker,backcolorpicker,charmap,visualaid,anchor,newdocument
      // blockquote,separator ( | is possible as separator, too)

      theme_advanced_buttons1 : "bold,italic,underline,strikethrough,sub,sup,separator,forecolor,backcolor,forecolorpicker,backcolorpicker",
      theme_advanced_buttons2 : "cut,copy,paste,undo,redo,separator,link,unlink,bullist,numlist,justifyleft,justifycenter,justifyright,justifyfull",
      mode : "textareas",
      onchange_callback : "myCustomOnChangeHandler"

   });
});