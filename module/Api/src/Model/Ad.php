<?php
namespace Api\Model;

use COM\Model;
class Ad extends Model{
    public function getAdByPosition($position){
        if(!empty($position)){
            $ads = $this->select(array('position' => $position))->toArray();

            return $ads;
        }else{
            return false;
        }
    }
}