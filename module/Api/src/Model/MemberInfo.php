<?php
namespace Api\Model;

use COM\Model;
class MemberInfo extends Model{

    public function isAvailable($memberInfo){
        $available = true;

        if(!empty($memberInfo['storeID'])){
            $storeInfo = $this->storeModel->select(array('storeID' => $memberInfo['storeID']))->current();
            if($storeInfo['storeCloseType'] == 1){
                if(strtotime('-1 month') < $storeInfo['storeCloseTime']){
                    $available = false;
                }else{
                    $this->storeModel->update(array('storeCloseType' => 0), array('storeID' => $memberInfo['storeID']));
                }

            }
            if($storeInfo['storeCloseType'] == 2){
                if(strtotime('-3 month') < $storeInfo['storeCloseTime']){
                    $available = false;
                }else{
                    $this->storeModel->update(array('storeCloseType' => 0), array('storeID' => $memberInfo['storeID']));
                }
            }
            if($storeInfo['storeCloseType'] == 3){
                $available = false;
            }
        }

        if($memberInfo['closeType'] == 1){
            if(strtotime('-1 month') < $memberInfo['closeTime']){
                $available = false;
            }else{
                $this->memberInfoModel->update(array('closeType' => 0), array('memberID' => $memberInfo['memberID']));
            }

        }
        if($memberInfo['closeType'] == 2){
            if(strtotime('-3 month') < $memberInfo['closeTime']){
                $available = false;
            }else{
                $this->memberInfoModel->update(array('closeType' => 0), array('memberID' => $memberInfo['memberID']));
            }

        }
        if($memberInfo['closeType'] == 3){
            $available = false;
        }

        return $available;
    }

}