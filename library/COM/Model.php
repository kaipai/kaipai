<?php
namespace COM;

use Base\Functions\Utility;
use Zend\Code\Scanner\Util;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Metadata\Metadata;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\EventFeature;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Filter\Word\UnderscoreToDash;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class Model extends AbstractTableGateway implements AdapterAwareInterface, ServiceManagerAwareInterface
{

    protected $sm = null;
    protected $_primary;
    protected $selectColumns = null;

    public function __construct()
    {
        $table = trim(strrchr(get_class($this), '\\'), '\\');
        $this->table = $table;
        if (!$this->getTable()) {
            throw new ServiceNotCreatedException('table property can not empty');
        }
    }

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->featureSet = new FeatureSet();
        $featureEvent = new EventFeature();
        $this->featureSet->addFeature($featureEvent);
        $this->initialize();
    }

    /**
     * 开启事务
     */
    public function beginTransaction()
    {
        $adapter = $this->getAdapter();
        $adapter->getDriver()->getConnection()->beginTransaction();
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $adapter = $this->getAdapter();
        $adapter->getDriver()->getConnection()->commit();
    }

    /**
     * 回滚事务
     */
    public function rollback()
    {
        $adapter = $this->getAdapter();
        $adapter->getDriver()->getConnection()->rollback();
    }

    /**
     * 转换为一个数组
     *
     * @param Result $result
     * @return array
     */
    public function toArray(Result $result)
    {
        $data = array();
        if ($result->valid()) {
            if (!$result->current()) {
                return $data;
            }
        }
        while ($result->valid()) {
            $data[] = $result->current();
            $result->next();
        }
        return $data;
    }

    /**
     *
     * @param $primaryValue
     * @return array|\ArrayObject|bool|null
     */
    public function get($primaryValue)
    {
        $_primary = $this->getPrimary();
        if (empty($_primary))
            throw new \Zend\Db\Exception\InvalidArgumentException("primary is empty");

        $rst = $this->select(array($this->_primary => $primaryValue));
        $row = $rst->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    /**
     * 获得数量查询
     * @param Where|\Closure|string|array $where
     * @return int
     */
    public function getCount($where = null)
    {
        $select = new Select();
        $select->columns(array('count' => new Expression('count(*)')))->from($this->getTable());
        (empty($where)) ?: $select->where($where);
        return $this->selectWith($select)->current()['count'];
    }

    protected function getPrimary()
    {
        return $this->_primary;
    }

    /**
     * check data exist
     *
     * @param Where|\Closure|string|array $where
     * @return int
     */
    public function checkExist($where)
    {
        return $this->select($where)->count();
    }

    /**
     * 根据表名获得字段
     * @return array|bool
     */
    public function getColumns($conPri = false)
    {
        if (empty($this->table))
            return false;
        $metadata = new Metadata($this->getAdapter());
        $table = $metadata->getTable($this->table);
        $columns = $table->getColumns();
        $cols = array();
        if (in_array($this->table, array('MemberInfo'))) $conPri = true;
        if (!empty($columns)) {
            foreach ($columns as $c) {
                $cols[$c->getName()]['ableNull'] = $c->getIsNullable();
                $cols[$c->getName()]['default'] = $c->getColumnDefault();
                $cols[$c->getName()]['comment'] = $c->getColumnComment();
            }
            if (!$conPri) {
                unset($cols[$this->_primary]);
            }
        }
        return $cols;
    }

    /**
     * 根据columns主要信息
     */
    function getSelectInfo($where = array(), $order = array(), $limit = '', $offset = '')
    {
        $select = new Select();
        $select->from($this->getTable())->columns($this->selectColumns);
        empty($where) ?: $select->where($where);
        empty($limit) ?: $select->limit((int)$limit);
        empty($offset) ?: $select->offset((int)$offset);
        empty($order) ? $select->order(array($this->_primary => 'desc')) : $select->order($order);
        return $this->selectWith($select)->toArray();
    }


    /**
     * getSelectInfo()查询条件
     * @param array $columns
     * @return $this
     */
    function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->sm = $serviceManager;
    }

    /**
     * 获取select实例
     */
    public function getSelect()
    {
        return $this->getSql()->select();
    }

    /**
     * 分页数据集
     * @param Select $select The select query
     * @param Adapter|Sql $adapterOrSqlObject DB adapter or Sql object
     */
    public function paginate(Select $select)
    {

        $dbSelect = new DbSelect($select, $this->getSql());
        $paginator = new Paginator($dbSelect);

        return $paginator;
    }

    /**
     * 获取单条数据
     *
     * @overwrite
     */
    public function fetch($where = array()){
        return $this->select($where)->current();

    }

    /**
     * 获取数据列表
     *
     * @overwrite
     */
    public function getList($where = array(), $order = null, $offset = null, $limit = null)
    {
        $select = $this->getSelect();
        $select->where($where);
        if($offset) $select->offset(intval($offset));
        if($limit) $select->limit(intval($limit));
        if ($order) $select->order($order);

        return $this->selectWith($select)->toArray();
    }

}
