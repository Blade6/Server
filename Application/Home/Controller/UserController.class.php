<?php
namespace Home\Controller;
use Think\Controller;
use Home\Event\JsonEvent;
class UserController extends Controller {

    public function test() {
        // 从文件中读取数据到PHP变量  
        $json_string = file_get_contents('test.json');  
        // 把JSON字符串转成PHP数组  
        $data = json_decode($json_string, true); 
        
        $user_id = $data['user_id'];
        $booksData = $data['booksData'];
        $notesData = $data['notesData'];
        dump($notesData);

        $result1 = $this->Books($user_id, $booksData['insertNoteBooks'], $booksData['updateNoteBooks']);
        $result2 = $this->Notes($result1, $user_id, $notesData['insertNotes'], $notesData['updateNotes']);

        $json['returnCode'] = "succ";
        $json['info'] = 2; // 下达id更新
        $json['data'] = array("books"=>$result1, "notes"=>$result2);
        dump($json);

        echo json_encode($json,JSON_UNESCAPED_UNICODE); 

        //D('user') -> addSynUid($user_id);
    }

    public function getSynUid($user_id) {
        $con['id'] = $user_id;
        $data = M('user') -> where($con) -> find();
        if(count($data)>0) {
            $json['returnCode'] = "succ";
            $json['info'] = 4;
            $json['data'] = $data['syn_uid'];
        } else {
            $json['returnCode'] = "fail";
            $json['info'] = 0;
        }
        header('Content-type:text/json');
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    // 登录
    public function login($usr, $pwd) {
        $userid = D('user')->getUserID($usr);
        $result = D('user')->userLogin($usr, $pwd);
        if (count($result) > 0) {
            $json['returnCode'] = "succ";
            $json['info'] = 1;
            $json['data'] = $result;
        } else {
            $json['returnCode'] = "fail";
            $json['info'] = 0;
        }
        header('Content-type:text/json');
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }
    
    // 注册
    public function register($usr,$pwd) {
        $result = D('user')->userSignup($usr,$pwd);
        if ($result > 0) {
            $json['returnCode'] = "succ";
        } else {
            $json['returnCode'] = "fail";
        }
        $json['info'] = 0;
        header('Content-type:text/json');
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    public function synd($user_id) {
        $books = D('dir') -> select($user_id);
        $notes = D('note') -> select($user_id);

        $data = array("books"=>$books,"notes"=>$notes);
        
        $json['returnCode'] = "succ";
        $json['info'] = 3;
        $json['data'] = $data;
        header('Content-type:text/json');
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    // 同步
    public function synu() {
        header("Content-type:text/json; charset=utf-8"); 

        $input = file_get_contents("php://input");
        $data = (array) json_decode($input, true);
        
        $user_id = $data['user_id'];
        $booksData = $data['booksData'];
        $notesData = $data['notesData'];

        $result1 = $this->Books($user_id, $booksData['insertNoteBooks'], $booksData['updateNoteBooks']);
        $result2 = $this->Notes($result1, $user_id, $notesData['insertNotes'], $notesData['updateNotes']);

        $json['returnCode'] = "succ";
        $json['info'] = 2; // 下达id更新
        $json['data'] = array("books"=>$result1, "notes"=>$result2);

        echo json_encode($json,JSON_UNESCAPED_UNICODE); 

        D('user') -> addSynUid($user_id);
    }

    private function Books($user_id, $insert, $update) {
        foreach($insert as $value1) {
            $id = $value1['androidId'];
            $serverId = D('dir') -> insert($value1, $user_id);
            $result["k".$id] = $serverId;
        }

        foreach ($update as $value2) {
            $id = $value2['androidId'];
            D('dir') -> update($value2, $user_id);
            $result["k".$id] = $value2['serverId'];
        }

        return $result;
    }

    private function Notes($map, $user_id, $insert, $update) {
        foreach($insert as $value1) {
            $id = $value1['androidId'];
            $re = D('note') -> insert($value1, $user_id, $map);
            $result["k".$id] = $re;
        }

        foreach ($update as $value2) {
            D('note') -> update($value2, $user_id);
        }

        return $result;
    }
     
    // 修改密码
    public function changeUserpwd($userid,$oldpwd,$newpwd){
        $pwd=D('user')->userPwd($userid);
        if($pwd==$oldpwd){
            $result=D('user')->changePwd($userid, $newpwd);
            if ($result) {
                $json['returnCode'] = 1;
                $json['msg'] = "success";
            } else {
                $json['returnCode'] = 0;
                $json['msg'] = "fail";
            }
        } else {
            $json['returnCode'] = 0;
            $json['msg'] = "fail";
        }
        header('Content-type:text/json');
        echo json_encode($json,JSON_UNESCAPED_UNICODE); 
    }
    
    /**
     * 获取个人信息
     */
    public function getUserMsg($userid){
        $result = D('user') ->findUser($userid);
        if($result){
            $data['userid'] = $result['id'];
            $data['username'] = $result['username'];
            $data['pic'] = $result['pic'];
            $json = (new JsonEvent())->encapsulate($data, "userInfo");
            header('Content-type:text/json');
            echo json_encode($json,JSON_UNESCAPED_UNICODE);
        }
    }
    
}