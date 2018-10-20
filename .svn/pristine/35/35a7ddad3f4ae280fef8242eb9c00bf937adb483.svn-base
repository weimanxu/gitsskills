<?php
class WithdrawClass extends CustomBaseClass{
    
    /**
     * 根据uid，type获取提现地址
     * 
     * @param  int      $uid
     * @param  string   $type
     * @return array
     * @author Ymj Create At 2017年7月27日
     */
    public function getAddressByUidAndType ($uid, $type) {
        if (!Validator::isInt($uid)){
            return null;
        }
        if (!in_array($type, App::loadConf('app/currency'))){
            return null;
        }
        $addressInfo=Mysql::_getInstance()
                ->sql('SELECT * FROM `withdraw_address` WHERE `user_id` = '.$uid.' AND `type` = "'.$type.'" AND `state` = 1 ')
                ->queryOne()
                ->getResult();
        return $addressInfo;
    }
    
}