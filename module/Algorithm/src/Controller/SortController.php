<?php
namespace Algorithm\Controller;
use COM\Controller;
class SortController extends Controller{

    private $raw = array(
        5, 4, 3, 13, 45, 192, 35, 98, 78, 454
    );

    public function indexAction(){
        echo '<pre>';
        print_r($this->raw);
        $this->heapSort();
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

    private function heapSort(){



    }

}