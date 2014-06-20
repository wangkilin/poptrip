<?php
/**
 * $Id: Ldap.class.php 74 2012-04-24 16:09:49Z julien.serra $
 * $Revision: 74 $
 * $Author: julien.serra $
 * $LastChangedDate: 2012-04-24 18:09:49 +0200 (mar. 24 avr. 2012) $
 *
 * Ldap.php: talk to LDAP server
 *
 * @package SelfRegistration
 * @version 1.0
 * @author Kilin WANG <zaixin.wang@tellmemore.cn>
 */

/**
 * Class implement LDAP user verification, LDAP users listing
 */
class AuralogLDAP
{
    /*
     * @const int
     * The mark of LDAP server error
     */
    const LDAP_SERVER_ERROR = 4;
    /*
     * @const int
     * The mark of password wrong
     */
    const LDAP_PASSWORD_WRONG = 2;
    /*
     * @const int
     * The mark of user wrong
     */
    const LDAP_USER_WRONG = 3;
    /*
     * @const int
     * The mark of verification OK
     */
    const LDAP_PASSWORD_CORRECT = 0;

    /*
     * @var bool
     * If open debug mode
     */
    public $debug = false;

    /*
     * @var resource
     * LDAP connection
     */
    protected $_ds;

    /*
     * @var string
     * THe base_db used for search LDAP server
     */
    protected $_baseDn = '';

    /*
     * @var string
     * The filter used for search LDAP server
     */
    protected $_filter = '';

    /**
     * constructor
     * @param string $ldapServerIp The ldap server used to verify user login
     */
    public function __construct($ldapServerIp)
    {
        $this->_ds = ldap_connect($ldapServerIp);
        ldap_set_option($this->_ds,LDAP_OPT_PROTOCOL_VERSION,3);
        
    }
    
    public function bind(){
    	$bBindOK = false;
    	try {
    		$bBindOK = ldap_bind($this->_ds);
    		if(!$bBindOK){
    			throw new Exception(Constants::LOG_CRITICAL_NO_LDAP);
    		}
    	} catch (Exception $e){
    		LogFile::addCriticalLog($e->getMessage(), null);
    	}
    	return $bBindOK;
    }

    /**
     * Set ldap option
     * @param int $optionName The LDAP option
     * @param mixed $value The new value for the specified option.
     *
     * @return object Self instance
     */
    public function setLdapOption($optionName, $value)
    {
        if($this->_ds) {
            ldap_set_option($this->_ds, $optionName, $value);
        }

        return $this;
    }

    /**
     * set property value
     * @param string The property name
     * @param string The property value
     *
     * @return object
     */
    public function set($propertyName, $propertyValue)
    {
        $this->$propertyName = $propertyValue;

        return $this;
    }

    /**
     * Get LDAP users list by filter
     *
     * @return array
     */
    public function getLdapUsers()
    {
        $usersList = array();
        if($this->_ds) {
            $sr=ldap_search($this->_ds, $this->_baseDn, $this->_filter);
            $info = ldap_get_entries($this->_ds, $sr);
            if($this->debug) {
                echo '<!-- ' . print_r($info, true) . ' -->';
            }
            if(is_array($info)) {
                //list of authorized account in emanagement_test
                for($i=0; $i<$info[0]['member']['count']; $i++){
                    $temp = explode(',', $info[0]['member'][$i]);
                    $tempArr  = explode('=', $temp[0]);
                    $usersList[] = $tempArr[1];
                }
            }
        }

        return $usersList;
    }


    /**
     * compare password with LDAP user's encrpted password
     * @param string $text The plain text
     * @param string $hash The LDAP encrypted password
     *
     * @return bool
     */
    protected function _verifyPassword($text, $hash)
    {
        $hashMethod = substr($hash, 0, strpos($hash, '}')+1);
        switch(strtoupper($hashMethod)) {
            case '{MD5}': // md5
                $_refHash = "{MD5}".base64_encode( pack( 'H*' , md5($text) ) );
                break;

            case '{SSHA}': // ssha
                $ohash = base64_decode(substr($hash,6));
                $osalt = substr($ohash,20);
                $hash = substr($ohash,0,20);
                $_refHash = pack("H*",sha1($text.$osalt));
                break;

            default:
                $_refHash = $hash;
                break;
        }
        if($this->debug) {
            echo '<!-- verifyPassword:: ' . $_refHash . '&' . $hash . ' -->';
        }

        return $_refHash === $hash;
    }

    /**
     * Check if ldap the username with password
     * @param string $username The ldap username
     * @param string $password The password
     *
     * @return int The verification result
     */
    public function verifyUser($username, $password)
    {
        $result = self::LDAP_USER_WRONG;
        if($this->_ds) {
            $sr=ldap_search($this->_ds, $this->_baseDn, $this->_filter);
            $info = ldap_get_entries($this->_ds, $sr);
            if($this->debug) {
                echo '<!-- ' . print_r($sr, true) . ' -->';
            }
            unset($info[0]['member']['count']);
            foreach($info[0]["member"] as $key=>$dn){
                //extract the name in the member line
                preg_match('@^(cn=|uid=)+([^,]+)@i',$dn, $matches);
                if(strcasecmp($username,$matches[2]) == 0){
                    if(@ldap_bind($this->_ds, $dn, $password)) {
                        $result = self::LDAP_PASSWORD_CORRECT; // password correct
                    } else {
                    $filter="(objectclass=*)";
                    $justthese = array("userpassword", "sn");
                    // find the user, compare the password
                    $sr2=ldap_read($this->_ds, $dn, $filter, $justthese);
                    $entry = ldap_get_entries($this->_ds, $sr2);
                        if(isset($entry[0]['userpassword'])) {
                    if($this->_verifyPassword($password, $entry[0]['userpassword'][0])) {
                        $result = self::LDAP_PASSWORD_CORRECT; // password correct
                    } else {
                        $result = self::LDAP_PASSWORD_WRONG; // password incorrect
                    }
                        } else {
                            $result = self::LDAP_PASSWORD_WRONG; // password incorrect
                        }
                    }
                    break;
                }
            }
        }else{
            $result = self::LDAP_SERVER_ERROR; // LDAP server error
        }

        return $result;
    }

    /**
     * Generate ssha code
     * @param string $text The content to be encoded
     *
     * @return string
     */
    public function sshaEncode($text)
    {
        for ($i=1; $i<=10; $i++) {
            $salt .= substr('0123456789abcdef',rand(0,15),1);
        }
        $hash = "{SSHA}" . base64_encode(pack("H*",sha1($text.$salt)).$salt);

        return $hash;
    }

    /**
     * destruct instance
     */
    public function __destruct()
    {
        ldap_close($this->_ds);
    }
}
/* EOF */
