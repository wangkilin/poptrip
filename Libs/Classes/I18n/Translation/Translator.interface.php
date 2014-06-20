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
interface Translator
{

    /**
     * Constructor
     * @param string $str XML file path or XML content
     */
    public function __construct($str);

    /**
     * Merge new translations into translations list
     * @param string $str XML file path or XML content
     */
    public function mergeTranslation($str);

    /**
     * Get translation by keyworkd
     * @param string $stringName The keyword used to get translation
     *
     * @return string
     */
    public function get($stringName);

    /**
     * Get translation by keyworkd.
     * @param string $stringName The keyword used to get translation
     *
     * @return string
     */
    public function _($stringName);
}

/* EOF */