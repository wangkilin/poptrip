<?php
class operXml
{
    var $parser;

    public function __construct() 
    {
        $this->parser = xml_parser_create();
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);

        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");
    }

    public function parse($xmlStr="",$endtag=true) 
    {
     $this->endtag = $endtag;
     $this->xmlStr = $xmlStr;
     $this->tree = new stdClass;
     $this->tree->tag = "root";
     $this->tree->props = new stdClass;
     $this->tree->children = array();
     $this->tree->p = NULL;
     $this->tree->level = -1;
     $this->deep = 0;
     $this->pList = array($this->tree);
     xml_parse($this->parser, $this->xmlStr);
    if(count($this->tree->children)>0)
        $this->root = $this->tree->children[0];
    else
       $this->root = NULL;
    return $this;
   }

   public function tag_open($parser, $tag, $attributes) 
   {
  $o = new stdClass;
  $o->p = $this->pList[$this->deep];
  $o->index = count($o->p->children);
  $o->level = $o->p->level1;
  $o->tag = $tag;
  $o->props = new stdClass;
  while(list($key,$value)=each($attributes))
  $o->props->{$key} = $value;
  $o->value = "";
  $o->children = array();
  array_push($o->p->children,$o);
  $this->deep ;
  $this->pList[$this->deep] = $o;
    }

    public function cdata($parser, $cdata) 
    {
        $this->pList[$this->deep]->value = $cdata;
    }

    public function tag_close($parser, $tag)
    {
  $this->deep--;
    }
 
 public function getNodeByProp() // 根据属性名称和值取得节点，
 {       // 参数：属性名称,属性值1,属性值2,属性值3,...
  $args = func_get_args();
  $node = $this->tree;
  for($i=1;$i<count($args);$i )
  {
   $node = $this->_getNodeByProp($node,$args[0],$args[$i]);
   if($node==NULL) break;
  }
  return $node;
 }
 
 public function getChildByTag($node,$tag)  // 取得$node节点下标签为$tag的节点
 {
  for($i=0;$i<count($node->children);$i )
  {
   if($node->children[$i]->tag==$tag)
    return $node->children[$i];
  }
  return NULL;
 }
 
 public function getChildsByTag($node,$tag) // 取得$node节点下标签为$tag的节点，返回节点列表数组
 {
  $rs = array();
  for($i=0;$i<count($node->children);$i )
   if($node->children[$i]->tag==$tag)
    array_push($rs,$node->children[$i]);
  return $rs;
 }
 
 public function addRoot($tag)   // 添加根节点
 {
  $this->tree->children = array();
  $this->root = $this->addChild($this->tree,$tag);
  return $this->root;
 }
 // 在$node节点下添加标签为$tag的节点，并返回添加的节点
 public function addChild($node,$tag)  
 {
  $o = new stdClass;
  $o->p = $node;
  $o->level = $node->level1;
  $o->index = count($node->children);
  $o->tag = $tag;
  $o->props = new stdClass;
  $o->value = "";
  $o->children = array();
  array_push($node->children,$o);
  return $o;
 }
 // 删除$node节点
 public function delete($node)  
 {
  $p = $node->p;
  array_splice($p->children,$node->index,1);
  for($i=0;$i<count($p->children);$i )
   $p->children[$i]->index = $i;
 }
 // 将srcNode移动到$dstNode下面
 public function move($dstNode,$srcNode) 
 {
  $this->delete($srcNode); 
  $srcNode->p = $dstNode;
  $srcNode->level = $dstNode->level1;
  $srcNode->index = count($dstNode->children);
  array_push($dstNode->children,$srcNode);
 }
 // 返回xml格式串
 public function __toString()  
 {
  $s = "";
  for($i=0;$i<count($this->tree->children);$i )
   $s .= $this->traversalNodeToXml($this->tree->children[$i],$this->endtag);
  return $s;
 }
// 保存成xml格式文件
 public function save($xmlFile)  
 {
  $content = $this->__toString();
  
  $fp = @fopen($xmlFile,"w") or die("创建文件失败：".$xmlFile);
  @fwrite($fp,$content);
  @fclose($fp);
  @chmod($xmlFile,0777);
 }
 
 private function traversalNodeToXml($treeNode,$endtag)
 {
  $space = "";
  $space = str_pad($s,$treeNode->level*2," ",STR_PAD_LEFT);
  $s = $space."<".$treeNode->tag;
  while(list($key,$value)=each($treeNode->props))
   $s .= " $key=\"$value\"";
  $childCount = count($treeNode->children);
  if($childCount==0)
  {
   if($treeNode->value!="" || $endtag)
    $s .= ">".$treeNode->value."</".$treeNode->tag.">\n";
   else
    $s .= "/>\n";
   return $s;
  }
  
  $s .= ">\n";
  for($i=0;$i<$childCount;$i )
   $s .= $this->traversalNodeToXml($treeNode->children[$i],$endtag);
  $s .= $space."</".$treeNode->tag.">\n";
  return $s;
 }
 
 private function _getNodeByProp($node,$propName,$propValue)
 {
  for($i=0;$i<count($node->children);$i )
  {
   $anode = $node->children[$i];
   if(isset($anode->props) && isset($anode->props->{$propName}))
   {
    if($anode->props->{$propName}==$propValue) return $anode;
    continue;
   }
   $anode = $this->_getNodeByProp($node->children[$i],$propName,$propValue);
   if($anode!=NULL) return $anode;
  }
  return NULL;
 }
};
?>