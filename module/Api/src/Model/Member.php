<?php
namespace Api\Model;

use COM\Model;
class Member extends Model{

    public function auth($mobile = null, $password = null){
        return $this->select(array('mobile' => $mobile, 'password' => $this->genPassword($password)))->current();
    }

    public function add($data = array()){
        $memberData = array(
            'mobile' => $data['Mobile'],
            'password' => $this->genPassword($data['Password']),
        );
        $memberInfoData = array(
        );
        try{
            $this->beginTransaction();
            $this->insert($memberData);
            $memberInfoData['memberID'] = $this->getLastInsertValue();
            $this->sm->get('Api\Model\MemberInfo')->insert($memberInfoData);
            $this->commit();
        }catch (\Exception $e){
            $this->rollback();
        }

    }

    public function getMemberList($where = null, $order = null){
        $select = $this->getSelect();
        $select->from(array('a' => $this->table))
                ->where($where);
        if(!empty($order)) $select->order($order);

        return $this->selectWith($select)->toArray();
    }

    public function genPassword($pwd = null){
        return md5($pwd);
    }

    public function genPwd($pwd = null){
        return md5($pwd);
    }

}