<?php
require '../common.php';

$city_id = i($QUERY, 'city_id');
$center_id = i($QUERY, 'center_id');
$batch_id = i($QUERY, 'batch_id');
$is_event_id = i($QUERY, 'is_event_id');

$year = 2017;
$model = new Common;

if(!$is_event_id) { // If no IS Event ID given, chosoe latest automatically.
	$is_event_id = $model->getLatestISEvent();
}
$question_count = count($model->getISQuestions());
$page_title = 'Impact Survey Reports';

if($city_id) {

} elseif($center_id) {

} elseif ($batch_id) {
	# code...
} else { // National
	$page_title .= " : National";
	$next_level_key = 'city_id';

	$all_cities = $model->getAllCities();
	$data = array();
	foreach ($all_cities as $city) {
		$city_id = $city['id'];

		$all_teachers = $model->getTeachers(array('city_id' => $city_id));
		$teacher_count = count($all_teachers);

		$responses_count = $model->getResponseCount(array_keys($all_teachers), $is_event_id);
		$students_count = $model->getStudentCount(array_keys($all_teachers));

		$total_student_count = array_sum(array_values($students_count));
		$total_response_count = array_sum(array_values($responses_count));
		$possible_response_count = $total_student_count * $question_count;

		$completion_percentage = 0;
		if($total_response_count and $possible_response_count)
			$completion_percentage = round($total_response_count / $possible_response_count * 100, 2);

		$data[$city_id] = array(
			'id'	=> $city_id,
			'name'	=> $city['name'],
			'teacher_count' 		=> $teacher_count,
			'total_response_count' 	=> $total_response_count,
			'possible_response_count' => $possible_response_count,
			'completion_percentage'	=> $completion_percentage
		);
	}
}

render();
