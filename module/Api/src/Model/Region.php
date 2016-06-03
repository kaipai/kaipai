<?php
namespace Api\Model;

use COM\Model;
class Region extends Model{
    public function getRegionsByPid($pid){

        $data = $this->setColumns(array('name', 'regionID', 'pid'))->select(array('pid' => $pid))->toArray();

        return $data;
    }
}