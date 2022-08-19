<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Jason	<1878566968@qq.com>
// +----------------------------------------------------------------------

namespace heil;

//Csv文件
class Csv{
    protected $lineBreak = "\n";
    /** 
     * @param array $content 内容二维数组，每一行一个二级数组
     * @param array $title 列标题 一维数组
     * @param array $forceStringColumn 强制转为字符的列，0表示第一列，依次类推
     * @return $file 文件数据
     */
    public function build($content, $title = null, $forceStringColumn = null){
        $data = "";
        $data .= $this->buildTitle($title);
        $data .= $this->buildContent($content, $forceStringColumn);
        return $data;
    }
    protected function buildTitle($title){
        if(is_null($title) || !is_array($title)){
            return null;
        }
        $data = implode(",", $title);
        return $data . $this->lineBreak;
    }
    protected function buildContent($content, $forceStringColumn = null){
        if (!is_array($content)){
            return null;
        }
        $data = "";
        foreach($content as $row){
            $this->forceToString($row, $forceStringColumn);
            $data .= implode(",", $row) . $this->lineBreak;
        }
        return $data;
    }
    protected function forceToString(&$row, $forceStringColumn = null){
        if (empty($forceStringColumn) || !is_array($forceStringColumn)){
            return null;
        }
        foreach($forceStringColumn as $column){
            $row[$column] = "\t" . $row[$column];
        }
    }
}

