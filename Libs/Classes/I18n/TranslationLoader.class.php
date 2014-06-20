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
/**
 * Load XML string into translation array
 *
 * @example    $t = new XmlTranslationLoader('../../../localisation/en-US/Global.xml');
 *             echo $t->_('STR_SECONDARY_NAVIGATION_GUIDED_MODE'');
 *
 */
class TranslationLoader
{
    /*
     * The translations list.  KeyWord=>TranslationString
     * @var array
     */
    protected $_translations = array();

    protected $translator = null;

    /**
     * Constructor
     * @param string $str XML file path or XML content
     */
    public function __construct($str, $loaderType)
    {
        switch(strtolower($loaderType)) {
            case 'ini':
                require_once(dirname(__FILE__) . '/Translation/IniTranslationLoader.class.php');
                $this->translator = new IniTranslationLoader($str);
                break;

            case 'xml':
                require_once(dirname(__FILE__) . '/Translation/XmlTranslationLoader.class.php');
                $this->translator = new IniTranslationLoader($str);
                break;

            default:
                break;
        }
    }

    /**
     * Merge new translations into translations list
     * @param string $str XML file path or XML content
     */
    public function mergeTranslation($str, $loaderType)
    {
        $this->translator->mergeTranslation($str);
    }

    /**
     * Get translation by keyworkd
     * @param string $stringName The keyword used to get translation
     *
     * @return string
     */
    public function get($stringName)
    {
        return $this->translator->_($stringName);
    }

    /**
     * Get translation by keyworkd.
     * @param string $stringName The keyword used to get translation
     *
     * @return string
     */
    public function _($stringName)
    {
        return $this->translator->_($stringName);
    }
}

/* EOF */