<?php
/**
 * $Id: XmlTranslationLoader.class.php 83 2012-04-27 09:14:01Z zaixin.wang $
 * $Revision: 83 $
 * $Author: zaixin.wang $
 * $LastChangedDate: 2012-04-27 17:14:01 +0800 (Fri, 27 Apr 2012) $
 *
 * @package
 * @version
 * @author Kilin WANG <zaixin.wang@tellmemore.cn>
 */
require_once(dirname(__FILE__) . '/Translator.interface.php');
/**
 * Load XML string into translation array
 *
 * @example    $t = new XmlTranslationLoader('../../../localisation/en-US/Global.xml');
 *             echo $t->_('STR_SECONDARY_NAVIGATION_GUIDED_MODE'');
 *
 */
class IniTranslationLoader implements Translator
{
    /*
     * The translations list.  KeyWord=>TranslationString
     * @var array
     */
    protected $_translations = array();

    /**
     * Constructor
     * @param string $str XML file path or XML content
     */
    public function __construct($str)
    {
        $this->_translations = $this->_parseTranslation($str);
    }

    /**
     * Parse XML into translations list
     * @param string $str XML file path or XML content
     *
     * @return array
     */
    protected function _parseTranslation($str)
    {
        $strings = array();
        if(is_file($str)) {
            $strings = @parse_ini_file($str);
        } else {
            $strings = @parse_ini_string($str);
        }

        return $strings;
    }

    /**
     * Merge new translations into translations list
     * @param string $str XML file path or XML content
     */
    public function mergeTranslation($str)
    {
        $strings = $this->_parseTranslation($str);

        array_merge($this->_translations, $strings);
    }

    /**
     * Get translation by keyworkd
     * @param string $stringName The keyword used to get translation
     *
     * @return string
     */
    public function get($stringName)
    {
        $translation = isset($this->_translations[$stringName]) ? $this->_translations[$stringName] : $stringName;

        return $translation;
    }

    /**
     * Get translation by keyworkd.
     * @param string $stringName The keyword used to get translation
     *
     * @return string
     */
    public function _($stringName)
    {
        return $this->get($stringName);
    }
}

/* EOF */