<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserModel
 *
 * @author jianhong
 */
namespace Home\Model;
use Think\Model;
class UserModel extends Model {

   // ---------------------业务API-------------------------- 
    public function userSignup($username,$password) {
        $user = M('user');
        // 先检验用户名是否存在
        $data['username']=$username;
        if($user->where($data)->find()) return -1;
        
        $data['password']=$password;
        $data['pic'] = '/wechat/Public/Users/default.png';
        $result=$user->data($data)->add();
        if($result) return 1;
        else return 0;
    }
    
    public function userLogin($username, $pwd) {
        $user = M('user');
        $data['username'] = $username;
        $data['password'] = $pwd;
        $result = $user->where($data)->find();
        return $result;
    }

    public function addSynUid($user_id) {
        $user = M('user');
        $con['id'] = $user_id;
        $data = $user->where($con)->find();
        $syn_uid = $data['syn_uid'];
        $con['syn_uid'] = $syn_uid + 2;
        $user->save($con);
    }

    public function changePwd($id,$newpwd){
        $user = M('user');
        $data["id"] = $id;
        $data['password'] = $newpwd;
        $result = $user->save($data);
        return $result;
    }
    
    // ---------------------数据存取API-------------------------- 
    // 判断用户名是否已经存在
    public function IsUserExist($username) {
        $user = M('user');
        $data["username"] = $username;
        $re = $user->where($data)->find();
        if ($re) return true;
        else return false;
    }

    // 根据用户名获取用户id
    public function getUserID($username) {
        $user = M('user');
        $data["username"] = $username;
        $result = $user->field("password")->where($data)->find();
        return $result["id"];
    }
    
    // 获取用户信息
    public function findUser($id){
        $user = M('user');
        $data['id']=$id;
        $result=$user->where($data)->find();
        return $result;
    }
    
    // 获取用户密码
    public function userPwd($id) {
        $user = M('user');
        $data["id"] = $userid;
        $result = $user->field("password")->where($data)->find();
        return $result["password"];
    }
    
}
