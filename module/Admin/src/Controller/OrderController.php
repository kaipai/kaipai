<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class OrderController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $result = $this->memberOrderModel->getList($where, "orderID desc", $offset, $limit);
        $data['total'] = $result['memberOrdersCount'];

        $data['rows'] = $result['memberOrders'];

        return $this->adminResponse($data);
    }

    public function customizationAction(){

        $deliveryTypes = $this->deliveryTypeModel->select()->toArray();
        $this->view->setVariables(array(
            'deliveryTypes' => $deliveryTypes
        ));
        return $this->view;
    }

    public function customizationListAction(){
        $where = array(
            'MemberOrder.orderType' => 2,
        );
        $orders = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);
        foreach($orders['data'] as $k => $v){
            $tmp = json_decode($v['customizationSnapshot'], true);
            $orders['data'][$k]['title'] = $tmp['title'];
            $orders['data'][$k]['price'] = $tmp['price'];
            $orders['data'][$k]['depositPrice'] = $tmp['depositPrice'];
            $orders['data'][$k]['listImg'] = $tmp['listImg'];
        }
        return $this->adminResponse(array('rows' => $orders['data'], 'total' => $orders['total']));
    }

    public function setDeliveryAction(){
        $orderID = $this->queryData['orderID'];
        $haveDelivery = $this->queryData['haveDelivery'];
        $deliveryTypeID = $this->queryData['deliveryTypeID'];
        $deliveryNum = $this->queryData['deliveryNum'];

        if(!empty($haveDelivery) && empty($deliveryNum)) return $this->response(ApiError::COMMON_ERROR, '请填写运单号码');

        try{
            $this->memberOrderDeliveryModel->beginTransaction();
            $this->memberOrderDeliveryModel->update(array('deliveryTypeID' => $deliveryTypeID, 'deliveryNum' => $deliveryNum), array('orderID' => $orderID));
            $this->memberOrderModel->update(array('orderStatus' => 4), array('orderID' => $orderID));
            $this->memberOrderDeliveryModel->commit();
            return $this->response(AdminSuccess::COMMON_SUCCESS, '发货成功');
        }catch (\Exception $e){

            $this->memberOrderDeliveryModel->rollback();
            return $this->response(AdminError::COMMON_ERROR, '发货失败');
        }
    }
}