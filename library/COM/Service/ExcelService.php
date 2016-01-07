<?php
namespace COM\Service;

use Base\ConstDir\BaseConst;

/**
 * @description 文件导入导出类
 * @author 童旭华
 * @date 2015-7-21
 */
class ExcelService
{
    private $_filename = '';

    private $_data = array();

    private $_objPHPExcel = '';

    private $_creator = ''; //创建者
    private $_lastModifiedBy = '';  //最后修改这
    private $_title = '';   //标题
    private $_subject = '';   //
    private $_description = '';   //描述
    private $_keyword = '';   //关键字
    private $_category = '';   //类别
    private $_suffix = '.xls';

    //*****配置项*****
    private $_formatNumber = true;    //设置单元格为长整形
    private $_horizontal = "center";    //默认水平居中
    private $_vertical = "center";    //默认垂直居中
    private $_family = "楷体"; //默认字体
    private $_size = 12; //默认字体大小

    /**
     * @descrption 初始化函数
     */
    public function __construct($filename, $title = '', $creator = '', $lastModifiedBy = '', $subject = '', $description = '', $keyword = '', $category = '')
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        include ZF2 . "/PHPExcel.php";
        $this->_objPHPExcel = new \PHPExcel();

        //*****初始化*****
        $this->_filename = $filename;
        $this->_title = $title;
        $this->_creator = $creator;
        $this->_lastModifiedBy = $lastModifiedBy;
        $this->_subject = $subject;
        $this->_description = $description;
        $this->_keyword = $keyword;
        $this->_category = $category;
    }


    /**
     * @description 导出Excel5
     * @param array $data 导出数据集
     */
    public function exportExcel5($data)
    {

        $this->_parseData($data);
//        $this->_setProperties($data);
        $this->_suffix = ".xls";
        $this->exportExcel("Excel5");
    }

    /**
     * @description 导出Excel7
     * @param array $data 导出数据集
     */
    public function exportExcel7($data)
    {
        $this->_parseData($data);
        $this->_setProperties();
        $this->_suffix = ".xlsx";
        $this->exportExcel("Excel2007");
    }

    /**
     * @description 导出Excel7
     * @param array $data 导出数据集
     */
    public function exportExcel($type = "Excel5")
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->_filename . $this->_suffix . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($this->_objPHPExcel, $type);
        @ob_end_clean();
        $objWriter->save('php://output');
    }

    /**
     * @description 解析数据
     * @param array $data 导出数据集
     */
    private function _parseData($data, $sheetIndex = 0)
    {
        if (!is_array($data)) return false;

        $rows = 1;
        foreach ($data as $key => $val) {
            if (isset($val['sheetIndex'])) {
                $this->_parseData($val['data'], $val['sheetIndex']);
                $msgWorkSheet = new \PHPExcel_Worksheet($this->_objPHPExcel);
                $this->_objPHPExcel->addSheet($msgWorkSheet);
            } else {
                $cols = 'A';
                $this->_objPHPExcel->setActiveSheetIndex($sheetIndex);
                if ($this->_formatNumber) {
                    $this->_objPHPExcel->getActiveSheet()->getStyle($rows)->getNumberFormat()
                        ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
                }
                if (is_array($val)) {
                    foreach ($val as $dkey => $dval) {
                        if ($dkey !== 'cellAttribute') {
                            $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValue($cols . $rows, $dval);
                            $cols++;
                        }
                    }
                }
            }
            $rows++;
        }
    }

    /**
     * @description 设置表格属性方法，循环data并取相关属性值
     */
    private function _setProperties($data, $sheetIndex = 0)
    {
        $rows = 1;
        foreach ($data as $rkey => $rval) {//行循环
            $cols = "A";
            if (isset($rval["sheetIndex"])) {
                if (isset($rval["cellAttribute"])) $this->_setPropertiesActual($rval["sheetIndex"], $rval["cellAttribute"]);
                $this->_setProperties($rval["data"], $rval["sheetIndex"]);
            } else {
                foreach ($rval as $ckey => $cval) {//列循环
                    if ($ckey !== 'cellAttribute') {
                        $this->defaultPropertiesActual($sheetIndex, $cols . $rows);
                        if (isset($rval['cellAttribute'])) {
                            $this->_setPropertiesActual($sheetIndex, $rval['cellAttribute'], $cols . $rows);
                        }
                        if (isset($cval['cellAttrbute'])) $this->_setPropertiesActual($sheetIndex, $cval["cellAttribute"], $cols . $rows);
                        $cols++;
                    }
                }
            }
            $rows++;
        }
    }

    /**
     * @description 实际设置具体属性
     * @param int $sheetIndex sheet下表
     * @param array $attribute 设置属性
     * @param string $cell 对应单元格
     */
    private function _setPropertiesActual($sheetIndex = 0, $attribute = array(), $cell = 0)
    {
        $style = $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->getStyle($cell);
        if (isset($attribute['size'])) {
            $style->getFont()->setSize($attribute['size']);
        }

        if (isset($attribute['color']))
            $style->getFont()->getColor()->setRGB($attribute['color']);

        if (isset($attribute['bdColor'])) {
            $style->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $style->getFill()->getStartColor()->setRGB($attribute['bdColor']);
        }

        if (isset($attribute['title']))
            $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->setTitle($attribute['title']);

        if (isset($attribute['width'])) {
            foreach ($attribute['width'] as $key => $val) {
                if (strpos($key, "-") !== false) {
                    $arr = explode("-", $key);
                    for ($i = $arr[0]; $i <= $arr[1]; $i++) {
                        if ($val == 'auto') {
                            $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->getColumnDimension($i)->setAutoSize(true);
                        } else {
                            $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->getColumnDimension($val)->setWidth($val);
                        }
                    }
                } else {
                    if ($val == 'auto') {
                        $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->getColumnDimension($i)->setAutoSize(true);
                    } else {
                        $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->getColumnDimension($val)->setWidth($val);
                    }
                }
            }
        }

    }

    private function defaultPropertiesActual($sheetIndex = 0, $cell = 0)
    {
        $style = $this->_objPHPExcel->setActiveSheetIndex($sheetIndex)->getStyle($cell);

        //*****常用初始化设置******
        $style->getAlignment()->setHorizontal($this->_horizontal); //水平   
        $style->getAlignment()->setVertical($this->_vertical);  //垂直
        $style->getFont()->setName($this->_family);
        $style->getFont()->setSize($this->_size);
        $style->getFont()->setBold(true);
    }

    /**
     * @description 设置参数
     * @param array $param 设置项
     */
    public function setConfig($param)
    {
        foreach ($param as $key => $val) {
            if (isset($this->$key)) $this->$key = $val;
        }
    }
}

