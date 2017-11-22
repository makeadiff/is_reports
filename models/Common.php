<?php
/// Common model. Includes all the neccessay data interactions. Not enough to make seperate models yet.
class Common {
	private $sql;

	function __construct() {
       global $sql;
       $this->sql = $sql;
	}

	public function getTeachers($source)
	{
		global $year;
		$where = "1=1";

		if(!empty($source['city_id'])) {
			$where = "U.city_id = " . $source['city_id'];
		} elseif(!empty($source['center_id'])) {
			$where = "C.id = " . $source['center_id'];
		} elseif(!empty($source['batch_id'])) {
			$where = "B.id = " . $source['batch_id'];
		} else {
			return array();
		}

		$teacher_count = $this->sql->getById("SELECT U.id,U.name 
			FROM User U
			INNER JOIN UserBatch UB ON UB.user_id=U.id
			INNER JOIN Batch B ON B.id=UB.batch_id
			INNER JOIN Center C ON C.id=B.center_id
			WHERE C.status='1' AND U.status='1' AND B.year='$year' AND U.user_type='volunteer' AND " . $where);

		return $teacher_count;
	}

	public function getResponseCount($teacher_ids, $is_event_id)
	{
		if(!$teacher_ids) return array();

		$responses = $this->sql->getById("SELECT R.user_id,COUNT(R.id) AS responses 
			FROM IS_Response R
			WHERE R.is_event_id=$is_event_id AND R.user_id IN (" . implode(",", $teacher_ids) . ")
			GROUP BY R.user_id");

		return $responses;
	}

	public function getStudentCount($teacher_ids)
	{
		if(!$teacher_ids) return array();
		global $year;

		// NOTE : Still not sure if it should be COUNT(DISTINCT SL.student_id) or not. Without distinct it will give the necessary value. If two teachers are teaching the same student, it will be counted as 2.
		$students = $this->sql->getById("SELECT UB.user_id, COUNT(SL.student_id) AS student_count
			FROM Student S
			INNER JOIN StudentLevel SL ON SL.student_id=S.id 
			INNER JOIN Level L ON L.id=SL.level_id
			INNER JOIN UserBatch UB ON UB.level_id=SL.level_id
			WHERE L.year = $year AND S.status='1' AND UB.user_id IN (" . implode(",", $teacher_ids) . ")
			GROUP BY SL.level_id");

		return $students;
	}

	public function getAllCities()
	{
		return $this->sql->getAll("SELECT id,name FROM City WHERE type='actual' ORDER BY name");
	}

	public function getLatestISEvent()
	{
		return $this->sql->getOne("SELECT id FROM IS_Event ORDER BY added_on DESC LIMIT 0,1");
	}

	public function getISQuestions()
	{
		return $this->sql->getById("SELECT id,question FROM IS_Question WHERE status='1'");
	}
}