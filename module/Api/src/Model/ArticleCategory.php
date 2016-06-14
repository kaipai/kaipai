<?php
namespace Api\Model;

use COM\Model;
class ArticleCategory extends Model{

    public function getCategories(){
        $res = $this->select(array('display' => 1))->toArray();

        return $res;
    }

}