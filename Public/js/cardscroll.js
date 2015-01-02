(function ($) {
    $.extend({
        movepic:{
            bigpicwidth:706,
            smallpicwidth:675,
            bigpicleft:150,
            picnum:5,
            init:function(cardid,contactid,cardtype,params){
               this.cardid = cardid;
               this.jscontactid = contactid;
               this.cardtype = cardtype;
               if(typeof params != undefined){
                    this.bigpicwidth = params['bigpicwidth'];
                    this.bigpicleft = params['bigpicleft'];
                    this.smallpicwidth = params['smallpicwidth'];
                    this.picnum = params['picnum'];
               } 
               this.smallpicmoveleft();
            },

            smallpicmoveleft:function(){
                cardid = this.cardid;
                jscontactid = this.jscontactid;
                cardtype = this.cardtype;
                bigpicwidth = this.bigpicwidth;
                smallpicwidth = this.smallpicwidth;
                bigpicleft = this.bigpicleft;
                picnum = this.picnum;
                Pack = 0;
                $('.flash_smallimg_btn ul').on('click','li',function(){
                    var num = $(this).index();

                    Pack = num;

                    var oMleft = -(num*bigpicwidth-bigpicleft);

                    $('.flash_bigimg_zs ul').animate({left:oMleft},'slow');

                    $(this).addClass('active').siblings().removeClass('active');
                    if($("#js_addcard")){
                        $("#js_addcard").html($("#slide_dot1 a").eq(num).html());
                        $("#js_addcarduser").html($("#slide_dot2 a").eq(num).html());
                    }


                });
                var pages =Math.ceil($('.flash_smallimg_btn ul').children('li').length/picnum);

                var pg=0;

                $('.small_btn_left').on('click', function(){

                    var oleft = parseInt($('.flash_smallimg_btn ul').css('left'));

                    if(pg <= 0){

                        return;

                    }


                    if(!$('.flash_smallimg_btn ul').is(':animated')){
                        pg--;
                        $('.flash_bigimg_zs ul').animate({left: -(pg*picnum*bigpicwidth-bigpicleft)},'slow');

                        $('.flash_smallimg_btn ul').animate({left: oleft+smallpicwidth},'slow');

                        $('.flash_smallimg_btn ul').children('li').eq(pg*picnum).addClass('active').siblings('li').removeClass('active');

                        if($("#js_addcard")){
                            $("#js_addcard").html($("#slide_dot1 a").eq(pg*picnum).html());
                            $("#js_addcarduser").html($("#slide_dot2 a").eq(pg*picnum).html());
                        }
                    }



                });

                $('.small_btn_right').on('click',function(){

                    var oleft = parseInt($('.flash_smallimg_btn ul').css('left'));

                    if(pg>=(pages-1)){

                        return;

                    }


                    if(!$('.flash_smallimg_btn ul').is(':animated')){
                        pg++;
                        $('.flash_bigimg_zs ul').animate({left: -(pg*picnum*bigpicwidth-bigpicleft)},'slow');

                        $('.flash_smallimg_btn ul').animate({left: oleft-smallpicwidth},'slow');

                        $('.flash_smallimg_btn ul').children('li').eq(pg*picnum).addClass('active').siblings('li').removeClass('active');

                        if($("#js_addcard")){
                            $("#js_addcard").html($("#slide_dot1 a").eq(pg*picnum).html());
                            $("#js_addcarduser").html($("#slide_dot2 a").eq(pg*picnum).html());
                        }
                    }


                });

                $('.big_btn_left').on('click',function(){
                    var oMleft = -((Pack-1)*bigpicwidth-bigpicleft);
                    var len = $('.flash_smallimg_btn ul').children('li').length;
                    var thisleft = parseInt($('.flash_bigimg_zs ul').css('left'));
                    if(thisleft >=bigpicleft){
                        return;
                    }
                    if(!$('.flash_bigimg_zs ul').is(':animated')){
                        if(Pack > 0){

                            if(Pack%picnum == 0){

                                var n = parseInt((Pack)/picnum);
                                if(n != 0 ){
                                    $('.flash_smallimg_btn ul').animate({left: -(n-1)*smallpicwidth},'slow');
                                    pg--;
                                }

                            }
                            Pack--;

                        }


                        $('.flash_bigimg_zs ul').animate({left: thisleft+bigpicwidth});

                        $('.flash_smallimg_btn ul').children('li').eq(Pack).addClass('active').siblings().removeClass('active');
                        if($("#js_addcard")){
                            $("#js_addcard").html($("#slide_dot1 a").eq(Pack).html());
                            $("#js_addcarduser").html($("#slide_dot2 a").eq(Pack).html());
                        }
                    }

                });

                $('.big_btn_right').on('click',function(){
                    var len = $('.flash_bigimg_zs ul').children('li').length;
                    var thisleft = parseInt($('.flash_bigimg_zs ul').css('left'));
                    if(-thisleft >= (len-1)*bigpicwidth-bigpicleft){
                        return;
                    }
                    if(!$('.flash_bigimg_zs ul').is(':animated')){
                        if(Pack < len-1){

                            Pack++;

                            if(Pack%picnum == 0){

                                var n = parseInt(Pack/picnum);

                                $('.flash_smallimg_btn ul').animate({left: -n*smallpicwidth},'slow');

                                pg++;

                            }

                        }


                        $('.flash_bigimg_zs ul').animate({left: thisleft-bigpicwidth});

                        $('.flash_smallimg_btn ul').children('li').eq(Pack).addClass('active').siblings().removeClass('active');
                        if($("#js_addcard")){
                            $("#js_addcard").html($("#slide_dot1 a").eq(Pack).html());
                            $("#js_addcarduser").html($("#slide_dot2 a").eq(Pack).html());
                        }
                    }

                });
                $('#Business_Card_p').hover(function(){
                    if(cardtype[Pack]=='self'){
                        $('#slide_dot1').show();
                        $('#slide_dot2').children('a').attr('href',"../card_editor/updateCard/vcardid/" + cardid[Pack] + "/contactid/" + jscontactid[Pack]);
                        $('#slide_dot2').show();
                        $('#ullist_one').show();
                        $('#slide_dot1').attr("onclick", 'delecard("' + jscontactid[Pack] + '","' + cardid[Pack] + '")');

                    }else if(cardtype[Pack]=='self-eps'){
                        $('#slide_dot1').show();
                        $('#slide_dot2').hide();
                        $('#ullist_one').show();
                        $('#slide_dot1').attr("onclick", 'delecard("' + jscontactid[Pack] + '","' + cardid[Pack] + '")');
                    }

                },function(){
                    $('#ullist_one').hide();
                })
            }
        }
    })

})(jQuery);