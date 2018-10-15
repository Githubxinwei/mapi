<?php
namespace common\models;

use Yii;
use yii\base\Model;

class Excel extends Model
{
    const LETTER = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX',"AY"];
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadFile($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'AQ';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();

                if($column == 0 || $column == 10 || $column == 11 || $column == 13){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }elseif($column == 9){
                    $res[$row-4][$column] = (int)$val;
                }elseif($column == 40){
                    $res[$row-4][$column] = (string)$val;
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;

    }

    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadFileThree($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'AN';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();

                if($column == 9|| $column == 10 || $column == 12){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }

        return $res;

    }
    /**
     * 导入excel文件
     * @author jiaobingyang
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadFileFive($path,$type='member')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP');
        $res = array();
//        $excelCol = $type == 'charge' ? 'AX' : 'AP';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= 41; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 12|| $column == 13 || $column == 15){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        
        return $res;

    }
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadFileTwo($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'AR';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();

                if($column == 0 || $column == 12|| $column == 13 || $column == 15){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }

        return $res;

    }
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadMemberCardFile($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'N' : 'N';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 7 || $column == 8 || $column == 9){
                    if(is_integer($val)){
                        $res[$row-1][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-1][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-1][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadFileNumber($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'O';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();

                if($column == 11){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }

        return $res;

    }
    /**
     * 导入excel文件
     * @author houkaixin
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadFileCharge($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AY' : 'AN';
        for ($row = 5; $row <= $highestRow; $row++) {
                for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                    $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                    if($column == 0 || $column == 2|| $column == 4 || $column == 6 || $column == 7 || $column == 13|| $column == 15){
                        continue;
                    }
                    if($column == 0 || $column == 8 || $column == 9 || $column == 25){
                        if(is_integer($val)){
                            $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                        }else{
                            $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                        }
                    }elseif($column == 3){
                        $res[$row-4][$column] = (string)$val;
                    }else{
                        $res[$row-4][$column] = $val;
                    }
                }
        }

        return $res;
    }
    /**
     * 导入excel文件
     * @author huangpengju
     * @param $path //excel本地路径
     * @param $type //类型
     * @return array excel里的数据组成的数组
     */
    public function loadFileMember($path,$type='member')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'member' ? 'AX' : 'AN';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 7){
                    if(is_integer($val)){
                        $res[$row-1][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-1][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-1][$column] = $val;
                }
                if($column==10){
                    break;
                }
            }
        }
        return $res;

    }
    /**
     * 导入excel文件
     * @author houkaixin    // 导入柜子租用信息（衣柜租用2008-2017.xls）
     * @param $path //excel本地路径
     * @param $type //类型
     * @return array excel里的数据组成的数组
     */
    public function loadCabinetFile($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'member' ? 'AX' : 'AN';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0||$column == 2|| $column == 3||$column == 6 || $column == 12|| $column == 14){
                    continue;
                }
                if($column == 8 || $column == 9|| $column == 17){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
                if($column==39){
                    break;
                }
            }
        }
        return $res;

    }



    /**
     * 导入excel文件
     * @author houkaixin    // 导入柜子租用信息（衣柜租用2008-2017.xls）
     * @param $path //excel本地路径
     * @param $type //类型
     * @return array excel里的数据组成的数组
     */
    public function loadExpireCabinetFile($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'member' ? 'AX' : 'AN';
        for ($row = 5; $row < $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0||$column == 2|| $column == 5||$column == 6 || $column == 7||  $column == 13 || $column == 15){
                    continue;
                }
                if($column == 10 || $column ==11){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
                if($column==16){
                    break;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author houkaixin    // 请假表的数据导入
     * @param $path //excel本地路径
     * @param $type //类型
     * @return array excel里的数据组成的数组
     */
    public function loadMemberLeaveRecord($path,$type='member'){

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'member' ? 'AX' : 'AN';
        for ($row = 4; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                if($column==21){
                    break;
                }
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0||$column == 3|| $column == 4||$column == 5 || $column == 6||  $column == 8 ||$column == 9||$column == 15||$column == 17){
                    continue;
                }
                if($column == 12 || $column ==13||$column ==14||$column ==20){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        if(!empty($val)){
                            $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                        }else{
                            $res[$row-4][$column] = $val;
                        }
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件（会员卡转让）
     * @author huangpengju
     * @param $path //excel本地路径
     * @param $type //类型
     * @create 2017/6/19
     * @return array excel里的数据组成的数组
     */
    public function loadFileTransfer($path,$type='transfer')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');
        $res = array();
        $excelCol = $type == 'transfer' ? 'AP' : 'U';
        if($type == 'transfer')
        {
            for ($row = 5; $row <$highestRow; $row++) {
                for ($column =1; self::LETTER[$column] != $excelCol; $column++) {

                    $val = $objWorksheet->getCellByColumnAndRow($column,$row)->getValue();
                    if($column == 1 ){
                        if(is_integer($val)){
                            $res[$row-5][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                        }else{
                            $res[$row-5][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                        }
                    }else{
                        $res[$row-5][$column] = $val;
                    }
                }
            }
        }else if($type == 'memberTransfer'){
            for ($row = 4; $row <=$highestRow; $row++) {
                for ($column =0; self::LETTER[$column] != $excelCol; $column++) {
                    $val = $objWorksheet->getCellByColumnAndRow($column,$row)->getValue();
                        $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 李慧恩
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadEmployeeFile($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'K';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();

                if($column == 0 || $column == 9){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }

        return $res;

    }

    /**
     * 导入excel文件
     * @author 朱梦珂
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadPrivateExpireFile($path,$type='private')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'private' ? 'AX' : 'AN';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                $res[$row-1][$column] = $val;
                if($column==12){
                    break;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadYanFile($path,$type='private')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        $excelCol = $type == 'private' ? 'AX' : 'AN';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadZhangFile($path,$type='private')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        $excelCol = $type == 'private' ? 'AX' : 'AN';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= $highestRow; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadJingFile($path,$type='private')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        $excelCol = $type == 'private' ? 'AX' : 'AN';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= $highestRow; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadUnitDataFile($path,$type='private')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = [0=>'A',1=>'B'];
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        $excelCol = $type == 'private' ? 'AX' : 'AN';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadDelKaiFile($path)
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = [0=>'A',1=>'B'];
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= $highestRow; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadCorrectCardFile($path,$type='private')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = [0=>'A',1=>'B'];
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= $highestRow; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }
    
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadDelCardFile($path)
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = [0=>'A',1=>'B'];
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= $highestRow; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadTimeFile($path)
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr           = array(0 => 'A',1 => 'B');
        $res = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= $highestRow; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadCardCategoryFile($path)
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M');
        $res = array();
        $excelCol = 'N';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                $res[$row][$column] = $val;
            }
        }
        return $res;
    }
    
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadTurnMembersFile($path)
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = [0=>'A',1=>'B'];
        $arr = array(0 => 'A',1 => 'B');
        $res = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= $highestRow; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
                if($column==1){
                    break;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 朱梦珂
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadPrivateNewExpireFile($path,$type='private')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'private' ? 'J' : 'AN';
        for ($row = 3; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 6 ||$column == 7 || $column == 8){
                    if($column == 7 || $column == 8){
                        $res[$row-2][$column]  =   \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }else{
                        $arr = explode('.',$val);
                        $val = implode('-',$arr);
                        $res[$row-2][$column] = strtotime($val);
                    }
                }else{
                    $res[$row-2][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author 朱梦珂
     * @param $path excel 本地路径
     * @param $type
     * @return array excel里的数据组成的数组
     */
    public function loadSwimExpireFile($path,$type='private')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'private' ? 'AX' : 'AN';
        for ($row = 4; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 7 || $column == 8 || $column == 9){
//                    if(is_integer($val)){
//                        $res[$row-3][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
//                    }else{
//                        $res[$row-3][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
//                    }
                    $arr = explode('.',$val);
                    $val = implode('-',$arr);
                    $res[$row-3][$column] = strtotime($val);
                }else{
                    $res[$row-3][$column] = $val;
                }
                if($column==9){
                    break;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author houkaixin
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadFileChargeClass($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AY' : 'AN';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                if($column == 23){
                    break;
                }
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0 || $column == 2|| $column == 5|| $column == 6 || $column == 10 || $column == 13){
                    continue;
                }
                if($column == 8 || $column == 9){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;
    }

    public function loadEndTimeCharge($path,$type='member'){
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'P' : 'AN';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                if($column == 23){
                    break;
                }
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
//                if($column == 0 || $column == 2|| $column == 4|| $column == 7 || $column == 11 || $column == 16|| $column == 18){
//                    continue;
//                }
                if($column == 3 || $column == 5|| $column == 6){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadFileAiBoMember($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'L';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();

                if($column == 7|| $column == 10 || $column == 8){
                    $res[$row-1][$column] = $val;
                }else{
                    $res[$row-1][$column] = $val;
                }
            }
        }

        return $res;

    }
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadFileYaMember($path,$type='member')
    {
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'O';
        for ($row = 11; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 4 || $column == 8|| $column == 10 || $column == 11){
                    if(strpos($val, '.')!==FALSE){
                        $arr = explode('.',$val);
                        $val = implode('-',$arr);
                        $res[$row-10][$column] = strtotime($val);
                    }elseif(is_double($val)){
                        $res[$row-10][$column] = ($val-25569)*3600*24;
                    }else{
                        $res[$row-10][$column] = 1;
                    }
                }else{
                    if($column == 3){
                        $res[$row-10][$column] = (string)$val;
                    }else{
                        $res[$row-10][$column] = $val;
                    }
                }
            }
        }

        return $res;

    }
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadFileAiBoNewMember($path,$type='O',$date='one')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type;
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($type == 'R'){
                    if($column == 5){
                        if($date == 'two'){
                            $res[$row-2][$column] = strtotime($val);;
                        }else{
                            $res[$row-2][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                        }
                    }else{
                        $res[$row-2][$column] = $val;
                    }
                }else{
                    if($column == 10 ||$column == 8 || $column == 9){
                        $arr = explode('.',$val);
                        $v   = implode('-',$arr);
                        $val = strtotime($v);
                        $res[$row-1][$column] = $val;
//                        $res[$row-2][$column] = \PHPExcel_Shared_Date::ExcelToPHP(intval($val));
                    }
//                    elseif ( ){
//                        $arr = explode('.',$val);
//                        $v   = implode('-',$arr);
//                        $val = strtotime($v);
//                        $res[$row-2][$column] = $val;
//                    }
                    else{
                        $res[$row-1][$column] = $val;
                    }
                }
            }
        }

        return $res;

    }
    /**
     * 导入excel文件
     * @author houkaixin
     * @param $path    // 文件路径
     * @return array excel里的数据组成的数组
     */
    public function loadAiBoMemberLeaveRecord($path,$type='member'){
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');
        $res = array();
        $excelCol = $type == 'charge' ? 'AY' : 'AN';
        for ($row = 4; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                if($column == 16){
                    break;
                }
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 5|| $column == 6||$column == 7|| $column == 8|| $column == 14){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = strtotime($val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author houkaixin
     * @param $path    // 文件路径
     * @param $type    // 请求类型
     * @return array excel里的数据组成的数组
     */
    public function loadAiBoMemberHangUp($path,$type='member'){
        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');
        $res = array();
        $excelCol = $type == 'charge' ? 'AY' : 'AN';
        for ($row = 4; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                if($column == 15){
                    break;
                }
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 4|| $column == 9||$column == 10|| $column == 13){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = strtotime($val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadExpireFile($path,$type='member')
    {

        $objPHPExcel   = \PHPExcel_IOFactory::load($path);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q');

        $res = array();
        $excelCol = $type == 'charge' ? 'AX' : 'P';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();

                if($column == 3|| $column == 5 || $column == 6){
                    if(is_integer($val)){
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }else{
                        $res[$row-4][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                    }
                }else{
                    $res[$row-4][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadBringFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 13 => 'M');
        $res = array();
        $excelCol = 'N';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                $res[$row][$column] = $val;
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author jiaobingyang
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadTakeClassFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O', 15 => 'P', 16 => 'Q', 17 => 'U', 18 => 'V', 19 => 'W');
        $res = array();
        $excelCol = 'X';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 15|| $column == 16){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadPhoneFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D');
        $res = array();
        $excelCol = 'E';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0 || $column == 2){
                    $res[$row][$column] = (string)$val;
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }
    /**
     * @author jiaobingyang
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadDuplicateCardFile($path)
    {
        $objPHPExcel  = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow   = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O');
        $res = array();
        $excelCol = 'O';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 4 || $column == 6 || $column == 7){
                    $res[$row][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                }elseif($column == 8){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author jiaobingyang
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadDuplicateCourseFile($path)
    {
        $objPHPExcel  = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow   = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O',15 => 'P',16 => 'Q', 17 => 'R',18 => 'S',19 => 'T',20 => 'U', 21 => 'V', 22 => 'W');
        $res = array();
        $excelCol = 'O';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                $res[$row][$column] = $val;
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadCollegeFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D');
        $res = array();
        $excelCol = 'J';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 6){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 7 || $column == 8){
                    $res[$row][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadDvFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M');
        $res = array();
        $excelCol = 'N';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if ($column == 0 || $column == 7 || $column == 11) {
                    $res[$row][$column] = (string)$val;
                } elseif ($column == 3 || $column == 5 || $column == 6) {
                    $res[$row][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                } else {
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }
    
    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadZhuanKaFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO');
        $res = array();
        $excelCol = 'AP';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 1){
                    $res[$row][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                }elseif($column == 11 || $column == 15 || $column == 18 || $column == 20 || $column == 21 || $column == 22 || $column == 23 || $column == 24 || $column == 25 || $column == 26 || $column == 27 || $column == 28 || $column == 29){
                    $res[$row][$column] = (string)$val;
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadMatchCourseFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y');
        $res = array();
        $excelCol = 'Z';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 3 || $column == 4 || $column == 11){
                    $res[$row][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                }elseif($column == 1 || $column == 7 || $column == 8 || $column == 12 || $column == 13 || $column == 14){
                    $res[$row][$column] = (string)$val;
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadDelMemberFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D');
        $res = array();
        $excelCol = 'D';
        for ($row = 3; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 2){
                    $res[$row][$column] = (string)$val;
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author xingsonglin
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadCorrectTimeFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'G');
        $res = array();
        $excelCol = 'H';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 0){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 3 || $column == 5 || $column == 6){
                    $res[$row][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadZhangShuXiaFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM');
        $res = array();
        $excelCol = 'AN';
        for ($row = 5; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 2 || $column == 7 || $column == 9 || $column == 14 || $column == 15 || $column == 17){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 10 || $column == 11 || $column == 13){
                    $res[$row][$column] = \PHPExcel_Shared_Date::ExcelToPHP((int)$val);
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /*
     * @author jiaobingyang
     * @param $path excel本地路径
     * @return array excel里的数据组成的数组
     */
    public function loadDuJianFile($path)
    {
        $objPHPExcel  = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow   = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'O');
        $res = array();
        $excelCol = 'O';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 2 || $column == 6){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 8 || $column == 9 || $column == 10){
                    $res[$row][$column] = strtotime(implode('-',explode('.',$val)));
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadYaXingFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P');
        $res = array();
        $excelCol = 'Q';
        for ($row = 3; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 4){
                    $res[$row][$column] = implode('-',explode('.',$val));
                }elseif($column == 1 || $column == 5 || $column == 9 || $column == 13){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 8 || $column == 11 || $column == 12){
                    $res[$row][$column] = strtotime(implode('-',explode('.',$val)));
                }elseif($column == 14){
                    $res[$row][$column] = (int)$val;
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadBuKaFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P');
        $res = array();
        $excelCol = 'Q';
        for ($row = 3; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 4){
                    $res[$row][$column] = implode('-',explode('.',$val));
                }elseif($column == 1 || $column == 5 || $column == 9 || $column == 13 || $column == 15){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 8 || $column == 11 || $column == 12){
                    $res[$row][$column] = strtotime(implode('-',explode('.',$val)));
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadZhuanRangFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M',13=>'N',14=>'O',15=>'P');
        $res = array();
        $excelCol = 'Q';
        for ($row = 3; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 4 || $column == 8 || $column == 12){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 7 || $column == 10 || $column == 11){
                    $res[$row][$column] = strtotime(implode('-',explode('.',$val)));
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadAddProfileFile($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J');
        $res = array();
        $excelCol = 'Q';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 3){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 6 || $column == 7){
                    $res[$row][$column] = strtotime(implode('-',explode('.',$val)));
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }

    /**
     * 导入excel文件
     * @author 焦冰洋
     * @param $path excel 本地路径
     * @param $path
     * @return array excel里的数据组成的数组
     */
    public function loadXiaoFei($path)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(0 => 'A',1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G', 7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M');
        $res = array();
        $excelCol = 'N';
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; self::LETTER[$column] != $excelCol; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                if($column == 1 || $column == 7){
                    $res[$row][$column] = (string)$val;
                }elseif($column == 6 || $column == 9 || $column == 10){
                    $res[$row][$column] = strtotime(implode('-',explode('.',$val)));
                }else{
                    $res[$row][$column] = $val;
                }
            }
        }
        return $res;
    }


}
