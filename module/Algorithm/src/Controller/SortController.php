<?php
namespace Algorithm\Controller;
use COM\Controller;
class SortController extends Controller{

    private $raw = array(
        49, 7, 23, 123, 31, 123, 43, 24, 54,
    );

    public function indexAction(){
        print_r($this->raw);
        $this->quickSort();
        print_r($this->raw);
        return $this->response;
    }

    private function bubblingSort(){
        $count = count($this->raw);
        for($i = $count-1; $i >= 0; $i--){
            for($j = 0; $j < $i; $j++){
                if($this->raw[$j] > $this->raw[$j+1]){
                    $tmp = $this->raw[$j];
                    $this->raw[$j] = $this->raw[$j+1];
                    $this->raw[$j+1] = $tmp;
                }
            }
        }
        return $this->raw;
    }

    private function insertSort(){
        $count = count($this->raw);
        for($i = 1; $i < $count; $i++){
            $tmp = $this->raw[$i];
            for($j = $i - 1; $j >= 0; $j--){
                if($this->raw[$j] > $tmp){
                    $this->raw[$j + 1] = $this->raw[$j];
                    $this->raw[$j] = $tmp;
                }
            }
        }
        return $this->raw;
    }

    private function shellSort(){
        $count = count($this->raw);
        $k = floor($count / 2);
        while($k > 0){
            for($i = 0; $i < $k; $i++){
                for($j = $i; $j < $count && $j + $k < $count; $j = $j + $k){
                    if($this->raw[$j] > $this->raw[$j + $k]){
                        $tmp = $this->raw[$j + $k];
                        $this->raw[$j + $k] = $this->raw[$j];
                        $this->raw[$j] = $tmp;
                    }
                }
            }
            $k = floor($k / 2);
        }
        return $this->raw;
    }

    private function selectionSort(){
        $count = count($this->raw);
        for($i = 0; $i < $count; $i++){
            $k = $i;
            for($j = $i+1; $j < $count - 1; $j++){
                if($this->raw[$k] > $this->raw[$j]){
                    $k = $j;
                }
            }
            if($k != $i){
                $tmp = $this->raw[$k];
                $this->raw[$k] = $this->raw[$i];
                $this->raw[$i] = $tmp;
            }
        }
        return $this->raw;
    }

    private function quickSort(){
        $count = count($this->raw);
        $this->recursiveSort(0, $count - 1);
    }

    private function recursiveSort($left, $right){
        $pivot = $this->getPivot($left, $right);
        $i = $left; $j = $right - 1;
        if($i == $j) return ;

        $pivotVal = $this->raw[$pivot];
        $this->raw[$pivot] = $this->raw[$right - 1];
        $this->raw[$right - 1] = $pivotVal;

        var_dump($i, $j);
        print_r($this->raw);


        while(1){

            while($this->raw[++$i] < $pivotVal){

            }

            while($this->raw[--$j] > $pivotVal){

            }

            if($i < $j){

                $tmp = $this->raw[$i];
                $this->raw[$i] = $this->raw[$j];
                $this->raw[$j] = $tmp;

            }else{
                break;
            }
        }
        $tmp = $this->raw[$i];
        $this->raw[$i] = $this->raw[$right - 1];
        $this->raw[$right - 1] = $tmp;

        $this->recursiveSort($left, $i - 1);
        $this->recursiveSort($i + 1, $right);

    }

    private function getPivot($left, $right){
        $middle = floor(($left + $right) / 2);

        if($this->raw[$left > $this->raw[$right]]){
            $tmp = $this->raw[$left];
            $this->raw[$left] = $this->raw[$right];
            $this->raw[$right] = $tmp;
        }
        if($this->raw[$left] > $this->raw[$middle]){
            $tmp = $this->raw[$left];
            $this->raw[$left] = $this->raw[$middle];
            $this->raw[$middle] = $tmp;
        }
        if($this->raw[$middle] > $this->raw[$right]){
            $tmp = $this->raw[$middle];
            $this->raw[$middle] = $this->raw[$right];
            $this->raw[$right] = $tmp;
        }


        return $middle;
    }


    private function heapSort(){



    }

    private function initHeap(){

    }

}