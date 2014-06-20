<?php
/**
 * $Id: XmlTranslationLoader.class.php 100 2012-05-11 07:46:28Z zaixin.wang $
 * $Revision: 100 $
 * $Author: zaixin.wang $
 * $LastChangedDate: 2012-05-11 09:46:28 +0200 (ven. 11 mai 2012) $
 *
 * @package
 * @version
 * @author Kilin WANG <zaixin.wang@tellmemore.cn>
 */
/**
 * Load XML string into translation array
 *
 * @example    $t = new XmlTranslationLoader('../../../localisation/en-US/Global.xml');
 *             echo $t->_('STR_SECONDARY_NAVIGATION_GUIDED_MODE'');
 *
 */
class XmlTranslationLoader
{
    /*
     * The translations list.  KeyWord=>TranslationString
     * @var array
     */
    protected $_translations = array();

    /**
     * Constructor
     * @param string $xml XML file path or XML content
     */
    public function __construct($xml)
    {
        $this->_translations = $this->_parseXmlTranslation($xml);
    }

    /**
     * Parse XML into translations list
     * @param string $xml XML file path or XML content
     *
     * @return array
     */
    protected function _parseXmlTranslation($xml)
    {
        $strings = array();
        if(is_file($xml)) {
            $translations = @simplexml_load_file($xml);
        } else {
            $translations = @simplexml_load_string($xml);
        }

        if($translations) {
            foreach($translations as $_stringInfo) {
                $_stringInfo = (array)$_stringInfo->attributes();
                $strings[$_stringInfo['@attributes']['name']] = $_stringInfo['@attributes']['value'];
            }
        }

        return $strings;
    }

    /**
     * Merge new translations into translations list
     * @param string $xml XML file path or XML content
     */
    public function mergeTranslation($xml)
    {
        $strings = $this->_parseXmlTranslation($xml);

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