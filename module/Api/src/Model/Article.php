<?php
namespace Api\Model;

use Base\ConstDir\Regexp;
use Base\Functions\Utility;
use COM\Model;
use Zend\Validator\Regex;

class Article extends Model{

    public function getArticles($page, $limit, $where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'ArticleCategory'), 'Article.articleCategoryID = b.articleCategoryID', array('categoryName'));
        $select->where(array('isDel' => 0));
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $data,
            'total' => $total
        );

        return $res;
    }

    public function getIndexRecommendArticles(){
        $select = $this->getSql()->select();
        $select->where(array('isIndexRecommend' => 1));
        $select->limit(2);
        $result = $this->selectWith($select)->toArray();
        foreach($result as $k => $v){
            preg_match_all(Regexp::BODY_CONTENT, $v['articleContent'], $tmp);
            $articleContent = strip_tags($tmp[0][0]);
            $result[$k]['articleContent'] = Utility::mbCutStr($articleContent, 30);
        }
        return $result;
    }

    public function getIndexArticleList(){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'ArticleCategory'), 'Article.articleCategoryID = b.articleCategoryID', array('categoryName'));
        $select->where(array('isIndexArticleList' => 1));
        $select->limit(5);

        return $this->selectWith($select)->toArray();
    }

    public function getInfo($where){
        $select = $this->getSelect();
        $select->join(array('b' => 'ArticleCategory'), 'Article.articleCategoryID = b.articleCategoryID', array('categoryName'));
        $select->where($where);
        $res = $this->selectWith($select)->current();
        return $res;
    }

}