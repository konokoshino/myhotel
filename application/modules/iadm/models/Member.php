<?php

class Iadm_Model_Member extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'user';
		$this->_primary = 'User_Id';
	}

	public function getListMember($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			if (isset($options['search'])) {
				if ($options['search']['EmailSearch'] != null) {
					$select->where("User_Email like '%" . $options['search']['EmailSearch'] . "%'");
				}
				if ($options['search']['StatusSearch'] != null) {
					$select->where("User_Status =?", $options['search']['StatusSearch']);
				}
			}
			$select->where("User_Type =?", 2);
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('User_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->where("User_Type =?", 2);
			$select->order('User_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			if (isset($options['search'])) {
				if ($options['search']['EmailSearch'] != null) {
					$select->where("User_Email like '%" . $options['search']['EmailSearch'] . "%'");
				}
				if ($options['search']['StatusSearch'] != null) {
					$select->where("User_Status =?", $options['search']['StatusSearch']);
				}
			}
			$select->where("User_Type =?", 2);
			$select->order('User_Id DESC');
			$result = $this->fetchRow($select);
		}

		if ($options['task'] == 'checkEmail') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->where('User_Email =?', $options['email']);
			$result = $this->fetchRow($select);
		}

		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('User_Id =?', $options['Id']);
			$select->where("User_Type =?", 2);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertMember($params) {
		$params['User_Type'] = 2;
		$this->insert($params);
	}

	public function updateMember($data, $id) {

		$where = 'User_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteMember($id) {

		$where = 'User_Id=' . $id;
		$this->delete($where);
	}

}

?>
