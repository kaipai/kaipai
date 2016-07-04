<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Front;
use Zend\Authentication\Storage\Session;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Between;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Where;

class MemberController extends Front{

    private $_storeInfo;

    public function init(){

        if(empty($this->memberInfo)) {
            if($this->request->isXmlHttpRequest()){
                return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
            }else{
                return $this->redirect()->toUrl('/login/do-login');
            }

        }
        if(!empty($this->memberInfo['storeID'])){
            $this->_storeInfo = $this->storeModel->select(array('storeID' => $this->memberInfo['storeID']))->current();
            $this->layout()->setVariable('_storeInfo', $this->_storeInfo);
            $this->view->setVariables(array(
                '_storeInfo' => $this->_storeInfo
            ));
        }
        if((empty($this->_storeInfo) || $this->_storeInfo['verifyStatus'] != 2) && in_array($this->actionName, array('add-product', 'product', 'special', 'store-order', 'setting', 'my-store'))){
            return $this->redirect()->toUrl('/member/store-join');
        }
    }

    public function notificationAction(){
        $type = $this->queryData['type'];
        $where = array(
            'Notification.memberID' => $this->memberInfo['memberID'],
        );
        if(!empty($type)) $where['Notification.type'] = $type;
        if($type == 3){
            $where[] = new Between('Notification.instime', date('Y-m-d H:i:s', strtotime('-3 days')), date('Y-m-d H:i:s'));
        }
        if($type == 4){
            $where[] = new Between('Notification.instime', date('Y-m-d H:i:s', strtotime('-3 months')), date('Y-m-d H:i:s'));
        }
        if($type == 1){
            $where = new Where();
            $where->and->equalTo('Notification.type', $type);
            $where->and->nest()->or->equalTo('Notification.memberID', $this->memberInfo['memberID'])->or->isNull('Notification.memberID');

        }


        $res = $this->notificationModel->getNotifications($where, $this->pageNum, $this->limit);
        $notifications = $res['data'];
        //$total = $this->notificationModel->getTotalNotifications(array('memberID' => $this->memberInfo['memberID']));
        $this->notificationModel->update(array('read' => 1), array('memberID' => $this->memberInfo['memberID']));
        $this->view->setVariables(array(
            'notifications' => $notifications,
            'pages' => $res['pages'],
            'type' => $type,
            //'total' => $total,
            'typeTotal' => $res['total'],
        ));
        return $this->view;
    }

    public function orderAction(){

        $search = $this->queryData['search'];
        $orderStatus = $this->queryData['orderStatus'];
        $where = new Where();
        $where->and->equalTo('MemberOrder.memberID', $this->memberInfo['memberID']);
        $where->and->notEqualTo('MemberOrder.orderStatus', -1);
        if(!empty($orderStatus)){
            $where->and->equalTo('MemberOrder.orderStatus', $orderStatus);
        }
        if(!empty($search)){
            $where->and->nest()->or->like('d.productName', '%' . $search . '%')->or->like('MemberOrder.orderID', '%' . $search . '%')->or->like('e.storeName', '%' . $search . '%');
        }



        $result = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);
        $orders = $result['data'];
        foreach($orders as $k => $v){
            if(!empty($v['productSnapshot'])){
                $tmp = json_decode($v['productSnapshot'], true);
                $orders[$k]['product'] = $tmp;
            }elseif(!empty($v['customizationSnapshot'])){
                $tmp = json_decode($v['customizationSnapshot'], true);

                $orders[$k]['product'] = array(
                    'productName' => $tmp['title'],
                    'currPrice' => $tmp['price'],
                    'depositPrice' => $tmp['depositPrice'],
                    'listImg' => $tmp['listImg'],
                );
            }
        }

        $this->view->setVariables(array(
            'orders' => $orders,
            'pages' => $result['pages'],
            'orderStatus' => $orderStatus,
            'search' => $search,
        ));
        return $this->view;
    }

    public function orderDetailAction($storeView = 0){
        $orderID = $this->queryData['orderID'];
        $where = array(
            'MemberOrder.orderID' => $orderID,

        );
        if($storeView){
            $where['MemberOrder.storeID'] = $this->memberInfo['storeID'];
        }else{
            $where['MemberOrder.memberID'] = $this->memberInfo['memberID'];
        }
        $orderInfo = $this->memberOrderModel->getOrderDetail($where);

        if(empty($orderInfo)){
            $this->view->setTemplate('error/404');
        }else{
            if($orderInfo['orderType'] == 2){
                $tmp = json_decode($orderInfo['customizationSnapshot'], true);

                $orderInfo['product'] = array(
                    'productName' => $tmp['title'],
                    'currPrice' => $tmp['price'],
                    'depositPrice' => $tmp['depositPrice'],
                    'listImg' => $tmp['listImg'],
                );
            }else{
                $tmp = json_decode($orderInfo['productSnapshot'], true);
                $orderInfo['product'] = $tmp;
            }
            $this->view->setVariables(array(
                'info' => $orderInfo,
            ));
        }

        return $this->view;
    }

    public function auctionAction(){
        $auctions = $this->auctionMemberModel->getAuctionList(array('AuctionMember.memberID' => $this->memberInfo['memberID'], 'b.auctionStatus' => 2, 'b.isDel' => 0));

        if($this->request->isXmlHttpRequest()){
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $auctions);
        }else{
            $this->view->setVariables(array(

                'auctions' => $auctions
            ));
            return $this->view;
        }

    }

    public function profileAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID'],
        );
        $memberInfo = $this->memberInfoModel->fetch($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberInfo);
    }

    public function interestProductAction(){
        $search = $this->queryData['search'];
        $auctionStatus = $this->queryData['auctionStatus'];
        $where = new Where();
        $where->and->equalTo('MemberProductInterest.memberID', $this->memberInfo['memberID']);
        $where->and->equalTo('b.isDel', 0);
        if(!empty($auctionStatus)){
            $where->and->equalTo('b.auctionStatus', $auctionStatus);
        }
        if(!empty($search)){
            $where->and->nest()->or->like('b.productName', '%' . $search . '%');
        }

        $interestProducts = $this->memberProductInterestModel->getProducts($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'products' => $interestProducts['data'],
            'pages' => $interestProducts['pages'],
            'auctionStatus' => $auctionStatus,
            'search' => $search,
        ));
        return $this->view;
    }

    public function interestStoreAction(){
        $search = $this->queryData['search'];
        $where = new Where();
        $where->and->equalTo('MemberStoreInterest.memberID', $this->memberInfo['memberID']);
        if(!empty($search)){
            $where->and->nest()->or->like('b.storeName', '%' . $search . '%');
        }

        $interestStores = $this->memberStoreInterestModel->getStores($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'stores' => $interestStores['data'],
            'pages' => $interestStores['pages'],
            'search' => $search,
        ));
        return $this->view;
    }

    public function updateAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID']
        );
        $set = $this->postData;
        $purview = array('selfDesc', 'wechat', 'avatar', 'email', 'qq', 'signature');
        foreach($set as $k => $v) {
            if (!in_array($k, $purview)) unset($set[$k]);
        }
        if(empty($set)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $this->memberInfoModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function logOutAction(){
        $session = new Session(self::FRONT_PLATFORM);
        $session->clear();
        setcookie('autoCode', '', time(), '/');
        return $this->redirect()->toUrl('/login/do-login');
    }

    public function addInterestProductAction(){
        $productID = $this->postData['productID'];
        try{
            $this->memberProductInterestModel->beginTransaction();
            $tmp = array(
                'productID' => $productID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberProductInterestModel->insert($tmp);
            $this->productModel->update(array('interestedCount' => new Expression('interestedCount+1')), array('productID' => $productID));
            $specialID = array();
            $specials = $this->productModel->setColumns(array('specialID'))->select(array('productID' => $productID))->toArray();
            foreach($specials as $v){
                $specialID[] = $v['specialID'];
            }
            $this->specialModel->update(array('interestedCount' => new Expression('interestedCount-1')), array('specialID' => $specialID));
            $this->memberProductInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '关注成功');
        }catch (\Exception $e){
            $this->memberProductInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function removeInterestProductAction(){
        $productID = $this->postData['productID'];
        try{
            $this->memberProductInterestModel->beginTransaction();
            $tmp = array(
                'productID' => $productID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberProductInterestModel->delete($tmp);
            $this->productModel->update(array('interestedCount' => new Expression('interestedCount-1')), array('productID' => $productID));
            $specialID = array();
            $specials = $this->productModel->setColumns(array('specialID'))->select(array('productID' => $productID))->toArray();
            foreach($specials as $v){
                $specialID[] = $v['specialID'];
            }
            $this->specialModel->update(array('interestedCount' => new Expression('interestedCount-1')), array('specialID' => $specialID));
            $this->memberProductInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '取消关注成功');
        }catch (\Exception $e){
            $this->memberProductInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function addInterestStoreAction(){
        $storeID = $this->postData['storeID'];
        try{
            $this->memberStoreInterestModel->beginTransaction();
            $tmp = array(
                'storeID' => $storeID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberStoreInterestModel->insert($tmp);
            $this->storeModel->update(array('interestedCount' => new Expression('interestedCount+1')), array('storeID' => $storeID));
            $this->memberStoreInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '关注成功');
        }catch (\Exception $e){
            $this->memberStoreInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function removeInterestStoreAction(){
        $storeID = $this->postData['storeID'];
        try{
            $this->memberStoreInterestModel->beginTransaction();
            $tmp = array(
                'storeID' => $storeID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberStoreInterestModel->delete($tmp);
            $this->storeModel->update(array('interestedCount' => new Expression('interestedCount-1')), array('storeID' => $storeID));
            $this->memberStoreInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '取消关注成功');
        }catch (\Exception $e){
            $this->memberStoreInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function noteAction(){
        return $this->view;
    }

    public function cancelOrderAction(){
        $orderID = $this->postData['orderID'];
        $where = array('orderID' => $orderID, 'memberID' => $this->memberInfo['memberID']);
        $orderInfo = $this->memberOrderModel->select($where)->current();
        if(empty($orderID)) return $this->response(ApiError::COMMON_ERROR, '订单信息不存在');
        if($orderInfo['orderStatus'] > 1 && $orderInfo['orderStatus'] != 5) return $this->response(ApiError::COMMON_ERROR, '订单已付款不能取消');

        $this->memberOrderModel->update(array('orderStatus' => -1), $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '取消成功');

    }

    public function confirmDeliveryDoneAction(){
        $where = array(
            'MemberOrder.memberID' => $this->memberInfo['memberID'],
            'MemberOrder.orderID' => $this->postData['orderID'],
            'MemberOrder.orderStatus' => 4,
        );
        $orderInfo = $this->memberOrderModel->fetch($where);
        if(empty($orderInfo)) return $this->response(ApiError::COMMON_ERROR, '订单信息不存在');

        try{
            $this->memberOrderModel->beginTransaction();
            $this->memberOrderModel->update(array('orderStatus' => 5), $where);
            if(!empty($orderInfo['storeID'])){
                $storeInfo = $this->storeModel->fetch(array('storeID' => $orderInfo['storeID']));
                if(!empty($storeInfo['fees'])){
                    $siteFees = $orderInfo['productPrice'] * $storeInfo['fees'];
                    $this->memberPayDetailModel->update(array('siteFees' => $siteFees), array('unitePayID' => $orderInfo['unitePayID']));
                    $orderInfo['paidMoney'] -= $siteFees;

                }
                $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney + ' . $orderInfo['paidMoney'])), array('storeID' => $orderInfo['storeID']));
                $this->memberRechargeMoneyLogModel->insert(array('memberID' => $storeInfo['memberID'], 'money' => $orderInfo['paidMoney'], 'unitePayID' => $orderInfo['unitePayID'], 'source' => '订单确认收货打款'));
                if(!empty($siteFees)){
                    $this->memberRechargeMoneyLogModel->insert(array('memberID' => $storeInfo['memberID'], 'money' => $siteFees, 'unitePayID' => $orderInfo['unitePayID'], 'source' => '网站佣金', 'type' => 2));
                }
            }

            $this->memberOrderModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '确认成功');
        }catch (\Exception $e){
            $this->memberOrderModel->rollback();
            return $this->response(ApiError::COMMON_ERROR, '确认失败');
        }


    }

    public function memberDeliveryListAction(){
        $memberDeliveries = $this->memberDeliveryModel->select(array('memberID' => $this->memberInfo['memberID']))->toArray();
        $this->view->setVariables(array(
            'memberDeliveries' => $memberDeliveries
        ));
        return $this->view;
    }

    public function addMemberDeliveryAction(){
        $receiverName = $this->postData['receiverName'];
        $receiverMobile = $this->postData['receiverMobile'];
        $receiverAddr = $this->postData['receiverAddr'];
        $memberDeliveryID = $this->postData['memberDeliveryID'];
        if(empty($receiverName) || empty($receiverMobile) || empty($receiverAddr)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $data = array(
            'memberID' => $this->memberInfo['memberID'],
            'receiverName' => $receiverName,
            'receiverMobile' => $receiverMobile,
            'receiverAddr' => $receiverAddr,
        );
        if(!empty($memberDeliveryID)){
            $this->memberDeliverModel->update($data, array('memberDeliveryID' => $memberDeliveryID));
            $data['memberDeliveryID'] = $memberDeliveryID;
            return $this->response(ApiSuccess::COMMON_SUCCESS, '更新成功', $data);
        }else{
            $this->memberDeliveryModel->insert($data);
            $memberDeliveryID = $this->memberDeliveryModel->getLastInsertValue();
            $data['memberDeliveryID'] = $memberDeliveryID;
            return $this->response(ApiSuccess::COMMON_SUCCESS, '添加成功', $data);
        }
    }

    public function delMemberDeliveryAction(){
        $memberDeliveryID = $this->postData['memberDeliveryID'];
        $this->memberDeliveryModel->delete(array('memberDeliveryID' => $memberDeliveryID, 'memberID' => $this->memberInfo['memberID']));

        return $this->response(ApiSuccess::COMMON_SUCCESS, '删除成功');
    }

    public function settingsAction(){
        if(empty($this->postData)){
            $memberInfo = $this->memberInfoModel->select(array('memberID' => $this->memberInfo['memberID']))->current();
            $this->view->setVariables(array('_memberInfo' => $memberInfo));
            return $this->view;
        }

        try{
            if(!empty($this->postData['avatar'])){
                $this->postData['avatar'] = Utility::saveBaseCodePic($this->postData['avatar']);
            }
            if(!empty($this->postData['qq'])){
                $this->storeModel->update(array('storeqq' => $this->postData['qq']), array('storeID' => $this->memberInfo['storeID']));
            }

            $this->memberInfoModel->update($this->postData, array('memberID' => $this->memberInfo['memberID']));
            return $this->response(ApiSuccess::COMMON_SUCCESS, '保存成功');
        }catch (\Exception $e){
            return $this->response(ApiError::COMMON_ERROR, '保存失败');
        }
    }

    public function updateMobileAction(){
        $mobile = $this->postData['mobile'];
        $verifyCode = $this->postData['verifyCode'];

        if(empty($mobile) || empty($verifyCode)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);
        $smsVeriyfCode = $this->mobileVerifyCodeModel->getLastVerifyCode($mobile);
        if($verifyCode == $smsVeriyfCode){
            try{
                $this->memberModel->beginTransaction();
                $this->memberModel->update(array('mobile' => $mobile), array('memberID' => $this->memberInfo['memberID']));
                $this->memberInfoModel->update(array('mobile' => $mobile), array('memberID' => $this->memberInfo['memberID']));
                $this->memberModel->commit();

                return $this->response(ApiSuccess::COMMON_SUCCESS, '保存成功');
            }catch (\Exception $e){
                $this->memberModel->rollback();
                return $this->response(ApiSuccess::COMMON_SUCCESS, '保存失败');
            }



        }else{
            return $this->response(ApiError::COMMON_ERROR, '手机验证码验证失败');
        }
    }

    public function updatePasswordAction(){
        if(empty($this->postData['oldPwd']) || empty($this->postData['newPwd']) || empty($this->postData['confirmNewPwd'])) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $loginInfo = $this->memberModel->select(array('mobile' => $this->memberInfo['mobile'], 'password' => $this->memberModel->genPassword($this->postData['oldPwd'])))->current();
        if(empty($loginInfo)) return $this->response(ApiError::COMMON_ERROR, '原密码错误');

        if(strlen($this->postData['newPwd']) < 6) return $this->response(ApiError::PASSWORD_LT_SIX_WORDS, ApiError::PASSWORD_LT_SIX_WORDS_MSG);
        if($this->postData['newPwd'] != $this->postData['confirmNewPwd']){
            return $this->response(ApiError::TWICE_PASSWORD_NOT_SIMILAR, ApiError::TWICE_PASSWORD_NOT_SIMILAR_MSG);
        }

        try{
            $this->memberModel->update(array('password' => $this->memberModel->genPassword($this->postData['newPwd'])), array('memberID' => $this->memberInfo['memberID']));

            return $this->response(ApiSuccess::COMMON_SUCCESS, '保存成功');

        }catch (\Exception $e){
            return $this->response(ApiError::COMMON_ERROR, '保存失败');
        }

    }

    public function addInterestZoneAction(){
        $zoneID = $this->postData['zoneID'];
        $where = array('memberID' => $this->memberInfo['memberID'], 'interestedMemberID' => $zoneID);

        $this->notificationModel->insert(array('type' => 5, 'memberID' => $zoneID, 'content' => '您的空间已被' . $this->memberInfo['nickName'] . '关注。'));

        $exist = $this->memberInterestModel->select($where)->current();
        if(empty($exist)){
            $this->memberInterestModel->insert($where);

            return $this->response(ApiSuccess::COMMON_SUCCESS, '关注成功');
        }else{
            return $this->response(ApiSuccess::COMMON_SUCCESS, '已关注');
        }

    }

    public function removeInterestZoneAction(){
        $zones = $this->postData['zones'];
        $where = array('memberID' => $this->memberInfo['memberID'], 'interestedMemberID' => $zones);

        $this->memberInterestModel->delete($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '取关成功');

    }

    public function orderStatusAction(){
        $unitePayID = $this->postData['unitePayID'];
        $type = $this->postData['type'];
        if($type == 1){
            $where = array(
                'orderID' => $unitePayID,
            );
            $order = $this->memberOrderModel->select($where)->current();
            $orderStatus = $order['orderStatus'];
        }elseif($type == 2){
            $where = array(
                'unitePayID' => $unitePayID
            );
            $product = $this->productModel->select($where)->current();

            $orderStatus = $product['isPaid'] ? 2 : 1;

        }elseif($type == 3){
            $where = array(
                'unitePayID' => $unitePayID
            );
            $special = $this->specialModel->select($where)->current();

            $orderStatus = $special['isPaid'] ? 2 : 1;
        }


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('orderStatus' => $orderStatus));
    }



























    public function storeCancelOrderAction(){
        $orderID = $this->postData['orderID'];
        $where = array('orderID' => $orderID, 'storeID' => $this->memberInfo['storeID']);
        $orderInfo = $this->memberOrderModel->select($where)->current();
        if(empty($orderID)) return $this->response(ApiError::COMMON_ERROR, '订单信息不存在');
        if($orderInfo['orderStatus'] > 1 && $orderInfo['orderStatus'] != 5) return $this->response(ApiError::COMMON_ERROR, '订单已付款不能取消');

        $this->memberOrderModel->update(array('orderStatus' => -1), $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '取消成功');
    }

    public function addProductAction(){
        $specialID = $this->queryData['specialID'];
        $productID = $this->queryData['productID'];

        $productInfo = $this->productModel->select(array('productID' => $productID, 'storeID' => $this->_storeInfo['storeID']))->current();
        if(!empty($productInfo)){
            $specialID = $productInfo['specialID'];
            $productCategoryFilterOptions = $this->productFilterOptionModel->select(array('productID' => $productID))->toArray();
            $productPropertyValues = $this->productPropertyValueModel->select(array('productID' => $productID))->toArray();
            $productInfo['detailImgs'] = json_decode($productInfo['detailImgs'], true);
            $deliveryCity = $this->regionModel->select(array('regionID' => $productInfo['deliveryCityID']))->current();
            $productInfo['deliveryProvinceID'] = $deliveryCity['pid'];
            $startTime = $productInfo['startTime'];
            $endTime = $productInfo['endTime'];
            $productInfo['startTimeYear'] = date('Y', $startTime);
            $productInfo['startTimeMonth'] = date('n', $startTime);
            $productInfo['startTimeDay'] = date('j', $startTime);
            $productInfo['startTimeHour'] = date('G', $startTime);
            $productInfo['startTimeMin'] = date('i', $startTime);
            $productInfo['endTimeYear'] = date('Y', $endTime);
            $productInfo['endTimeMonth'] = date('n', $endTime);
            $productInfo['endTimeDay'] = date('j', $endTime);
            $productInfo['endTimeHour'] = date('G', $endTime);
            $productInfo['endTimeMin'] = date('i', $endTime);
        }

        $productCategories = $this->productCategoryModel->select()->toArray();
        $storeCategory = $this->storeCategoryModel->select(array('storeID' => $this->_storeInfo['storeID']))->toArray();
        $specialWhere = array('storeID' => $this->_storeInfo['storeID']);
        if(!empty($specialID)){
            $specialWhere = array('specialID' => $specialID);
        }
        $specials = $this->specialModel->select($specialWhere)->toArray();

        $this->view->setVariables(array(
            'productCategories' => $productCategories,
            'storeCategory' => $storeCategory,
            'specials' => $specials,
            'specialID' => $specialID,
            'productInfo' => $productInfo,
            'productCategoryFilterOptions' => $productCategoryFilterOptions,
            'productPropertyValues' => $productPropertyValues,
        ));
        return $this->view;
    }

    public function addProductCategoryAction(){
        $this->view->setNoLayout();
        $productCategoryID = $this->queryData['productCategoryID'];
        $options = $this->productCategoryFilterOptionModel->getCategoryFilters(array('b.productCategoryID' => $productCategoryID));

        $this->view->setVariables(array(
            'options' => $options,
        ));
        return $this->view;
    }

    public function addSpecialAction(){
        if(empty($this->postData)){
            $specialID = $this->queryData['specialID'];
            $specialInfo = $this->specialModel->select(array('specialID' => $specialID, 'storeID' => $this->_storeInfo['storeID']))->current();
            if(!empty($specialInfo)){
                $startTime = $specialInfo['startTime'];
                $endTime = $specialInfo['endTime'];
                $specialInfo['startTimeYear'] = date('Y', $startTime);
                $specialInfo['startTimeMonth'] = date('n', $startTime);
                $specialInfo['startTimeDay'] = date('j', $startTime);
                $specialInfo['startTimeHour'] = date('G', $startTime);
                $specialInfo['startTimeMin'] = date('i', $startTime);
                $specialInfo['endTimeYear'] = date('Y', $endTime);
                $specialInfo['endTimeMonth'] = date('n', $endTime);
                $specialInfo['endTimeDay'] = date('j', $endTime);
                $specialInfo['endTimeHour'] = date('G', $endTime);
                $specialInfo['endTimeMin'] = date('i', $endTime);
            }
            $productCategories = $this->productCategoryModel->select()->toArray();
            $this->view->setVariables(array(
                'productCategories' => $productCategories,
                'specialInfo' => $specialInfo,

            ));
            return $this->view;
        }else{
            if(isset($this->postData['productCountLimit'])){
                $this->postData['productCountLimit'] = intval($this->postData['productCountLimit']);
                if(!($this->postData['productCountLimit'] >= 20 && $this->postData['productCountLimit'] <= 60)){
                    return $this->response(ApiError::COMMON_ERROR, '拍品数量限制在20-60个');
                }
            }
            if(!empty($this->postData['specialImg'])){
                $this->postData['specialImg'] = Utility::saveBaseCodePic($this->postData['specialImg']);
            }
            if(!empty($this->postData['specialBanner'])){
                $this->postData['specialBanner'] = Utility::saveBaseCodePic($this->postData['specialBanner']);
            }
            if(!empty($this->postData['startTime'])){
                $this->postData['startTime'] = strtotime($this->postData['startTime']);
                if($this->postData['startTime'] < time()) return $this->response(ApiError::COMMON_ERROR, '拍卖开始时间选择错误');
            }
            if(!empty($this->postData['endTime'])){
                $this->postData['endTime'] = strtotime($this->postData['endTime']);
                if($this->postData['endTime'] < time()) return $this->response(ApiError::COMMON_ERROR, '拍卖结束时间选择错误');

                if($this->postData['endTime'] < $this->postData['startTime']) return $this->response(ApiError::COMMON_ERROR, '拍卖结束时间小于开始时间');
            }

            $this->postData['storeID'] = $this->_storeInfo['storeID'];



            if(!empty($this->postData['specialID'])){
                $where = array('specialID' => $this->postData['specialID'], 'storeID' => $this->_storeInfo['storeID']);
                $specialInfo = $this->specialModel->select($where)->current();
                if(empty($specialInfo)) return $this->response(ApiError::COMMON_ERROR, '专场不存在');
                if($specialInfo['verifyStatus'] == 1) return $this->response(ApiError::COMMON_ERROR, '专场已提交审核, 不能编辑');
                if($specialInfo['verifyStatus'] == 2) return $this->response(ApiError::COMMON_ERROR, '专场已通过审核, 不能编辑');

                unset($this->postData['specialID']);
                $this->specialModel->update($this->postData, $where);
                if(!empty($this->postData['startTime']) && !empty($this->postData['endTime'])){
                    $this->productModel->update(array('startTime' => $this->postData['startTime'], 'endTime' => $this->postData['endTime']));
                }
                return $this->response(ApiSuccess::COMMON_SUCCESS, '更新成功');
            }else{

                $this->specialModel->insert($this->postData);



                return $this->response(ApiSuccess::COMMON_SUCCESS, '新增成功');
            }


        }

    }

    public function specialVerifyAction(){
        $specialID = $this->postData['specialID'];
        $specialInfo = $this->specialModel->select(array('specialID' => $specialID))->current();
        //$this->specialModel->update(array('verifyStatus' => 1), array('specialID' => $specialID, 'storeID' => $this->_storeInfo['storeID']));
        $update = array('verifyStatus' => 1);
        if(empty($this->siteSettings['specialMoney']) || !empty($specialInfo['isPaid'])){
            $update['isPaid'] = 1;
            $this->specialModel->update($update, array('specialID' => $specialID, 'storeID' => $this->_storeInfo['storeID']));
            return $this->response(ApiSuccess::COMMON_SUCCESS, '提交成功');

        }else{
            $unitePayID = $this->memberOrderModel->genUnitePayID();
            $this->specialModel->update(array('unitePayID' => $unitePayID), array('specialID' => $specialID));
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('unitePayID' => $unitePayID, 'price' => $this->siteSettings['specialMoney']));
        }
    }

    public function specialAction(){
        $where = array('Special.storeID' => $this->_storeInfo['storeID']);
        $specials = $this->specialModel->getSpecials($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'specials' => $specials['data'],
            'pages' => $specials['pages'],
        ));
        return $this->view;
    }

    public function specialProductAction(){
        $specialID = $this->queryData['specialID'];
        $special = $this->specialModel->select(array('specialID' => $specialID))->current();
        $products = $this->productModel->select(array('specialID' => $specialID, 'isDel' => 0))->toArray();
        $this->view->setVariables(array(
            'special' => $special,
            'products' => $products,
        ));
        return $this->view;
    }

    public function productAction(){
        $auctionStatus = $this->queryData['auctionStatus'];
        $where = array('Product.storeID' => $this->memberInfo['storeID'], 'Product.auctionStatus != ?' => 3);
        if(!empty($auctionStatus)){
            $where['Product.auctionStatus'] = $auctionStatus;
        }
        $res = $this->productModel->getProducts($where, $this->pageNum, $this->limit);
        $products = $res['data'];
        foreach($products as $k => $v){
            $startTime = $v['startTime'];
            $endTime = $v['endTime'];
            if(!empty($startTime)){
                $products[$k]['startTimeYear'] = date('Y', $startTime);
                $products[$k]['startTimeMonth'] = date('n', $startTime);
                $products[$k]['startTimeDay'] = date('j', $startTime);
                $products[$k]['startTimeHour'] = date('G', $startTime);
                $products[$k]['startTimeMin'] = date('i', $startTime);
                $products[$k]['endTimeYear'] = date('Y', $endTime);
                $products[$k]['endTimeMonth'] = date('n', $endTime);
                $products[$k]['endTimeDay'] = date('j', $endTime);
                $products[$k]['endTimeHour'] = date('G', $endTime);
                $products[$k]['endTimeMin'] = date('i', $endTime);
            }
        }

        $this->view->setVariables(array(
            'products' => $products,
            'pages' => $res['pages'],
            'auctionStatus' => $auctionStatus,
        ));
        return $this->view;
    }

    public function storeSettingAction(){
        if(empty($this->postData)){
            $storeCategories = $this->storeCategoryModel->select(array('storeID' => $this->_storeInfo['storeID']))->toArray();
            $this->view->setVariables(array('storeCategories' => $storeCategories));
            return $this->view;
        }

        try{
            $this->storeModel->beginTransaction();
            $storeCategory = $this->postData['storeCategory'];
            unset($this->postData['storeCategory']);
            $storeID = $this->_storeInfo['storeID'];
            $where = array(
                'storeID' => $storeID
            );

            $this->postData['storeLogo'] = Utility::saveBaseCodePic($this->postData['storeLogo']);
            $this->postData['storeBanner'] = Utility::saveBaseCodePic($this->postData['storeBanner']);
            $this->postData['recommendImg'] = Utility::saveBaseCodePic($this->postData['recommendImg']);

            $this->storeModel->update($this->postData, $where);

            //$this->storeCategoryModel->delete(array('storeID' => $storeID));
            foreach($storeCategory as $v){
                $exist = $this->storeCategoryModel->select(array('storeID' => $storeID, 'storeCategoryName' => $v))->current();

                if(!empty($v) && empty($exist)){
                    $tmp = array(
                        'storeID' => $storeID,
                        'storeCategoryName' => $v,
                    );
                    $this->storeCategoryModel->insert($tmp);
                }
            }
            $this->storeModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '保存成功');
        }catch (\Exception $e){
            $this->storeModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }

    }

    public function storeOrderAction(){
        $search = $this->queryData['search'];
        $orderStatus = $this->queryData['orderStatus'];
        $where = new Where();
        $where->and->equalTo('MemberOrder.storeID', $this->memberInfo['storeID']);
        if(!empty($orderStatus)){
            $where->and->equalTo('MemberOrder.orderStatus', $orderStatus);
        }
        if(!empty($search)){
            $where->and->nest()->or->like('d.productName', '%' . $search . '%')->or->like('a.orderID', '%' . $search . '%')->or->like('e.storeName', '%' . $search . '%');
        }
        $result = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);
        $orders = $result['data'];
        foreach($orders as $k => $v){
            if(!empty($v['productSnapshot'])){
                $tmp = json_decode($v['productSnapshot'], true);
                $orders[$k]['product'] = $tmp;
            }elseif(!empty($v['customizationSnapshot'])){
                $tmp = json_decode($v['customizationSnapshot'], true);
                $orders[$k]['product'] = array(
                    'productName' => $tmp['title'],
                    'currPrice' => $tmp['price'],
                    'depositPrice' => $tmp['depositPrice'],
                    'listImg' => $tmp['listImg'],
                );
            }
        }

        $deliveryTypes = $this->deliveryTypeModel->select()->toArray();

        $this->view->setVariables(array(
            'orders' => $orders,
            'pages' => $result['pages'],
            'orderStatus' => $orderStatus,
            'search' => $search,
            'deliveryTypes' => $deliveryTypes
        ));
        return $this->view;
    }

    public function categoryPropertyAction(){
        $categoryID = $this->queryData['productCategoryID'];
        $properties = $this->productCategoryPropertyModel->select(array('productCategoryID' => $categoryID))->toArray();
        $this->view->setVariables(array(
            'properties' => $properties
        ));

        return $this->view;
    }

    public function saveProductAction(){

        $data = $this->postData;
        $data['storeID'] = $this->_storeInfo['storeID'];
        $product = $data;
        unset($product['productCategoryFilters'], $product['productCategoryProperty'], $product['storeCategoryID']);
        if(!empty($data['detailImgs'])){
            $detailImgs = explode(',', trim($data['detailImgs'], ','));
            if(count($detailImgs) > 5) return $this->response(ApiError::COMMON_ERROR, '拍品图片不能超过5张');
            $product['listImg'] = current($detailImgs);
            $product['detailImgs'] = json_encode($detailImgs);
        }

        if(!empty($product['startTime'])){
            $product['startTime'] = strtotime($product['startTime']);
        }
        if(!empty($product['endTime'])){
            $product['endTime'] = strtotime($product['endTime']);
        }

        if(!empty($product['specialID'])){
            $specialInfo = $this->specialModel->select(array('specialID' => $product['specialID']))->current();
            $product['startTime'] = $specialInfo['startTime'];
            $product['endTime'] = $specialInfo['endTime'];
            $product['auctionStatus'] = $specialInfo['auctionStatus'];

            if(empty($product['productID'])){
                $specialInfo = $this->specialModel->select(array('specialID' => $product['specialID']))->current();

                if(($specialInfo['productCount'] + 1) > $specialInfo['productCountLimit']) return $this->response(ApiError::COMMON_ERROR, '超过拍品数量限制');
                if($specialInfo['verifyStatus'] == 1) return $this->response(ApiError::COMMON_ERROR, '专场已提交审核, 不能编辑');
                if($specialInfo['verifyStatus'] == 2) return $this->response(ApiError::COMMON_ERROR, '专场已通过审核, 不能编辑');
            }

        }else{
            if(!empty($product['publish'])){

                if($product['startTime'] < time()) $product['startTime'] = time();
                if($product['endTime'] < time()) $product['endTime'] = strtotime('+1 day');


                if($product['startTime'] < time()) return $this->response(ApiError::COMMON_ERROR, '拍卖开始时间选择错误');
                if($product['endTime'] < time()) return $this->response(ApiError::COMMON_ERROR, '拍卖结束时间选择错误');
                if($product['endTime'] < $product['startTime']) return $this->response(ApiError::COMMON_ERROR, '拍卖结束时间早于开始时间');
                if($product['endTime'] > strtotime('+2 days', $product['startTime'])) return $this->response(ApiError::COMMON_ERROR, '拍卖时间在48小时内');

                $product['auctionStatus'] = 1;
            }elseif(empty($product['productID'])){
                unset($product['startTime'], $product['endTime']);
            }
        }
        unset($product['publish']);




        if(isset($product['startPrice'])){
            $product['currPrice'] = $product['startPrice'];
        }


        if(!empty($data['productID'])){

            $where = array(
                'productID' => $product['productID'],
                'storeID' => $product['storeID']
            );
            $products = $this->productModel->select($where)->toArray();
            foreach($products as $productInfo){
                if(empty($product['specialID']) && !empty($productInfo['specialID']) && !empty($product['startTime']) && !empty($product['endTime'])) return $this->response(ApiError::COMMON_ERROR, '专场拍品跟着专场上架');
                if(!empty($productInfo['specialID'])){
                    $specialInfo = $this->specialModel->select(array('specialID' => $productInfo['specialID']))->current();
                    if($specialInfo['verifyStatus'] == 1) return $this->response(ApiError::COMMON_ERROR, '专场已提交审核, 不能编辑');
                    if($specialInfo['verifyStatus'] == 2) return $this->response(ApiError::COMMON_ERROR, '专场已通过审核, 不能编辑');
                }
                if(empty($productInfo)) return $this->response(ApiError::COMMON_ERROR, '拍品不存在');
                if(!empty($productInfo['auctionStatus']) && $productInfo['auctionStatus'] != 1) return $this->response(ApiError::COMMON_ERROR, '该拍品不能被编辑');
            }
        }

        try{
            $this->productModel->beginTransaction();

            if(!empty($products)){
                unset($product['productID']);
                $this->productModel->update($product, $where);
                if(!empty($data['productCategoryFilters'])){
                    $this->productFilterOptionModel->delete(array('productID' => $where['productID']));
                }

                if(!empty($data['productCategoryProperty'])){
                    $this->productPropertyValueModel->delete(array('productID' => $where['productID']));
                }
                $productID = $where['productID'];
                foreach($products as $productInfo){
                    if(!empty($productInfo['specialID']) && $product['isDel']){
                        $this->specialModel->update(array('productCount' => new Expression('productCount-1')), array('specialID' => $productInfo['specialID']));
                    }
                }

            }else{
                $unitePayID = $this->memberOrderModel->genUnitePayID();
                $product['unitePayID'] = $unitePayID;
                if(empty($this->siteSettings['productMoney'])){
                    $product['isPaid'] = 1;
                }
                $this->productModel->insert($product);
                $productID = $this->productModel->getLastInsertValue();
                if(!empty($product['specialID'])){
                    $this->specialModel->update(array('productCount' => new Expression('productCount+1')), array('specialID' => $product['specialID']));
                }
            }

            if(!empty($data['productCategoryFilters'])){
                $productCategoryFilters = $data['productCategoryFilters'];
                foreach($productCategoryFilters as $v){
                    $tmp = array(
                        'productID' => $productID,
                        'productCategoryFilterOptionID' => $v,
                    );
                    $this->productFilterOptionModel->insert($tmp);
                }
            }

            if(!empty($data['productCategoryProperty'])){
                foreach($data['productCategoryProperty'] as $k => $v){
                    $tmp = array(
                        'productID' => $productID,
                        'productCategoryPropertyID' => $k,
                        'value' => $v,
                    );
                    $this->productPropertyValueModel->insert($tmp);
                }
            }

            $this->productModel->commit();
            if(!empty($products)){
                return $this->response(ApiSuccess::COMMON_SUCCESS, '更新成功', array('productID' => $productID));
            }else{
                return $this->response(ApiSuccess::COMMON_SUCCESS, '新增成功', array('productID' => $productID, 'unitePayID' => $unitePayID));
            }

        }catch (\Exception $e){
            $this->productModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }

    }

    public function saveProductPreviewAction(){

        $data = $this->postData;
        $data['storeID'] = $this->_storeInfo['storeID'];
        $product = $data;
        unset($product['productCategoryFilters'], $product['productCategoryProperty'], $product['storeCategoryID']);
        if(!empty($data['detailImgs'])){
            $detailImgs = explode(',', trim($data['detailImgs'], ','));
            if(count($detailImgs) > 5) return $this->response(ApiError::COMMON_ERROR, '拍品图片不能超过5张');
            $product['listImg'] = current($detailImgs);
            $product['detailImgs'] = json_encode($detailImgs);
        }

        if(!empty($product['startTime'])){
            $product['startTime'] = strtotime($product['startTime']);
        }
        if(!empty($product['endTime'])){
            $product['endTime'] = strtotime($product['endTime']);
        }
        if(!empty($product['startPrice'])){
            $product['currPrice'] = $product['startPrice'];
        }

        try{
            $this->productCopyModel->beginTransaction();
            $this->productCopyModel->insert($product);
            $productID = $this->productCopyModel->getLastInsertValue();
            if(!empty($data['productCategoryProperty'])){
                foreach($data['productCategoryProperty'] as $k => $v){
                    $tmp = array(
                        'productID' => $productID,
                        'productCategoryPropertyID' => $k,
                        'value' => $v,
                    );
                    $this->productPropertyValueCopyModel->insert($tmp);
                }
            }
            $this->productCopyModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('productID' => $productID));
        }catch (\Exception $e){
            $this->productCopyModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }

    }

    public function storeJoinAction(){
        if(empty($this->_storeInfo)){
            $this->view->setTemplate('/front/member/store-join-step1');
        }elseif($this->_storeInfo['verifyStatus'] == 1 || $this->_storeInfo['verifyStatus'] == 3){
            $this->view->setTemplate('/front/member/store-join-step2');
        }elseif($this->_storeInfo['verifyStatus'] == 2){
            $this->view->setTemplate('/front/member/store-join-step3');
        }


        return $this->view;
    }

    public function storeVerifyAction(){
        $store = $this->postData;
        $verifyCode = $this->mobileVerifyCodeModel->getLastVerifyCode($store['storeMobile']);
        if($verifyCode != $store['storeMobileCode']){
            return $this->response(ApiError::VERIFY_CODE_INVALID, ApiError::VERIFY_CODE_INVALID_MSG);
        }
        unset($store['storeMobileCode']);
        $store['idPic'] = Utility::saveBaseCodePic($store['idPic']);
        $store['memberIdPic'] = Utility::saveBaseCodePic($store['memberIdPic']);
        $store['memberID'] = $this->memberInfo['memberID'];
        $store['storeName'] = $this->memberInfo['nickName'];

        try{
            $this->storeModel->beginTransaction();
            $this->storeModel->insert($store);
            $storeID = $this->storeModel->getLastInsertValue();
            $this->memberInfoModel->update(array('storeID' => $storeID), array('memberID' => $this->memberInfo['memberID']));
            $this->storeModel->commit();

            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
        }catch (\Exception $e){
            $this->storeModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function recommendProductAction(){
        $productID = $this->postData['productID'];
        if(!empty($productID)){
            $this->productModel->update(array('isSpecialRecommend' => 1), array('productID' => $productID));
        }
        return $this->response(ApiSuccess::COMMON_SUCCESS, '推荐成功');
    }

    public function storeRecommendProductAction(){
        $productID = $this->postData['productID'];
        if(!empty($productID)){
            $this->productModel->update(array('isStoreRecommend' => $this->postData['isStoreRecommend']), array('productID' => $productID));
        }
        if($this->postData['isStoreRecommend'] == 1){
            return $this->response(ApiSuccess::COMMON_SUCCESS, '推荐成功');
        }else{
            return $this->response(ApiSuccess::COMMON_SUCCESS, '取消推荐成功');
        }

    }

    public function delSpecialAction(){
        $specialID = $this->postData['specialID'];
        if(!empty($specialID)){
            $this->specialModel->update(array('isDel' => 1), array('specialID' => $specialID));
        }
        return $this->response(ApiSuccess::COMMON_SUCCESS, '删除成功');
    }

    public function delImgAction(){
        $productInfo = $this->productModel->select(array('productID' => $this->postData['productID']))->current();
        if(!empty($productInfo)){
            $detailImgs = json_decode($productInfo['detailImgs'], true);
            foreach($detailImgs as $k => $v){
                if($v == $this->postData['path']){
                    unset($detailImgs[$k]);
                }
            }
            $detailImgs = json_encode(array_values($detailImgs));
            $this->productModel->update(array('detailImgs' => $detailImgs), array('productID' => $this->postData['productID']));
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, '删除成功');
    }

    public function publishProductAction(){
        $productID = $this->postData['productID'];
        $where = array('productID' => $productID, 'storeID' => $this->_storeInfo['storeID']);
        $update = array(
            'auctionStatus' => 1
        );

        $products = $this->productModel->select($where)->toArray();

        foreach($products as $productInfo){
            if(!empty($productInfo)){
                $productInfo['startTime'] = time();
                $update['startTime'] = $productInfo['startTime'];

                $productInfo['endTime'] = strtotime('+1 day');
                $update['endTime'] = $productInfo['endTime'];

                if(!empty($productInfo['specialID'])) return $this->response(ApiError::COMMON_ERROR, '专场拍品跟着专场上架');
                if($productInfo['startTime'] < time()) return $this->response(ApiError::COMMON_ERROR, '拍卖开始时间设置错误');
                if($productInfo['endTime'] < time()) return $this->response(ApiError::COMMON_ERROR, '拍卖结束时间设置错误');
                if($productInfo['endTime'] < $productInfo['startTime']) return $this->response(ApiError::COMMON_ERROR, '拍卖结束时间小于开始时间');
                if($productInfo['endTime'] > strtotime('+2 days', $productInfo['startTime'])) return $this->response(ApiError::COMMON_ERROR, '拍卖时间在48小时内');
            }
        }

        $paidWhere = array_merge($where, array('isPaid' => 0));
        $paidProducts = $this->productModel->select($paidWhere)->toArray();
        if(!empty($paidProducts)){
            $price = $this->siteSettings['productMoney'] * count($paidProducts);
            $unitePayID = $this->memberOrderModel->genUnitePayID();
            $this->productModel->update(array('unitePayID' => $unitePayID), $where);
        }else{
            $this->productModel->update($update, $where);
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, '上架成功', array('unitePayID' => $unitePayID, 'price' => $price));
    }

    public function withdrawProductAction(){
        $productID = $this->postData['productID'];
        $where = array('productID' => $productID, 'storeID' => $this->_storeInfo['storeID']);
        $products = $this->productModel->select($where)->toArray();
        foreach($products as $productInfo){
            if(!empty($productInfo['specialID'])) return $this->response(ApiError::COMMON_ERROR, '专场拍品跟着专场下架');
            if($productInfo['auctionStatus'] == 2) return $this->response(ApiError::COMMON_ERROR, '拍卖正在进行中, 无法下架');
        }

        $this->productModel->update(array('auctionStatus' => 0, 'startTime' => new Expression('null'), 'endTime' => new Expression('null')), $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '下架成功');
    }

    public function confirmDeliveryInfoAction(){
        $haveDelivery = $this->postData['haveDelivery'];
        $deliveryType = $this->postData['deliveryType'];
        $deliveryNum = $this->postData['deliveryNum'];
        $orderID = $this->postData['orderID'];
        if($haveDelivery && (empty($deliveryType) || empty($deliveryNum))) return $this->response(ApiError::COMMON_ERROR, '请填写物流信息');
        try{

            $this->memberOrderModel->beginTransaction();
            $this->memberOrderDeliveryModel->update(array('deliveryTypeID' => $deliveryType, 'deliveryNum' => $deliveryNum), array('orderID' => $orderID));
            $this->memberOrderModel->update(array('orderStatus' => 4), array('orderStatus' => 3, 'orderID' => $orderID, 'storeID' => $this->_storeInfo['storeID']));


            $orderInfo = $this->memberOrderModel->select(array('orderID' => $orderID))->current();
            $productSnapshot = json_decode($orderInfo['productSnapshot'], true);
            $productName = $productSnapshot['productName'];
            $this->notificationModel->insert(array('type' => 4, 'memberID' => $orderInfo['memberID'], 'content' => '您购得的拍品<<' . $productName . '>>已发货。'));


            $this->memberOrderModel->commit();

            return $this->response(ApiSuccess::COMMON_SUCCESS, '保存成功');
        }catch (\Exception $e){
            $this->memberOrderModel->rollback();
            return $this->response(ApiError::COMMON_ERROR, '保存失败');
        }
    }

    public function storeOrderDetailAction(){
        $this->view->setVariables(array('storeView' => 1));
        $this->orderDetailAction(1);

        $this->view->setTemplate('/front/member/order-detail');
        return $this->view;
    }

    public function myStoreAction(){

        return $this->redirect()->toUrl('/store/detail?storeID=' . $this->_storeInfo['storeID']);
    }

    /*public function cancelKeepPriceAction(){
        $productID = $this->request->getPost('productID');
        $where = array(
            'productID' => $productID,
            'storeID' => $this->_storeInfo['storeID'],
        );
        $this->productModel->update(array('keepPrice' => 0, 'existKeepPrice' => 0), $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '取消成功');
    }*/

    public function myRechargeAction(){
        $where = array(
            'MemberRechargeMoneyLog.memberID' => $this->memberInfo['memberID'],
        );
        if(!empty($this->postData)){
            $startTimeYear = $this->postData['startTimeYear'];
            $startTimeMonth = $this->postData['startTimeMonth'];
            $startTimeDay = $this->postData['startTimeDay'];
            $endTimeYear = $this->postData['endTimeYear'];
            $endTimeMonth = $this->postData['endTimeMonth'];
            $endTimeDay = $this->postData['endTimeDay'];

            $startTime = $startTimeYear . '-' . $startTimeMonth . '-' . $startTimeDay . ' 00:00:00';
            $endTime = $endTimeYear . '-' . $endTimeMonth . '-' . $endTimeDay . ' 00:00:00';
            $where[] = new Between('MemberRechargeMoneyLog.instime', $startTime, $endTime);
        }

        $res = $this->memberRechargeMoneyLogModel->getLogs($where, $this->pageNum, $this->limit);
        $logs = $res['data'];

        $this->view->setVariables(array(
            'logs' => $logs,
            'pages' => $res['pages'],
        ));
        return $this->view;
    }

    public function bindCardAction(){
        if(!empty($this->postData)){
            $verifyCode = $this->mobileVerifyCodeModel->getLastVerifyCode($this->postData['cardMobile']);
            if($verifyCode != $this->postData['verifyCode']){
                return $this->response(ApiError::VERIFY_CODE_INVALID, ApiError::VERIFY_CODE_INVALID_MSG);
            }
            unset($this->postData['verifyCode']);
            $this->storeModel->update($this->postData, array('storeID' => $this->_storeInfo['storeID']));

            return $this->response(ApiSuccess::COMMON_SUCCESS, '绑定成功');
        }
        return $this->view;
    }

    public function postWithdrawAction(){
        $this->postData['memberID'] = $this->memberInfo['memberID'];
        $this->postData['storeID'] = $this->memberInfo['storeID'];
        $this->postData['money'] = floatval($this->postData['money']);
        if(empty($this->postData['money'])) return $this->response(ApiError::COMMON_ERROR, '请输入金额');
        if($this->postData['money'] > $this->memberInfo['rechargeMoney']) return $this->response(ApiError::COMMON_ERROR, '提现金额大于余额, 提现失败');
        try{
            $this->withdrawLogModel->beginTransaction();
            $this->withdrawLogModel->insert($this->postData);
            $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney - ' . $this->postData['money'])), array('memberID' => $this->memberInfo['memberID']));
            $this->memberRechargeMoneyLogModel->insert(array('memberID' => $this->memberInfo['memberID'], 'money' => $this->postData['money'], 'source' => '提现', 'type' => 2));
            $this->withdrawLogModel->commit();

            return $this->response(ApiSuccess::COMMON_SUCCESS, '提交成功, 待审核');
        }catch (\Exception $e){
            $this->withdrawLogModel->rollback();
            return $this->response(ApiError::COMMON_ERROR, '提现失败');
        }
    }
}