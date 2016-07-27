<?php
namespace Algorithm\Controller;

use COM\Controller;

class BasisController extends Controller{

    public function indexAction(){
        //fscanf(STDIN, "%d", $n);

        //$result = $this->getSandClock(17);
        //$result = $this->primeNumberPair($n);
        //$arr = explode(',', $arr);
        //$result = $this->moveRight($m, $arr);
        $result = $this->shuffle();
        echo $result;

        return $this->response;
    }

    private function shuffle(){
        $n = 2;
        $s = '36,52,37,38,3,39,40,53,54,41,11,12,13,42,43,44,2,4,23,24,25,26,27,6,7,8,48,49,50,51,9,10,14,15,16,5,17,18,19,1,20,21,22,28,29,30,31,32,33,34,35,45,46,47';
        $cards = array();
        $color = array('S', 'H', 'C', 'D');
        for($i = 0; $i < count($color); $i++){
            for($j = 1; $j <= 13; $j++){
                $cards[$i * 13 + $j] = $color[$i] . $j;
            }
        }
        $cards[] = 'J1';
        $cards[] = 'J2';

        $s = explode(',', $s);
        for($i = 1; $i <= $n; $i++){
            $newCards = array();
            for($j = 0; $j <= 53; $j++){
                if(!empty($s[$j])){
                    $newCards[$s[$j]] = $cards[$j + 1];
                }else{
                    $newCards[$j + 1] = $cards[$j + 1];
                }
            }
            ksort($newCards);
            $cards = $newCards;

        }

        return implode(',', $cards);
    }

    private function double($n){
        $arr = str_split($n);
        $arrCount = array();
        $count = count($arr);
        $newArr = array();
        $newArrCount = array();
        $further = 0;
        $resultStr = '';
        $output = '';
        for($i = $count - 1; $i >= 0; $i--){
            $double = $arr[$i] * 2;
            if($further){
                $double += $further;
            }
            $result = $double % 10;
            $newArr[] = $result;
            $further = floor($double / 10);
            $arrCount[$arr[$i]] += 1;
        }
        if($further){
            $newArr[] = $further;
        }

        $newCount = count($newArr);
        for($i = $newCount - 1; $i >= 0; $i--){
            $resultStr .= $newArr[$i];
            $newArrCount[$newArr[$i]] += 1;
        }
        $match = 'YES';
        for($i = 0; $i < count($newArrCount) - 1; $i++){
            if($newArrCount[$i] != $arrCount[$i]) {
                $match = 'NO'; break;
            }
        }

        $output .= $match . "\n" . $resultStr;
        return $output;
    }

    private function moveRight($m, $arr){
        $count = count($arr);
        for($i = 0; $i < $count; $i++){
            $newKey = $i + $m + $count;
            if($newKey > 2 * $count - 1){
                $arr[$newKey - $count] = $arr[$i];
            }else{
                $arr[$newKey] = $arr[$i];
            }
        }
        for($i = 0; $i < $count; $i++){
            unset($arr[$i]);
        }
        ksort($arr);

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