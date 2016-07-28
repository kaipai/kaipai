<?php
namespace Api\Model;

use COM\Model;
class Region extends Model{
    public function getRegionsByPid($pid){

        $data = $this->setColumns(array('name', 'regionID', 'pid'))->select(array('pid' => $pid))->toArray();

        return $data;
    }

    public function getRegionInfo($cityID){
        $cityInfo = $this->select(array('regionID' => $cityID))->current();
        $regionInfo = array();
        if(!empty($cityInfo)){
            $regionInfo['cityID'] = $cityInfo['regionID'];
            $regionInfo['cityName'] = $cityInfo['name'];
            $provinceInfo = $this->select(array('regionID' => $cityInfo['pid']))->current();
            if(!empty($provinceInfo)){
                $regionInfo['provinceID'] = $provinceInfo['regionID'];
                $regionInfo['provinceName'] = $provinceInfo['name'];
            }
        }

        return $regionInfo;
    }
}