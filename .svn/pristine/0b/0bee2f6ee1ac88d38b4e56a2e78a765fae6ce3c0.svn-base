<?php
class WalletClass extends CustomBaseClass{
    /**
     * 通过用户id、货币类型获取该用户钱包项目列表
     *
     * @param  int $userId
     * @param  String $type
     * @return array
     * @author XJW Create At 2017年6月29日
     */
    public function listWalletProById($userId,$type){
        if(!Validator::isInt($userId)){
            return [];
        }
        
        $type=strtolower($type);
        if(!in_array($type, App::loadConf('app/currency'))){
            return [];
        }
        
        //三表联表查询sql语句
        $sql='SELECT up.*,pj.name,pj.logo,pj.begintime,pj.endtime,ad.address FROM user_project up JOIN
             project pj ON up.project_id=pj.id JOIN address ad ON
             ad.id=up.address_id ';
        $where='up.user_id='.$userId.' AND pj.state=1 AND up.type="'.$type.'" ORDER BY up.amount DESC';
        //创建mysql调用方法执行SQL语句
        $arr_proInfo= Mysql::_getInstance()
        ->sql($sql)
        ->where($where)
        ->query()
        ->getResult();
        return $arr_proInfo;
    }
    
    /**
     * 通过user_project_id查找交易记录
     * 
     * @param  int $userProjectId
     * @return array
     * @author Ymj Create At 2017年6月29日
     */
    public function listTransByUserProject ($userProject) {
        if(!Validator::isInt($userProject)){
            return [];
        }
        $sql = 'SELECT * FROM `transaction` WHERE `user_project_id` = ' . $userProject . ' AND `confirm_state` = 1 AND `state` = 1';
        return Mysql::_getInstance()->sql($sql)->query()->getResult();
    }
}