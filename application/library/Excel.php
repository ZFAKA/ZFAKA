<?php
/*
 * 功能：生成Excel文件类
 * author:Yinson
 * modify:20160906
 */
/*
 * 调用
 * $excel_obj = new Excel();
 * //设置样式
 * $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
 * //转码
 * $excel_data = $excel_obj->charset($excel_data);
 * 添加多行数据
 * $excel_obj->addArray($excel_data, "UTF8");
 * //添加工作表
 * $excel_obj->addWorksheet($excel_obj->charset('中文', "UTF8"));
 * //生成excel文件
 * $excel_obj->generateXML($excel_obj->charset('中文', "UTF8").'11'.'-'.date('Y-m-d-H',time()));
 */
class Excel{
    /**
     * excel文档头(返回的行)
     *
     * 依照excel xml规范。
     * @access private
     * @var string
     */
    private $header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?\>\n"
                       . "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n"
                       . "xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n"
                       . "xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n"
                       . "xmlns:html=\"http://www.w3.org/TR/REC-html40\">";

    /**
     * excel页脚
     * 依照excel xml规范。
     *
     * @access private
     * @var string
     */
    private $footer = "</Workbook>";

    /**
     * 文档行(行数组中)
     *
     * @access private
     * @var array
     */
    private $lines = array ();
    /**
     * 工作表(数组)
     *
     * @access private
     * @var array
     */
    private $worksheets = array ();
    /**
     * 单元格样式
     * @access private
     * @var string
     */
    private $cellstyle = array();

    /**
     * 默认单元格数据格式
     * @access private
     * @var string
     */
    private $default_cellformat = "String";

    public function __construct(){
        //设置默认样式
        $this->cellstyle['Default'] = "<Style ss:ID=\"Default\" ss:Name=\"Normal\">\n"
                                       . "<Alignment ss:Vertical=\"Center\"/>\n"
                                       . "<Borders/>\n"
                                       . "<Font ss:FontName=\"宋体\" x:CharSet=\"134\" ss:Size=\"11\" ss:Color=\"#000000\"/>\n"
                                       . "<Interior/>\n"
                                       . "<NumberFormat/>\n"
                                       . "<Protection/>"
                                       . "</Style>";
    }
    /**
     * 添加单行数据
     *
     * @access private
     * @param array 1维数组
     * @todo 行创建
     */
    private function addRow($array)
    {
        //初始化单元格
        $cells = "";
        //构建单元格
        foreach($array as $k => $v)
        {
            $style_str = "";
            if(!empty($v['styleid']))
            {
                $style_str = "ss:StyleID=\"{$v['styleid']}\"";
            }
            $format_str = $this->default_cellformat;
            if(!empty($v['format']))
            {
                $format_str = $v['format'];
            }
            $cells .= "<Cell {$style_str}><Data ss:Type=\"{$format_str}\">{$v['data']}</Data></Cell>\n";
        }
        //构建行数据
        $this->lines[] = "<Row>\n{$cells}</Row>\n";
    }
    /**
     * 添加多行数据
     * @access public
     * @param array 2维数组
     * @todo 构造多行
     */
    public function addArray($array)
    {
        $this->lines = array();
        //构建行数据
        foreach((array)$array as $k => $v)
        {
            $this->addRow ($v);
        }
    }
    /**
     * 添加工作表
     * @access public
     * @param string $sheettitle 工作表名
     * @todo 构造工作表XML
     */
    public function addWorksheet($sheettitle)
    {
        //剔除特殊字符
        $sheettitle = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $sheettitle);
        //现在,将其减少到允许的长度
        //$sheettitle = substr ($sheettitle, 0, 50);
        $this->worksheets[] = "\n<Worksheet ss:Name=\"{$sheettitle}\">\n<Table ss:DefaultRowHeight=\"20\">\n"
            . "<Column ss:Index=\"1\" ss:AutoFitWidth=\"0\"/>\n"
            . implode ("\n", $this->lines)
            . "</Table>\n</Worksheet>\n";
    }
    /**
     * 设置单元格样式
     *
     * @access public
     * @param array 样式数组例如： array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1'));
     * 当id为Default时，为表格的默认样式
     */
    public function setStyle($style_arr)
    {
        if(empty($style_arr))
        {
            return false;
        }
        $id = $style_arr['id'];
        unset($style_arr['id']);
        $style_str = "<Style ss:ID=\"{$id}\">";
        foreach($style_arr as $k=>$v){
            $tmp = '';
            foreach((array)$v as $k_item=>$v_item)
            {
                $tmp .= (" ss:{$k_item}=\"{$v_item}\"");
            }
            $style_str .= "<{$k} {$tmp}/>";
        }

        $this->cellstyle[$id] = $style_str."</Style>";
    }
    /**
     * 设置默认单元格格式
     *
     * @access public
     * @param string
     */
    public function setDefaultFormat($format_str)
    {
        if(empty($style_arr))
        {
            return false;
        }
        $this->default_cellformat = $format_str;
    }
    /**
     * 生成excel文件
     * 最后生成excel文件,并使用header()函数来将它交付给浏览器。
     * @access public
     * @param string $filename 文件名称
     */
    public function generateXML ($filename)
    {
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        //头
        $ua = $_SERVER["HTTP_USER_AGENT"];
        header("Content-Type: application/vnd.ms-excel");
        if(preg_match("/MSIE/", $ua))
        {
            header("Content-Disposition: attachment; filename=\"{$encoded_filename}.xls\"");
        }
        elseif(preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''{$filename}.xls\"");
        }
        else
        {
            header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
        }
        header("Cache-Control: max-age=0");
        echo stripslashes ($this->header);
        //样式
        echo "\n<Styles>";
        foreach((array)$this->cellstyle as $k=>$v)
        {
            echo "\n".$v;
        }
        echo "\n</Styles>";
        //工作表
        echo implode ("\n", $this->worksheets);
        echo $this->footer;
    }
    /**
     * 转码函数
     *
     * @param mixed $content
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function charset($content, $from='gbk', $to='utf-8')
    {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($content))
        {
            //如果编码相同则不转换
            return $content;
        }
        if (function_exists('mb_convert_encoding'))
        {
            if (is_array($content))
            {
                $content = var_export($content, true);
                $content = mb_convert_encoding($content, $to, $from);
                eval("\$content = $content;");return $content;
            }
            else
            {
                return mb_convert_encoding($content, $to, $from);
            }
        }
        elseif (function_exists('iconv'))
        {
            if (is_array($content))
            {
                $content = var_export($content, true);
                $content = iconv($from, $to, $content);
                eval("\$content = $content;");return $content;
            }
            else
            {
                return iconv($from,$to,$content);
            }
        }
        else
        {
            return $content;
        }
    }
}
