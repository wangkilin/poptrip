<?php
define ( 'LOCAL_UNIX_START_TIME', '621355968000000000' );
define ( 'UNIX_TIME_SUFFIX', '0000000' );


class EncryptInterface
{

    private $iv = array (85, 60, 12, 116, 99, 189, 173, 19, 138, 183, 232, 248, 82, 232, 200, 242 );

    private function Encrypt($input, $algorithm, $mode, $key, $iv)
    {
        $td = mcrypt_module_open ( $algorithm, '', $mode, '' );
        mcrypt_generic_init ( $td, $key, $iv );
        $ret = mcrypt_generic ( $td, $input );
        mcrypt_generic_deinit ( $td );
        mcrypt_module_close ( $td );
        return $ret;
    }

    private function IpToInt64($ip)
    {
        $a = explode ( '.', $ip );
        $s = '';
        foreach ( $a as $i ) {
            $h = dechex ( $i );
            if (strlen ( $h ) < 2) {
                $h = "0$h";
            }
            $s = $s . $h;
        }
        return new Math_BigInteger("0x$s", 16);
    }

    private function TimeToInt64($time)
    {
        $bigObj = new Math_BigInteger($time . UNIX_TIME_SUFFIX);
        $tmp = new Math_BigInteger(LOCAL_UNIX_START_TIME);
        $ret = $bigObj->add($tmp);
        return $ret;
    }

    private function PackHex($str, $len = 4)
    {
        $str_len = $len * 2;
        $num = strlen ( $str );
        if ($num < $str_len) {
            $str = str_repeat ( '0', $str_len - $num ) . $str;
        }

        $list = array ();
        for($i = 0; $i < $str_len; $i += 2) {
            array_unshift ( $list, hexdec ( substr ( $str, $i, 2 ) ) );
        }
        return self::BytesToStr ( $list );
    }

    private function BytesToStr($bytes)
    {
        $args = $bytes;
        array_unshift ( $args, 'C*' );
        return call_user_func_array ( 'pack', $args );
    }

    private function InfoToStr($data)
    {
        //include_once(API_ROOT.'lib/bigInt.class.php');
        $str = '';
        $str = self::PackHex ( dechex ( $data [0] ) );
        $str .= self::PackHex ( $data[1]->toHex(), 8 );
        $str .= self::PackHex ( $data[2]->toHex(), 8 );
        $str .= pack ( 'C', $data [3] );
        $str .= self::PackHex( dechex ($data[4]));
        $str .= self::PackHex( dechex ($data[5]),2);
        $str .= self::PackHex( dechex ($data[6]));
        $data7 = new Math_BigInteger($data[7]);
        $str .= self::PackHex( $data[7]->toHex(), 8);
        $str .= self::PackHex( dechex ($data[8]));
        $str .= self::PackHex( dechex ($data[9]+128), 4);
        $str .= $data[10];
        $str = $str . sha1 ( $str, true );
        $p = 16 - strlen ( $str ) % 16;
        return $str . str_repeat(chr($p), $p);
    }

    public function InfoEncrypt($data,$key="a1212121212211212bcdef")
    {
          $flagStr = pack ( 'C', 0 );
        $expire_time =  $data[2] - $data[1];

        $data [1] = self::TimeToInt64 ( $data [1] );

        $data [2] = self::TimeToInt64 ( $data [2] );

        $data [7] = self::IpToInt64 ( $data [7] );

        $s = self::Encrypt ( self::InfoToStr ( $data ), 'rijndael-128', 'cbc', base64_decode($key), self::BytesToStr ( $this->iv ) );
        $len = strlen($s);
        $mod = ($len + 5) % 3;
        if (0 == $mod){
            $s = $s.' ';
        }
        if (2 == $mod){
            $s = $s. '  ';
        }
        $c_expire_time = ($expire_time & 0x7FFFFFF0) | ($mod & 0x0F);
        $expireStr = self::PackHex ( dechex ( $c_expire_time ) );
        return base64_encode($flagStr . $expireStr . $s);
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
}
