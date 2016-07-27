<?php
namespace Algorithm\Controller;

use COM\Controller;

class BasisController extends Controller{

    public function indexAction(){
        fscanf(STDIN, "%d %s", $m, $arr);
        //$result = $this->getSandClock(17);
        //$result = $this->primeNumberPair($n);
        $arr = explode(',', $arr);
        $result = $this->moveRight($m, $arr);
        echo $result;

        return $this->response;
    }

    private function moveRight($m, $arr){
        $count = count($arr);
        for($i = $count - 1; $i >= $m; $i--){
            if($i + $m > $count - 1){
                $tmp = $arr[$i + $m - $count];
                $arr[$i + $m - $count] = $arr[$i];
                $arr[$i] = $tmp;
                print_r($arr);
            }else{
                $tmp = $arr[$i];
                $arr[$i] = $arr[$i + $m];
                $arr[$i + $m] = $tmp;
                print_r($arr);
            }
        }


        return implode(',', $arr);
    }

    private function primeNumberPair($n){
        $count = 0;
        $tmp = 0;
        for($i = $n; $i > 1; $i--){

            $sqrt = ceil(sqrt($i));

            $isPrime = true;
            if($n != 2) {
                for ($j = 2; $j <= $sqrt; $j++) {
                    if ($i % $j == 0) {
                        $isPrime = false;
                        break;
                    }
                }
            }

            if($isPrime){

                if($tmp && abs($i - $tmp) == 2){
                    $count++;
                }
                $tmp = $i;
            }
        }

        return $count;
    }

    private function getSandClock($n, $flag = '*'){
        $rows = array();
        $piece = floor($n / 2);
        $flagCount = 0;
        $output = '';
        for($i = 1, $count = 0; $count <= $piece; $i += 2,$count += $i){
            $rows[] = $i;
        }
        $rowsCount = count($rows);
        if($n >= 7){
            $maxCount = $rows[$rowsCount - 1];
            for($i = $rowsCount - 1; $i >= 0; $i--){
                for($j = 0; $j < $maxCount; $j++){
                    $wrapCount = floor(($maxCount - $rows[$i]) / 2);
                    if($j < $wrapCount || $j > $rows[$i] + $wrapCount - 1){
                        $output .= ' ';
                    }else{

                        $output .= $flag;
                        $flagCount++;
                    }
                }
                $output .= "\n";
            }

            for($i = 1; $i <= $rowsCount - 1; $i++){
                for($j = 0; $j < $maxCount; $j++){
                    $wrapCount = floor(($maxCount - $rows[$i]) / 2);
                    if($j < $wrapCount || $j > $rows[$i] + $wrapCount - 1){
                        $output .= ' ';
                    }else{
                        $output .= $flag;
                        $flagCount++;
                    }
                }
                $output .= "\n";
            }
        }

        $output .= ($n - $flagCount);
        return $output;
    }
}