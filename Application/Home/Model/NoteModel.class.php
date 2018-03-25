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
class NoteModel extends Model {

	public function select($user_id) {
		$note = M('note');
		$con['user_id'] = $user_id;
		$con['deleted'] = 0;
		$result = $note->where($con)->select();
		return $result;
	}

	public function insert($ins, $user_id, $map) {
		$note = M('note');

		if ($ins['bookGuid'] == 0) {
			$book_android = $ins['bookId'];
			$book_serverid = $map["k".$book_android];
		} else {
			$book_serverid = $ins['bookGuid'];
		}

		$data['user_id'] = $user_id;
		$data['content'] = $ins['content'];
		$data['create_time'] = $ins['createTime'];
		$data['edit_time'] = $ins['editTime'];
		$data['deleted'] = $ins['deleted'];
		$data['notebook_id'] = $book_serverid;

		$result=$note->data($data)->add();

		$newest = $note -> order('id desc') -> find();
		$id = $newest['id'];
		return $id;
	}

	public function update($upd, $user_id) {
		$note = M('note');

		$data['id'] = $upd['serverId'];
		$data['user_id'] = $user_id;
		$data['content'] = $upd['content'];
		$data['create_time'] = $upd['createTime'];
		$data['edit_time'] = $upd['editTime'];
		$data['deleted'] = $upd['deleted'];
		$data['notebook_id'] = $upd['bookGuid'];

		$note->save($data);
	}

}