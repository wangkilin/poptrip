
(function(Ctrip){
	$.ready(function(){
		var checkInDate = $('#checkInDate');
		var checkOutDate = $('#checkOutDate');
		var username = $('#username');
		var cellphone = $('#cellphone');
		var validateCell = function(str){
            if (/^13\d{9}$/.test(str)
                || (/^15[0-35-9]\d{8}$/.test(str))
                || (/^18[0-9]\d{8}$/.test(str))
                || (/^[965]\d{7}$/.test(str))) return true;
            else return false;
		}
		Date.prototype.yyyymmdd = function() {
			   var yyyy = this.getFullYear().toString();
			   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
			   var dd  = this.getDate().toString();
			   return yyyy +'-'+ ((mm.length != 1)?mm:"0"+mm) +'-'+ ((dd.length != 1)?dd:"0"+dd); // padding
	  	};
	  		//check the date format is valid
		Ctrip.isCorrectDate =  function(str){
			var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
			if (r == null) return false;
			var d = new Date(r[1], r[3] - 1, r[4]);
			return (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4]);
		}
		Ctrip.validate = function(){
			var msg = {pass:true,msg:''};
			var ckival = checkInDate.value();
			var ckoval = checkOutDate.value();
			if(username.value().trim() == ''){
				msg.pass = false;
				msg.msg = "请填写入住人";
				username[0].value = '';
				username[0].focus();
			}
			else if(cellphone.value() == ''){
				msg.pass = false;
				msg.msg = "请填写手机号码";
				cellphone[0].focus();
			}
			else if(!validateCell(cellphone.value())){
				msg.pass = false;
				msg.msg = "请填写正确的手机号码";
				cellphone[0].focus();
			}
			else if(checkInDate.value() == ''){
				msg.pass = false;
				msg.msg = "请填写开始时间";
				checkInDate[0].focus();
			}
			else if(checkOutDate.value() == ''){
				msg.pass = false;
				msg.msg = "请填写结束时间";
				checkOutDate[0].focus();
			}
			else if(ckival.split('-')[1] != ckoval.split('-')[1]){
				msg.pass = false;
				msg.msg = "只能查询相同月的订单";
				checkInDate[0].focus();
			}
			return msg;

		}
		function GetDateStr(dateObj, AddDayCount) {
	  		dateObj = dateObj.replace('-','/');
		    var dd = new Date(dateObj);
		    dd.setDate(dd.getDate()+AddDayCount);//鑾峰彇AddDayCount澶╁悗鐨勬棩鏈?
		    var y = dd.getFullYear();
		    var m = dd.getMonth()+1;//鑾峰彇褰撳墠鏈堜唤鐨勬棩鏈?
		    var d = dd.getDate();
		    
		    if(m.toString().length < 2){
		    	m = "0" + m;
		    }
		    if(d.toString().length < 2){
		    	d = "0" + d;
		    }
		    return y+"-"+m+"-"+d;
		}
		var today = new Date().yyyymmdd();
		//reg mod calendar
		//checkInDate.data('startdate','2012-01-01');
		$.mod.load('calendar', '3.1', function () {
			checkInDate.regMod('calendar', '3.1', {
				options: {
					minDate:'2012-01-01',
					maxDate:'2013-12-24',
				    autoShow: false,
				    showWeek: true
				},
				listeners: {
					onChange: function (input, value) {
						if(!Ctrip.isCorrectDate(value)){
							checkInDate.value(today);
						}
					}
				}
			});
			
			checkOutDate.regMod('calendar', '3.1', {
				options: {
					minDate:'2012-01-01',
					maxDate:'2013-12-24',
				    autoShow: false,
				    showWeek: true
				},
				listeners: {
					onChange: function (input, value) {
				 		if(!Ctrip.isCorrectDate(value)){
							checkOutDate.value(today);
						}
					}
				}
			});
		});
		$('#orderstatue').bind('change',function(){
			$('#orderform')[0].submit();
		})
		$('#orderform').bind('submit',function(e){
			var msg = Ctrip.validate();
			if(!msg.pass){
				alert(msg.msg);
				e.preventDefault();
			}
			
		})
	})
})(window.Ctrip = window.Ctrip || {})