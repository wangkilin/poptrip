<?php
/********************************************************/
/*****                 @!!@                          ****/
/********************************************************/
/**
 *@FileName    : ImageHandler.class.php
 *@Author      : Kilin WANG <wangkilin@126.com>
 *@Date        : 2014-5-24
 *@Homepage    : http://www.kinful.com
 *@Version     : 0.1
 */
class ImageHandler
{
    protected $newImage; // 目标文件
    protected $srcImageHandler; // 图片资源句柄
    protected $newImageHandler; // 新图句柄
    protected $srcImageExtension; // 文件类型
    protected $maskImageHandler; // 水印句柄
    protected $jpgQuality = 80; // 图片显示质量,默认为75
    protected $zoomScale = 0; // 图片缩放比例
    protected $srcImageWidth = 0; // 原图宽度
    protected $srcImageHeight = 0; // 原图高度
    protected $newImageWidth = 0; // 新图总宽度
    protected $newImageHeight = 0; // 新图总高度
    protected $canvasWidth; // 填充图形宽
    protected $canvasHeight; // 填充图形高
    protected $copySrcImageWidth; // 拷贝图形宽
    protected $copySrcImageHeight; // 拷贝图形高
    protected $copySrcImageStartX = 0;// 原图绘制起始横坐标
    protected $copySrcImageStartY = 0;// 原图绘制起始纵坐标
    protected $canvasX = 0; // 新图绘制起始横坐标
    protected $canvasY = 0; // 新图绘制起始纵坐标
    protected $maskChars; // 水印文字
    protected $maskImagePath; // 水印图片
    protected $maskX = 0; // 水印横坐标
    protected $maskY = 0; // 水印纵坐标
    protected $maskOffsetX = 5; // 水印横向偏移
    protected $maskOffsetY = 5; // 水印纵向偏移
    protected $maskWidth; // 水印宽
    protected $maskHeight; // 水印高
    protected $fontColor = "#000000"; // 水印文字颜色
    protected $font = 2; // 水印字体
    protected $fontSize = 12; // 字号
    protected $maskPosition = 0; // 水印位置
    protected $maskImagePct = 50; // 图片合并程度,值越大，合并程序越低
    protected $maskCharsAlpha = 50; // 文字合并程度,值越小，合并程序越低
    protected $imageBorderSize = 0; // 图片边框尺寸
    protected $imageBorderColor = '#000000'; // 图片边框颜色
    protected $_flip_x = 0; // 水平翻转次数
    protected $_flip_y = 0; // 垂直翻转次数

    protected $dirHandler = null; // the dir operatation handler. used to check new image store path


    // 支持的图片文件类型定义,绑定图片输出函数
    protected $supportImageTypes = array ("jpg" => array ("imageFuncName" => "imagejpeg" ),
                                   "gif" => array ("imageFuncName" => "imagegif" ),
                                   "png" => array ("imageFuncName" => "imagepng" ),
                                   "wbmp" => array ("imageFuncName" => "image2wbmp" ),
                                   "jpeg" => array ("imageFuncName" => "imagejpeg" ) );

    /**
     * 构造函数
     */
    public function ImageHandler($srcImagePath)
    {
        $imageInfo = $this->loadImageInfo($srcImagePath);
        $this->srcImageHandler = $imageInfo['handler'];
        $this->srcImageWidth = $imageInfo['width'];
        $this->srcImageHeight = $imageInfo['height'];
        $this->srcImageType = $imageInfo['type'];
        $this->srcImageExtension = $imageInfo['type'];

        $this->_loadDefault();
    }

    protected function _loadDefault()
    {
        $this->newImageHandler = null;
        $this->newImageHeight = $this->srcImageHeight;
        $this->newImageWidth = $this->srcImageWidth;
        $this->imageBorderSize = 0;
        $this->imageBorderColor = '#000000';
        $this->copySrcImageHeight = $this->srcImageHeight;
        $this->copySrcImageWidth = $this->srcImageWidth;
        $this->copySrcImageStartX = 0;
        $this->copySrcImageStartY = 0;
        $this->canvasHeight = $this->srcImageHeight;
        $this->canvasWidth = $this->srcImageWidth;
    }

    public function ___construct($srcImagePath)
    {
        $this->ImageHandler($srcImagePath);
    }

    /**
     * 设置图片生成路径
     *
     * @param    string    $src_Image   图片生成路径
     */
    protected function loadImageInfo($imagePath, $ImageType=null) {
        $imageInfo = @getimagesize($imagePath);
        if(!$imageInfo) {
            trigger_error('Image file does not exist: ' . $imagePath, E_USER_ERROR);
            return;
        }

        $imageWidth = $imageInfo[0];
        $imageHeight = $imageInfo[1];
        $imageType = $imageInfo[2];
        //1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，
        //6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，
        //9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，
        //16 = XBM

        $typeList = array (1 => "gif", 2 => "jpeg", 3 => "png", 4 => "swf",
                5 => "psd", 6 => "bmp", 15 => "wbmp" );
        switch($imageType) {
            case 1:
            case 2:
            case 3:
            case 15:
                $funcName = 'imagecreatefrom' . $typeList[$imageType];
                $ImageHandler = $funcName($imagePath);
                $imageType = $typeList[$imageInfo[2]];
                break;

            case 4:
            case 5:
            case 6:
                $ImageContent = file_get_contents($imagePath);
                $ImageHandler = @imageCreateFromString ( $src );
                $imageType = $typeList[$imageInfo[2]];
                break;

            default:
                return;
                break;
        }
        $imageWidth = imagesx($ImageHandler);
        $imageHeight = imagesy($ImageHandler);

        $return = array('type'=>$imageType, 'width'=>$imageWidth,
                        'height'=>$imageHeight, 'handler'=>$ImageHandler
        );

        return $return;
    }

    /**
     * 保存图片
     *
     * @param    string    $newImage   图片生成路径
     */
    public function setNewImagePath($imagePath)
    {
        $imagePath = realpath($imagePath);
        $imageDir = dirname($imagePath);
        if(! is_dir($imageDir)) {
            if(is_object($this->dirHandler) && method_exists($this->dirHandler, 'createDir') ) {
                $this->dirHandler->createDir($imageDir);
            } else {
                trigger('Folder does not exist: ' . $imageDir);
            }
        }
        $this->newImagePath = $imagePath;

        return $this;
    }

    /**
     * 设置图片的显示质量
     *
     * @param    string      $n    质量
     */
    public function setImageDisplayQuality($quality)
    {
        $this->jpgQuality = intval($quality);

        return $this;
    }

    /**
     * 设置文字水印
     *
     * @param    string     $word    水印文字
     * @param    integer    $font    水印字体
     * @param    string     $color   水印字体颜色
     */
    public function setMaskChars($chars)
    {
        $this->maskChars = strval($chars);

        return $this;
    }

    /**
     * 设置字体颜色
     *
     * @param    string     $color    字体RGB颜色 #ffffff
     */
    public function setMaskFontColor($color)
    {
        $this->fontColor = $color;

        return $this;
    }

    /**
     * 设置水印字体
     *
     * @param    string|integer    $font    字体
     */
    public function setFont($font=null)
    {
        if($font && (in_array($font, array(1,2,3,4,5)) || file_exists($font))) {
            $this->font = $font;
        } else {
            trigger_error('Font file does not exist: ' . $font, E_USER_WARNING);
        }

        return $this;
    }

    /**
     * 设置文字字体大小,仅对truetype字体有效
     */
    public function setMaskFontSize($size)
    {
        $this->fontSize = $size;

        return $this;
    }

    /**
     * 设置图片水印
     *
     * @param    string    $Image     水印图片源
     */
    public function setMaskImagePath($imagePath)
    {
        $this->maskImagePath = $imagePath;

        return $this;
    }

    /**
     * 设置水印横向偏移
     *
     * @param    integer     $x    横向偏移量
     */
    public function setMaskOffsetX($x)
    {
        $this->maskOffsetX = ( int ) $x;
    }

    /**
     * 设置水印纵向偏移
     *
     * @param    integer     $y    纵向偏移量
     */
    public function setMaskOffsetY($y)
    {
        $this->maskOffsetY = ( int ) $y;
    }

    /**
     * 指定水印位置
     *
     * @param    integer     $position    位置,1:左上,2:左下,3:右上,0/4:右下
     */
    public function setMaskPosition($position = 0)
    {
        $this->maskPosition = ( int ) $position;

        return $this;
    }

    /**
     * 设置图片合并程度
     *
     * @param    integer     $n    合并程度
     */
    public function setMaskImagePct($n)
    {
        $this->maskImagePct = ( int ) $n;

        return $this;
    }

    /**
     * 设置文字合并程度
     *
     * @param    integer     $n    合并程度
     */
    public function setMaskCharsAlpha($n)
    {
        $this->maskCharsAlpha = ( int ) $n;

        return $this;
    }

    /**
     * 设置缩略图边框
     *
     * @param    (类型)     (参数名)    (描述)
     */
    public function setImageBorder($size=1, $color="#000000")
    {
        $this->imageBorderSize = ( int ) $size;
        $this->imageBorderColor = $color;
    }

    /**
     * 水平翻转
     */
    function flipH()
    {
        $this->_flip_x ++;
    }

    /**
     * 垂直翻转
     */
    function flipV()
    {
        $this->_flip_y ++;
    }

    /**
     * 创建图片,主函数
     * @param    integer    $a     当缺少第二个参数时，此参数将用作百分比，
     *                             否则作为宽度值
     * @param    integer    $b     图片缩放后的高度
     */
    function createImage($width, $height = null)
    {
        $height = intval($height);
        $width = intval($width);
        if (0==$height) {
            $this->setZoomScale($width);
        } else {
            if (0 == $width) {
                trigger_error("目标宽度不能为0", E_USER_ERROR);
            }
            $this->setNewImageSize ( $width, $height );
        }

        if ($this->_flip_x % 2 != 0) {
            $this->_flipH ( $this->srcImageHandler );
        }

        if ($this->_flip_y % 2 != 0) {
            $this->_flipV ( $this->srcImageHandler );
        }
        /*
         var_dump('mm',$this->newImageWidth, $this->newImageHeight,$this->canvasX, $this->canvasY,
                 $this->copySrcImageStartX, $this->copySrcImageStartY,
                 $this->canvasWidth, $this->canvasHeight,
                 $this->copySrcImageWidth, $this->copySrcImageHeight);exit;//*/
        $this->_computeCanvas();
        /*
         var_dump($this->canvasX, $this->canvasY,
                 $this->copySrcImageStartX, $this->copySrcImageStartY,
                 $this->canvasWidth, $this->canvasHeight,
                 $this->copySrcImageWidth, $this->copySrcImageHeight);exit;//*/
        $this->_createMask ();
        $this->_output ();

        // 释放
        if (imagedestroy ( $this->srcImageHandler ) && imagedestroy ( $this->newImageHandler )) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成水印,调用了生成水印文字和水印图片两个方法
     */
    function _createMask() {
        $this->newImageHandler = imagecreatetruecolor ( $this->newImageWidth, $this->newImageHeight );
        $white = ImageColorAllocate ( $this->newImageHandler, 255, 255, 255 );
        imagefilledrectangle ( $this->newImageHandler, 0, 0, $this->newImageWidth, $this->newImageHeight, $white ); // 填充背景色
        if ($this->maskChars) {
            // 获取字体信息
            $this->_loadFontInfo ();

            if ($this->_isFull ()) {
                die ( "水印文字过大" );
            } else {
                //$white = ImageColorAllocate ( $this->newImageHandler, 255, 255, 255 );
                //imagefilledrectangle ( $this->newImageHandler, 0, 0, $this->newImageWidth, $this->newImageHeight, $white ); // 填充背景色
                $this->_drawBorder ();
                imagecopyresampled ( $this->newImageHandler, $this->srcImageHandler,
                                     $this->canvasX, $this->canvasY,
                                     $this->copySrcImageStartX, $this->copySrcImageStartY,
                                     $this->canvasWidth, $this->canvasHeight,
                                     $this->copySrcImageWidth, $this->copySrcImageHeight );
                $this->_createMaskWord ( $this->newImageHandler );
            }
        }

        if ($this->maskImagePath) {
            $this->_loadMaskImage (); //加载时，取得宽高


            if ($this->_isFull ()) {
                // 将水印生成在原图上再拷
                $this->_createMaskImage ( $this->srcImageHandler );
                //$white = ImageColorAllocate ( $this->newImageHandler, 255, 255, 255 );
                //imagefilledrectangle ( $this->newImageHandler, 0, 0, $this->newImageWidth, $this->newImageHeight, $white ); // 填充背景色
                $this->_drawBorder ();
                //imagecopyresampled($dst_image, $src_image,
                //                   $dst_x, $dst_y,
                //                   $copySrcImageStartX, $copySrcImageStartY,
                //                   $dst_w, $dst_h,
                //                   $src_w, $src_h)

                imagecopyresampled ( $this->newImageHandler, $this->srcImageHandler,
                $this->canvasX, $this->canvasY,
                $this->copySrcImageStartX, $this->copySrcImageStartY,
                $this->canvasWidth, $this->canvasY,
                $this->copySrcImageWidth, $this->copySrcImageHeight );
            } else {
                // 创建新图并拷贝
                $this->_drawBorder ();
                imagecopyresampled ( $this->newImageHandler, $this->srcImageHandler,
                                     $this->canvasX, $this->canvasY,
                                     $this->copySrcImageStartX, $this->copySrcImageStartY,
                                     $this->canvasWidth, $this->canvasHeight,
                                     $this->copySrcImageWidth, $this->copySrcImageHeight );
                $this->_createMaskImage ( $this->newImageHandler );
            }
        }

        if (empty ( $this->maskChars ) && empty ( $this->maskImagePath )) {
            //$this->newImageHandler = imagecreatetruecolor ( 1, 1 );
            //$white = ImageColorAllocate ( $this->newImageHandler, 255, 255, 255 );
            //imagefilledrectangle ( $this->newImageHandler, 0, 0, $this->newImageWidth, $this->newImageHeight, $white ); // 填充背景色
            $this->_drawBorder ();

            /*
            var_dump($this->newImageWidth, $this->newImageHeight,
            $this->canvasX, $this->canvasY,
            $this->copySrcImageStartX, $this->copySrcImageStartY,
            $this->canvasWidth, $this->canvasHeight,
            $this->copySrcImageWidth, $this->copySrcImageHeight);exit;//*/
            imagecopyresampled ( $this->newImageHandler, $this->srcImageHandler,
            $this->canvasX, $this->canvasY,
            $this->copySrcImageStartX, $this->copySrcImageStartY,
            $this->canvasWidth, $this->canvasHeight,
            $this->copySrcImageWidth, $this->copySrcImageHeight );
        }
    }

    /**
     * 画边框
     */
    function _drawBorder() {
        if (! empty($this->imageBorderSize)) {
            $c = $this->_parseColor($this->imageBorderColor );
            $color = ImageColorAllocate ( $this->newImageHandler, $c [0], $c [1], $c [2] );
            /*imagefilledrectangle ( $this->newImageHandler, 0, 0, $this->newImageWidth, $this->newImageHeight, $color ); // 填充背景色
            */
            $coordinates = array(0,0,
                                $this->newImageWidth, 0,
                                $this->newImageWidth, $this->imageBorderSize,
                                0, $this->imageBorderSize,
                    );
            imagefilledpolygon($this->newImageHandler, $coordinates, 4, $color);
            $coordinates = array(0,0,
                                 $this->imageBorderSize, 0,
                                 $this->imageBorderSize, $this->newImageHeight,
                                 0, $this->newImageHeight,);
            imagefilledpolygon($this->newImageHandler, $coordinates, 4, $color);
            $coordinates = array(0,$this->newImageHeight-$this->imageBorderSize,
                                 0, $this->newImageHeight,
                                 $this->newImageWidth, $this->newImageHeight,
                                 $this->newImageWidth, $this->newImageHeight-$this->imageBorderSize,
                                 $this->newImageWidth, $this->newImageHeight,);
            imagefilledpolygon($this->newImageHandler, $coordinates, 4, $color);

            $coordinates = array($this->newImageWidth-$this->imageBorderSize,0,
                                 $this->newImageWidth, 0,
                                 $this->newImageWidth, $this->newImageHeight,
                                 $this->newImageWidth-$this->imageBorderSize, $this->newImageHeight,);
            imagefilledpolygon($this->newImageHandler, $coordinates, 4, $color);

        }
    }

    /**
     * 生成水印文字
     */
    function _createMaskWord($src) {
        $this->_countMaskPos ();
        $this->_checkMaskValid ();

        $c = $this->_parseColor ( $this->fontColor );
        $color = imagecolorallocatealpha ( $src, $c [0], $c [1], $c [2], $this->maskCharsAlpha );

        if (is_numeric ( $this->font )) {
            imagestring ( $src, $this->font, $this->maskX, $this->maskY, $this->maskChars, $color );
        } else {
            imagettftext ( $src, $this->fontSize, 0, $this->maskX, $this->maskY, $color, $this->font, $this->maskChars );
        }
    }

    /**
     * 生成水印图
     */
    function _createMaskImage($src) {
        $this->_countMaskPos ();
        $this->_checkMaskValid ();
        imagecopymerge ( $src, $this->maskImageHandler,
        $this->maskX, $this->maskY,
        0, 0,
        $this->maskWidth, $this->maskHeight, $this->maskImagePct );

        imagedestroy ( $this->maskImageHandler );
    }

    /**
     * 加载水印图
     */
    protected function _loadMaskImage()
    {
        $imageInfo = $this->loadImageInfo($this->maskImagePath);
        $mask_type = $imageInfo['type'];
        $this->_isTheSupportedImageType($imageInfo['type']);

        $this->maskImageHandler = $imageInfo['handler'];
        $this->maskWidth = $imageInfo['width'];
        $this->maskHeight = $imageInfo['height'];
    }

    /**
     * 图片输出
     */
    function _output() {
        $imageExtension = $this->srcImageExtension;
        $func_name = $this->supportImageTypes[$imageExtension]['imageFuncName'];
        if (function_exists ( $func_name )) {
            // 判断浏览器,若是IE就不发送头
            if (isset ( $_SERVER ['HTTP_USER_AGENT'] )) {
                $ua = strtoupper ( $_SERVER ['HTTP_USER_AGENT'] );
                if (! preg_match ( '/^.*MSIE.*\)$/i', $ua )) {
                    header ( "Content-type:$imageExtension" );
                }
            }
            $func_name ($this->newImageHandler, $this->newImage);
        } else {
            return false;
        }
    }

    /**
     * 分析颜色
     *
     * @param    string     $color    十六进制颜色
     */
    function _parseColor($color) {
        $arr = array ();
        for($ii = 1; $ii < strlen ( $color ); $ii ++) {
            $arr [] = hexdec ( substr ( $color, $ii, 2 ) );
            $ii ++;
        }

        return $arr;
    }

    /**
     * 计算出位置坐标
     */
    function _countMaskPos() {
            switch ($this->maskPosition) {
                case 1 :
                    // 左上
                    $this->maskX = $this->maskOffsetX + $this->imageBorderSize;
                    $this->maskY = $this->maskOffsetY + $this->imageBorderSize;
                    break;

                case 2 :
                    // 左下
                    $this->maskX = $this->maskOffsetX + $this->imageBorderSize;
                    $this->maskY = $this->newImageHeight - $this->maskHeight - $this->maskOffsetY - $this->imageBorderSize;
                    break;

                case 3 :
                    // 右上
                    $this->maskX = $this->newImageWidth - $this->maskWidth - $this->maskOffsetX - $this->imageBorderSize;
                    $this->maskY = $this->maskOffsetY + $this->imageBorderSize;
                    break;

                case 4 :
                    // 右下
                    $this->maskX = $this->newImageWidth - $this->maskWidth - $this->maskOffsetX - $this->imageBorderSize;
                    $this->maskY = $this->newImageHeight - $this->maskHeight - $this->maskOffsetY - $this->imageBorderSize;
                    break;

                default :
                    // 默认将水印放到右下,偏移指定像素
                    $this->maskX = $this->newImageWidth - $this->maskWidth - $this->maskOffsetX - $this->imageBorderSize;
                    $this->maskY = $this->newImageHeight - $this->maskHeight - $this->maskOffsetY - $this->imageBorderSize;
                    break;
            }
    }

    /**
     * 设置字体信息
     */
    function _loadFontInfo()
    {
        if (is_numeric ( $this->font )) {
            // 计算水印字体所占宽高
            $this->maskWidth = imagefontwidth ($this->font) * strlen ($this->maskChars);
            $this->maskHeight = imagefontheight ($this->font);
        } else {
            $fontSizes = imagettfbbox ( $this->fontSize, 0, $this->font, $this->maskChars );
            $this->maskWidth = abs ( $fontSizes[0] - $fontSizes[2] );
            $this->maskHeight = abs ( $fontSizes[7] - $fontSizes[1] );
        }
    }

    public function setZoomScale($scale)
    {
        $this->zoomScale = floatval($scale);
        $width = intval($this->srcImageWidth * $this->zoomScale);
        $this->setNewImageSize($width);
        $this->setCopySrcImagePartInfo();

        return $this;
    }

    public function setCopySrcImagePartInfo($startX=0, $startY=0, $width=0, $height=0)
    {
        $this->copySrcImageStartX = intval($startX);
        $this->copySrcImageStartY = intval($startY);
        $width = intval($width);
        $height = intval($height);

        if($this->copySrcImageStartX > $this->srcImageWidth) {
            $this->copySrcImageStartX = 0;
        }
        if($this->copySrcImageStartY > $this->srcImageHeight) {
            $this->copySrcImageStartY = 0;
        }

        if($width<=0 || $width > $this->srcImageWidth || $width > ($this->srcImageWidth-$startX) ) {
            $width = $this->srcImageWidth-$startX;
        }
        if($height<=0 || $height > $this->srcImageHeight || $height > ($this->srcImageHeight-$startY) ) {
            $height = $this->srcImageHeight-$startY;
        }
        $this->copySrcImageHeight = $height;
        $this->copySrcImageWidth = $width;

        return $this;
    }

    /**
     * 设置新图尺寸
     *
     * @param    integer     $newImageWidth   目标宽度
     * @param    integer     $newImageHeight   目标高度
     */
    public function setNewImageSize($newImageWidth, $newImageHeight=null)
    {
        $newImageHeight = intval($newImageHeight);;
        $newImageWidth = intval($newImageWidth);
        if($newImageWidth==0 && $newImageHeight==0) {
            $newImageHeight = $this->srcImageHeight;
            $newImageWidth = $this->srcImageWidth;
        } else if($newImageHeight==0) {
            $newImageHeight = intval($newImageWidth / $this->srcImageWidth * $this->srcImageHeight);
        } else if($newImageWidth==0) {
            $newImageWidth = intval($newImageHeight / $this->srcImageHeight * $this->srcImageWidth);
        }

        $this->newImageHeight = $newImageHeight;
        $this->newImageWidth = $newImageWidth;

        return $this;

    }

    protected function _computeCanvas()
    {
        $canvasWidth = $this->newImageWidth - $this->imageBorderSize * 2;
        $canvasHeight = $this->newImageHeight - $this->imageBorderSize * 2;
        if ($canvasWidth < 0 || $canvasHeight < 0) {
            trigger_error('图片边框过大，已超过了图片的宽度和高度');
        }
        $this->canvasWidth = $canvasWidth;
        $this->canvasHeight = $canvasHeight;

        // 目标文件起始坐标
        $this->canvasX = $this->imageBorderSize;
        $this->canvasY = $this->imageBorderSize;
    }

    /**
     * 检查水印图是否大于生成后的图片宽高
     */
    protected function _isFull()
    {
        return ($this->maskWidth + $this->maskOffsetX > $this->canvasWidth || $this->maskHeight + $this->maskOffsetY > $this->canvasHeight) ? true : false;
    }

    /**
     * 检查水印图是否超过原图
     */
    protected function _checkMaskValid()
    {
        if ($this->maskWidth + $this->maskOffsetX > $this->srcImageWidth || $this->maskHeight + $this->maskOffsetY > $this->srcImageHeight) {
            die ( "水印图片尺寸大于原图，请缩小水印图" );
        }
    }

    /**
     * Check if support the image type
     * @param    string     $imageExtension    文件类型
     * @return bool
     */
    protected function _isTheSupportedImageType($imageExtension)
    {
        return isset($this->supportImageTypes[$imageExtension]);
    }

    /**
     * 按指定路径生成目录
     *
     * @param    string     $path    路径
     */
    function _mkdirs($path) {
        $adir = explode ( '/', $path );
        $dirlist = '';
        $rootdir = array_shift ( $adir );
        if (($rootdir != '.' || $rootdir != '..') && ! file_exists ( $rootdir )) {
            @mkdir ( $rootdir );
        }
        foreach ( $adir as $key => $val ) {
            if ($val != '.' && $val != '..') {
                $dirlist .= "/" . $val;
                $dirpath = $rootdir . $dirlist;
                if (! file_exists ( $dirpath )) {
                    @mkdir ( $dirpath );
                    @chmod ( $dirpath, 0777 );
                }
            }
        }
    }

    /**
     * 垂直翻转图片
     *
     * @param    string     $src    图片源
     */
    function _flipV($src)
    {
        $copySrcImageStartX = imagesx($src);
        $copySrcImageStartY = imagesy($src);

        $new_im = imagecreatetruecolor ( $copySrcImageStartX, $copySrcImageStartY );
        for($y = 0; $y < $copySrcImageStartY; $y ++) {
            imagecopy ( $new_im, $src, 0, $copySrcImageStartY - $y - 1, 0, $y, $copySrcImageStartX, 1 );
        }
        $this->srcImageHandler = $new_im;
    }

    /**
     * 水平翻转图片
     *
     * @param    string     $src    图片源
     */
    function _flipH($src) {
        $copySrcImageStartX = imagesx($src);
        $copySrcImageStartY = imagesy( $src );

        $new_im = imagecreatetruecolor ( $copySrcImageStartX, $copySrcImageStartY );
        for($x = 0; $x < $copySrcImageStartX; $x ++) {
            imagecopy ( $new_im, $src, $copySrcImageStartX - $x - 1, 0, $x, 0, 1, $copySrcImageStartY );
        }
        $this->srcImageHandler = $new_im;
    }

    public function addMaskImage($maskImagePath, $maskPosition)
    {

    }

    public function addMaskChars($markChars, $maskPosition)
    {

    }

}

$imageHandler = new ImageHandler('http://s1.bdstatic.com/r/www/cache/static/global/img/icons_37d13939.png');
$imageHandler->setMaskChars('HelloHelloHello')->setMaskFontColor('#ff0000');
$imageHandler->setMaskImagePath('D:/rar.png');
//$imageHandler->setImageBorder(10);
$imageHandler->createImage(800,500);
