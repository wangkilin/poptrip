(function (Ctrip) {
    $.ready(function () {

        CtripSelfPassParams = function (_mode, _url, _params, _tag) {
            var _form = document.createElement('form');
            _form.method = _mode;
            _form.action = _url;
            _form.target = _tag;
            var _input = document.createElement('input');
            _input.setAttribute('type', 'hidden');
            _input.setAttribute('name', 'passvalue');
            _input.setAttribute('value', _params);
            _form.appendChild(_input);
            document.body.appendChild(_form);
            _form.submit();
            document.body.removeChild(_form);
        }

        CtripSelfSplit = function (_str, _spliter) {
            var _strs = _str.split(_spliter);
            return _strs[0];
        }

        CtripSelfStrInterchange1 = function (_strid, _cityvalue, _stbvalue, _hnamevalue, _lzodvalue) {
            var _postDataUrl = $(_strid); //'#postDataUrl'
            var _str = _postDataUrl.value();//"http://127.0.0.1:8888/site/hotelsearch/city_cityvalue/stb_stbvalue/hf_hfvalue/pf_1,10.html" 
            if (/(html|htm)$/i.test(_str)) {
                if (_cityvalue != '') {
                    var _cityvalueReg = /cityvalue/i;
                    if (_cityvalueReg.test(_str)) {

                        _str = _str.replace(/cityvalue/i, _cityvalue);
                    }
                }
                else {
                    var _cityvalueReg = new RegExp("/city_cityvalue", "i");
                    if (_cityvalueReg.test(_str)) {
                        _str = _str.replace(_cityvalueReg, '');
                    }
                }

                if (_stbvalue != '') {
                    var _stbvalueReg = /stbvalue/i;
                    if (_stbvalueReg.test(_str)) {
                        _str = _str.replace(_stbvalueReg, _stbvalue);
                    }
                }
                else {
                    var _stbvalueReg = new RegExp("/stb_stbvalue", "i");
                    if (_stbvalueReg.test(_str)) {
                        _str = _str.replace(_stbvalueReg, '');
                    }
                }

                if (_hnamevalue != '') {
                    var _hnamevalueReg = /hnamevalue/i;
                    if (_hnamevalueReg.test(_str)) {
                        _str = _str.replace(_hnamevalueReg, _hnamevalue);
                    }
                }
                else {
                    var _hnamevalueReg = new RegExp("/hname_hnamevalue", "i");
                    if (_hnamevalueReg.test(_str)) {
                        _str = _str.replace(_hnamevalueReg, '');
                    }
                }

                if (_lzodvalue != '') {
                    var _lzodvalueReg = /lzodvalue/i;
                    if (_lzodvalueReg.test(_str)) {
                        _str = _str.replace(_lzodvalueReg, _lzodvalue);
                    }
                }
                else {
                    var _lzodvalueReg = new RegExp("/lzod_lzodvalue", "i");
                    if (_lzodvalueReg.test(_str)) {
                        _str = _str.replace(_lzodvalueReg, '');
                    }
                }
				
				var _hfvalueReg = new RegExp("/hf_hfvalue", "i");
				if (_hfvalueReg.test(_str)) {
					_str = _str.replace(_hfvalueReg, '');
				}
					
                return _str;
            }
            else {
                return '';
            }
        }
		
		CtripSelfStrInterchange2 = function (_strid, _cityvalue, _stbvalue, _hnamevalue, _lzodvalue, _hfvalue) {
            var _postDataUrl = $(_strid); //'#postDataUrl'
            var _str =_postDataUrl.value();//"http://127.0.0.1:8888/site/hotelsearch/city_cityvalue/stb_stbvalue/hname_hnamevalue/lzod_lzodvalue/hf_hfvalue/pf_1,10.html" 
            if (/(html|htm)$/i.test(_str)) {
                if (_cityvalue != '') {
                    var _cityvalueReg = /cityvalue/i;
                    if (_cityvalueReg.test(_str)) {
                        _str = _str.replace(/cityvalue/i, _cityvalue);
                    }
                }
                else {
                    var _cityvalueReg = new RegExp("/city_cityvalue", "i");
                    if (_cityvalueReg.test(_str)) {
                        _str = _str.replace(_cityvalueReg, '');
                    }
                }

                if (_stbvalue != '') {
                    var _stbvalueReg = /stbvalue/i;
                    if (_stbvalueReg.test(_str)) {
                        _str = _str.replace(_stbvalueReg, _stbvalue);
                    }
                }
                else {
                    var _stbvalueReg = new RegExp("/stb_stbvalue", "i");
                    if (_stbvalueReg.test(_str)) {
                        _str = _str.replace(_stbvalueReg, '');
                    }
                }

                if (_hnamevalue != '') {
                    var _hnamevalueReg = /hnamevalue/i;
                    if (_hnamevalueReg.test(_str)) {
                        _str = _str.replace(_hnamevalueReg, _hnamevalue);
                    }
                }
                else {
                    var _hnamevalueReg = new RegExp("/hname_hnamevalue", "i");
                    if (_hnamevalueReg.test(_str)) {
                        _str = _str.replace(_hnamevalueReg, '');
                    }
                }

                if (_lzodvalue != '') {
                    var _lzodvalueReg = /lzodvalue/i;
                    if (_lzodvalueReg.test(_str)) {
                        _str = _str.replace(_lzodvalueReg, _lzodvalue);
                    }
                }
                else {
                    var _lzodvalueReg = new RegExp("/lzod_lzodvalue", "i");
                    if (_lzodvalueReg.test(_str)) {
                        _str = _str.replace(_lzodvalueReg, '');
                    }
                }

                if (_hfvalue != '') {
                    var _hfvalueReg = /hfvalue/i;
                    if (_hfvalueReg.test(_str)) {
                        _str = _str.replace(_hfvalueReg, _hfvalue);
                    }
                }
                else {
                    var _hfvalueReg = new RegExp("/hf_hfvalue", "i");
                    if (_hfvalueReg.test(_str)) {
                        _str = _str.replace(_hfvalueReg, '');
                    }
                }
                return _str;
            }
            else {
                return '';
            }
        }

		CtripSelfSplitPostData = function(_str){
			var _data = _str.split('&');
			var _newData = new Array;
			for(var _i=0;_i<_data.length;_i++){
				var _dataTemp = _data[_i].split("=");
				_newData[_dataTemp[0]] = _dataTemp[1];
			}
			return _newData;
		}
    });
})(window.Ctrip = window.Ctrip || {});

