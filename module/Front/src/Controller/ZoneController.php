<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Front;
use Zend\Authentication\Storage\Session;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;

class ZoneController extends Front{

    private $_isMyZone = 0;
    private $_zoneInfo;
    public function init(){
        $zoneID = $this->queryData['zoneID'] ? $this->queryData['zoneID'] : $this->postData['zoneID'];

        if(!empty($zoneID)){
            $this->_zoneInfo = $this->memberInfoModel->select(array('memberID' => $zoneID))->current();
        }
        if(empty($this->_zoneInfo)){
            throw new \Exception('不存在的空间ID', 0);
        }

        if(!empty($this->memberInfo) && $this->memberInfo['memberID'] == $zoneID) {
            $this->_isMyZone = 1;
        }

        $this->view->setVariables(array(
            '_zoneInfo' => $this->_zoneInfo,
            '_isMyZone' => $this->_isMyZone,
        ));
    }

    public function aboutAction(){
        return $this->view;
    }

    public function indexAction(){
        $res = $this->memberArticleModel->getArticles(array('MemberArticle.memberID' => $this->_zoneInfo['memberID']), $this->pageNum, $this->limit);
        $articles = $res['data'];
        foreach($articles as $k => $v){
            $articles[$k]['memberArticleContent'] = Utility::mbCutStr(strip_tags($v['memberArticleContent']), 100);
        }
        $this->view->setVariables(array(
            'articles' => $articles,
            'pages' => $res['pages'],
        ));
        return $this->view;
    }

    public function markAction(){
        $res = $this->memberArticleMarkModel->getArticles(array('MemberArticleMark.memberID' => $this->_zoneInfo['memberID']), $this->pageNum, $this->limit);
        $articles = $res['data'];
        foreach($articles as $k => $v){
            $articles[$k]['memberArticleContent'] = Utility::mbCutStr(strip_tags($v['memberArticleContent']), 100);
        }
        $this->view->setVariables(array(
            'articles' => $articles,
            'pages' => $res['pages'],
        ));
        return $this->view;
    }



    public function articleDetailAction(){
        $memberArticleID = $this->queryData['memberArticleID'];
        $info = $this->memberArticleModel->select(array('memberArticleID' => $memberArticleID))->current();
        $this->memberArticleModel->update(array('readCount' => new Expression('readCount + 1')), array('memberArticleID' => $memberArticleID));
        $this->view->setVariables(array(
            'info' => $info
        ));
        return $this->view;
    }

    public function commentsAction(){
        $memberArticleID = $this->queryData['memberArticleID'];
        $where = array('memberArticleID' => $memberArticleID);
        $res = $this->memberArticleCommentModel->getComments($where, $this->pageNum, $this->limit);
        $comments = $res['data'];

        $this->view->setVariables(array(
            'comments' => $comments,
            'pages' => $res['pages'],
        ));

        return $this->view;
    }

    public function messageAction(){
        $where = array('memberID' => $this->_zoneInfo['memberID']);
        $res = $this->memberMessageModel->getMessages($where, $this->pageNum, $this->limit);
        $messages = $res['data'];

        $this->view->setVariables(array(
            'data' => $messages,
            'pages' => $res['pages'],
        ));
        return $this->view;
    }

    public function interestedZonesAction(){
        $where = array('MemberInterest.memberID' => $this->_zoneInfo['memberID']);
        $res = $this->memberInterestModel->getInterestMembers($where, $this->pageNum, $this->limit);
        $messages = $res['data'];

        $this->view->setVariables(array(
            'data' => $messages,
            'pages' => $res['pages'],
        ));
        return $this->view;
    }






    public function addFavoriteAction(){
        $memberArticleID = $this->postData['memberArticleID'];
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        try{
            $where = array('memberID' => $this->memberInfo['memberID'], 'memberArticleID' => $memberArticleID);


            $existLog = $this->memberArticleFavoriteLogModel->select($where)->current();
            if(empty($existLog)){
                $this->memberArticleFavoriteLogModel->insert($where);
                $this->memberArticleModel->update(array('favoriteCount' => new Expression('favoriteCount+1')), array('memberArticleID' => $memberArticleID));

                $memberArticleInfo = $this->memberArticleModel->select(array('memberArticleID' => $memberArticleID))->current();
                $this->notificationModel->insert(array('type' => 5, 'memberID' => $memberArticleInfo['memberID'], 'content' => '您的文章<<' . $memberArticleInfo['memberArticleName'] . '>>被' . $this->memberInfo['nickName'] . '喜欢。'));
                return $this->response(ApiSuccess::COMMON_SUCCESS, '点赞成功');
            }else{
                return $this->response(ApiError::COMMON_ERROR, '已点赞');
            }


        }catch (\Exception $e){
            return $this->response(ApiError::COMMON_ERROR, '点赞失败');
        }
    }

    public function addMarkAction(){
        $memberArticleID = $this->postData['memberArticleID'];
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        try{
            $where = array('memberID' => $this->memberInfo['memberID'], 'memberArticleID' => $memberArticleID);
            $existMark = $this->memberArticleMarkModel->select($where)->current();
            if(empty($existMark)){
                $this->memberArticleMarkModel->insert($where);
                $this->memberArticleModel->update(array('markCount' => new Expression('markCount+1')), $where);

                $memberArticleInfo = $this->memberArticleModel->select(array('memberArticleID' => $memberArticleID))->current();
                $this->notificationModel->insert(array('type' => 5, 'memberID' => $memberArticleInfo['memberID'], 'content' => '您的文章<<' . $memberArticleInfo['memberArticleName'] . '>>被' . $this->memberInfo['nickName'] . '收藏。'));
                return $this->response(ApiSuccess::COMMON_SUCCESS, '收藏成功');
            }else{
                return $this->response(ApiError::COMMON_ERROR, '收藏失败');
            }
        }catch (\Exception $e){
            return $this->response(ApiError::COMMON_ERROR, '收藏失败');
        }
    }

    public function addArticleAction(){

        try{
            if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
            if(empty($this->postData)) {
                $memberArticleID = $this->queryData['memberArticleID'];
                if(!empty($memberArticleID)){
                    $info = $this->memberArticleModel->select(array('memberArticleID' => $memberArticleID))->current();
                    $this->view->setVariables(array('info' => $info));
                }
                return $this->view;
            }else{
                if(!empty($this->postData['memberArticleID'])){
                    $memberArticleID = $this->postData['memberArticleID'];
                    unset($this->postData['memberArticleID'], $this->postData['zoneID']);
                    $this->memberArticleModel->update($this->postData, array('memberArticleID' => $memberArticleID));
                }else{
                    $this->postData['memberID'] = $this->memberInfo['memberID'];
                    unset($this->postData['zoneID']);
                    $this->memberArticleModel->insert($this->postData);
                    $memberArticleID = $this->memberArticleModel->getLastInsertValue();
                }
                return $this->response(ApiSuccess::COMMON_SUCCESS, '添加成功', array('memberArticleID' => $memberArticleID));
            }

        }catch (\Exception $e){
            return $this->response(ApiError::COMMON_ERROR, '添加失败');
        }
    }

    public function addCommentAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        try{
            unset($this->postData['zoneID']);
            $this->postData['senderID'] = $this->memberInfo['memberID'];
            $this->memberArticleCommentModel->insert($this->postData);
            $this->memberArticleModel->update(array('commentCount' => new Expression('commentCount+1')), array('memberArticleID' => $this->postData['memberArticleID']));
            return $this->response(ApiSuccess::COMMON_SUCCESS, '添加成功');
        }catch (\Exception $e){
            return $this->response(ApiError::COMMON_ERROR, '添加失败');
        }
    }

    public function addMessageAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        try{
            $this->postData['senderID'] = $this->memberInfo['memberID'];
            $this->postData['memberID'] = $this->_zoneInfo['memberID'];
            unset($this->postData['zoneID']);
            $this->memberMessageModel->insert($this->postData);
            $this->memberInfoModel->update(array('messageCount' => new Expression('messageCount+1')), array('memberID' => $this->postData['memberID']));
            return $this->response(ApiSuccess::COMMON_SUCCESS, '添加成功');
        }catch (\Exception $e){
            return $this->response(ApiError::COMMON_ERROR, '添加失败');
        }
    }

    public function delArticleAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        $this->memberArticleModel->update(array('isDel' => 1), array('memberArticleID' => $this->postData['memberArticleID']));

        return $this->response(ApiSuccess::COMMON_SUCCESS, '删除成功');
    }


}