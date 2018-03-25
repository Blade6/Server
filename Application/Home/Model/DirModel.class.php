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
class DirModel extends Model {

	public function select($user_id) {
		$book = M('dir');
		$con['user_id'] = $user_id;
		$con['deleted'] = 0;
		$result = $book->where($con)->select();
		return $result;
	}

	public function insert($ins, $user_id) {
		$book = M('dir');

		$data['user_id'] = $user_id;
		$data['name'] = $ins['name'];
		$data['count'] = $ins['notesNum'];
		$data['deleted'] = $ins['deleted'];

		$result=$book->data($data)->add();

		$newest = $book -> order('id desc') -> find();
		$id = $newest['id'];
		return $id;
	}

	public function update($upd, $user_id) {
		$book = M('dir');

		$data['id'] = $upd['serverId'];
		$data['user_id'] = $user_id;
		$data['name'] = $upd['name'];
		$data['count'] = $upd['notesNum'];
		$data['deleted'] = $upd['deleted'];

		$book->save($data);
	}

}