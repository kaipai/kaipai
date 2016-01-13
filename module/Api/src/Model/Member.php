<?php
/**
 * 用户登录信息
 */
namespace Api\Model;

use COM\Model;
class Member extends Model{

    /**
     * 验证
     */
    public function auth($mobile = null, $password = null){
        return $this->select(array('Mobile' => $mobile, 'Password' => $this->genPassword($password)))->current();
    }

    /**
     * 新增用户
     */
    public function add($data = array()){
        $memberData = array(
            'Mobile' => $data['Mobile'],
            'Password' => $this->genPassword($data['Password']),
        );
        $memberInfoData = array(
        );
        try{
            $this->beginTransaction();
            $this->insert($memberData);
            $memberInfoData['MemberID'] = $this->getLastInsertValue();
            $this->sm->get('Api\Model\MemberInfo')->insert($memberInfoData);
            $this->commit();
        }catch (\Exception $e){
            $this->rollback();
        }

    }

    /**
     * 用户列表
     */
    public function getList($where = null, $order = null){
        $select = $this->getSelect();
        $select->from(array('a' => $this->table))
                ->where($where);
        if(!empty($order)) $select->order($order);

        return $this->selectWith($select)->toArray();
    }

    /**
     * 生成密码
     */
    public function genPassword($pwd = null){
        return md5($pwd);
    }

}