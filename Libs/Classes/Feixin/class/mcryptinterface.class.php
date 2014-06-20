<?php
define('LOCAL_UNIX_START_TIME', '621355968000000000');
define('UNIX_TIME_SUFFIX', '0000000');

class McryptInterface
{
    const    MM_EXPIRE        = 50000;    //缓存寿命
    const    TIME_ZONE_PATCH    = 28800;    //时区修正 +8时区
    private $iv = array (85, 60, 12, 116, 99, 189, 173, 19, 138, 183, 232, 248, 82, 232, 200, 242 );
    private function Int64ToTime($int)
    {
        $gmp_number = gmp_init($int, 16);
        $gmp_number = gmp_sub($gmp_number, LOCAL_UNIX_START_TIME);
        $str = gmp_strval($gmp_number);
        return substr($str, 0, $n - strlen(UNIX_TIME_SUFFIX));
    }

    private function Int64ToIp($int)
    {
        $s = '';
        for ($i = 8, $n = strlen($int); $i < $n; $i += 2) {
            $c = substr($int, $i, 2);
            $s = $s.$c;
        }
        return long2ip(gmp_strval(gmp_init($s, 16)));
    }
    private function UnpackBin($str, &$start, $len = 4)
    {
        $i = $start;
        $start += $len;

        if ($len == 1) {
            return ord ( $str {$i} );
        }

        $hex = '';
        for(; $i < $start; $i ++) {
            $c = dechex ( ord ( $str {$i} ) );
            if (strlen ( $c ) < 2) {
                $c = "0$c";
            }
            $hex = $c . $hex;
        }
        return $hex;
    }

    private function BytesToStr($bytes)
    {
        $args = $bytes;
        array_unshift ( $args, 'C*' );
        return call_user_func_array ( 'pack', $args );
    }

    private function ClearPadding($str)
    {
        $n = strlen ( $str );
        $flag = ord ( $str {$n - 1} );
        if ($flag == 0) {
            return $str;
        }

        return substr ( $str, 0, $n - $flag );
    }

    private function Decrypt($input, $algorithm, $mode, $key, $iv)
    {
        $td = mcrypt_module_open ( $algorithm, '', $mode, '' );
        mcrypt_generic_init ( $td, $key, $iv );
        $ret = mdecrypt_generic ( $td, $input );
        mcrypt_generic_deinit ( $td );
        mcrypt_module_close ( $td );
        return $ret;
    }


    private function ObjectToArray(&$object)
    {
        $object = ( array ) $object;
        foreach ( $object as $key => $value ) {
            if (is_object ( $value ) || is_array ( $value )) {
                self::ObjectToArray ( $value );
                $object [$key] = $value;
            }
        }
        return $object;
    }


    public function InfoDecrypt($str,$key="a1212121212211212bcdef")
    {
        $s = base64_decode ( $str );

        $p = 0;
        $flag = self::UnpackBin ( $s, $p, 1 );
        $expire = self::UnpackBin ( $s, $p );
        $s = self::Decrypt( substr ( $s, $p ), 'rijndael-128', 'cbc', base64_decode($key), self::BytesToStr ( $this->iv ) );
        $infoArray = array();
        $p = 0;
        $infoArray["Nonce"] = hexdec(self::UnpackBin($s,$p));
        $infoArray["CreateTime"] = self::Int64ToTime(self::UnpackBin($s, $p, 8));
        $infoArray["ExpireTime"] = self::Int64ToTime(self::UnpackBin($s, $p, 8));
        self::UnpackBin($s, $p,23);

        $strlen=self::UnpackBin($s,$p,4) ;

        $infoArray["EncodeStr"] = substr ( $s, $p,hexdec($strlen)-128);



        return $infoArray;
    }
}
?>
