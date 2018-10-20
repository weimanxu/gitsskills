<?php
/**
 * APP自定义Class基类
 *
 */
class CustomBaseClass extends BaseClass {
    
    
    /**
     * 上下拉获取数据
     *
     * @param string $table
     * @param string $type
     * @param int    $id
     * @param string $where
     * @param array  $field
     * @param int    $limit
     * @param string $sort
     * @return array
     * @author Ymj Created at 2016年7月28日
     */
    public function listData ($table, $type, $id, $where, $field = [], $limit = 20, $sort = 'DESC') {
        if ($type != 'up' && $type != 'down') {
            return [];
        }
        
        if ($sort != 'DESC' && $sort != 'ASC') {
            return [];
        }
        
        if ($id != '' && !is_numeric($id)) {
            return [];
        }
        
        $whereList = explode('AND', $where);
        foreach ($whereList as &$item){
            $item = trim($item);
        }
        if ($id != ''){
            if ($type == 'up') {
                $whereList[] = '`id` < "' . $id . '"';
            }else{
                $whereList[] = '`id` > "' . $id . '"';
            }
        }
        
        foreach ($whereList as &$item){
            $item = ' ' . $item . ' ';
        }
        $sql = implode('AND', $whereList);
        if ($sql != '') {
            $sql = 'WHERE' . $sql . 'ORDER BY `id` ' . $sort . ' LIMIT ' . $limit;
        }else{
            $sql = 'ORDER BY `id` ' . $sort . ' LIMIT ' . $limit;
        }
        
        $mysql = Mysql::_getInstance($table)->sqlHead($field)->sql($sql, true);
        return $mysql->query()->getResult();
    }
    
    /**
     * 根据id获取信息
     * 
     * @param string $table
     * @param int $id
     * @param array $field
     * @return 
     * @author Ymj Created at 2017年6月27日
     */
    public function getInfoById($table, $id, $field = []){
        if (!is_numeric($id)){
            return null;
        }
        
        $info = Mysql::_getInstance($table)
              ->sqlHead($field)
              ->sql('WHERE `id` = "' . $id . '"', true)
              ->queryOne()
              ->getResult();
        
        return $info;
    }
    
    /**
     * 根据id获取信息（state = 1）
     *
     * @param string $table
     * @param int $id
     * @param array $field
     * @return
     * @author Ymj Created at 2017年6月27日
     */
    public function getInfoByIdWithState($table, $id, $field = []){
        if (!is_numeric($id)){
            return null;
        }
    
        $info = Mysql::_getInstance($table)
              ->sqlHead($field)
              ->sql('WHERE `id` = "' . $id . '" AND `state` = 1', true)
              ->queryOne()
              ->getResult();
    
        return $info;
    }

}