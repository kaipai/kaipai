<?php
namespace Api\Model;

use COM\Model;
class SiteSetting extends Model{
    public function getSettings(){
        $select = $this->getSelect();
        $select->order('sort DESC');

        return $this->selectWith($select)->toArray();
    }

}