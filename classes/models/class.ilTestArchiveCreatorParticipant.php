<?php

/**
 * Data model for question list
 */
class ilTestArchiveCreatorParticipant extends ilTestArchiveCreatorElement
{
	public $active_id;
	public $firstname;
	public $lastname;
	public $login;
	public $matriculation;
	public $exam_id;

	public $pass_number;
	public $pass_scored;
	public $pass_working_time;
	public $pass_finish_date;
	public $pass_reached_points;

	public $answers_file;

	/**
	 * Get a unique prefix that can be used for file and directory names
	 * @return mixed
	 */
	public function getFolderName()
	{
		return $this->creator->sanitizeFilename($this->lastname.'_'.$this->firstname.'_'.$this->active_id);
	}

	/**
	 * Get a unique prefix that can be used for file and directory names
	 * @return mixed
	 */
	public function getFilePrefix()
	{
		return $this->creator->sanitizeFilename($this->exam_id);
	}

	/**
	 * Get a unique index for sorting the list of elements
	 * @return mixed
	 */
	function getSortIndex()
	{
		return $this->lastname.'_'.$this->firstname.'_'.$this->exam_id;
	}

	/**
	 * Get the list of columns for this element type
	 * The file list should have the key 'files'
	 * @return array    key => title
	 */
	function getColumns()
	{
		return array(
			'firstname' => $this->lng->txt('firstname'),
			'lastname' => $this->lng->txt('lastname'),
			'login' => $this->lng->txt('login'),
			'matriculation' => $this->lng->txt('matriculation'),
			'exam_id' => $this->plugin->txt('exam_id'),
			'pass_number' => $this->plugin->txt('pass_number'),
			'pass_scored' => $this->plugin->txt('is_scored'),
			'pass_working_time' => $this->plugin->txt('working_time'),
			'pass_finish_date' => $this->plugin->txt('finish_date'),
			'pass_reached_points' => $this->plugin->txt('points'),
			'answers_file' => $this->plugin->txt('answers')
		);
	}

	/**
	 * Get the labels of contents where the data is a link
	 * @return array key => label
	 */
	function getLinkedLabels()
	{
		return array(
			'answers_file' => $this->plugin->txt('answers')
		);
	}


	/**
	 * Get the data row for this element
	 * @param string $format ('csv' or 'html')
	 * @return array key => content
	 */
	function getRowData($format = 'csv')
	{
		$pass_finish_date = new ilDateTime($this->pass_finish_date, IL_CAL_UNIX);
		return array(
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'login' => $this->login,
			'matriculation' => $this->matriculation,
			'exam_id' => $this->exam_id,
			'pass_number' => $this->pass_number,
			'pass_scored' => $format == 'csv' ? $this->pass_scored : ($this->pass_scored ? $this->lng->txt('yes') : $this->lng->txt('no')),
			'pass_finish_date' => $format == 'csv' ? $pass_finish_date->get(IL_CAL_DATETIME) : ilDatePresentation::formatDate( $pass_finish_date),
			'pass_working_time' => $format == 'csv' ? $this->pass_working_time : ilDatePresentation::secondsToString($this->pass_working_time),
			'pass_reached_points' => $this->pass_reached_points,
			'answers_file' => $this->answers_file,
		);
	}
}