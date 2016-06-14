<?php
namespace Admin\Controller;
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


        return $this->view;
    }

    public function customizationListAction(){
        $where = array(
            'MemberOrder.orderType' => 2,
        );
        $orders = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);

        return $this->adminResponse(array('rows' => $orders['data'], 'total' => $orders['total']));
    }
}