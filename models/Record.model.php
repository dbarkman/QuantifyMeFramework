<?php

/**
 * Record.model.php
 * Description:
 *
 */

class Record
{
	private $_logger;
	private $_db;

	private $_user;
	private $_category;
	private $_action;
	private $_detail;
	private $_description;
	private $_created;
	private $_modified;

	public function __construct($logger, $db)
	{
		$this->_logger = $logger;

		$this->_db = $db;
	}

	public function createRecord()
	{
		$sql = "
			INSERT INTO
				records
			SET
				user = '$this->_user',
				category = '$this->_category',
				action = '$this->_action',
				detail = '$this->_detail',
				description = '$this->_description',
				created = '$this->_created',
				modified = '$this->_modified'
		";

		mysql_query($sql);
		$insertId = mysql_insert_id();
		$rowsAffected = mysql_affected_rows();

		if ($rowsAffected === 1) {
			$this->tweetRecord($insertId);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function tweetRecord($insertId)
	{
		$sql = "
			SELECT
				c.name AS category,
				a.name AS action,
				r.detail,
				FROM_UNIXTIME(r.created, '%H:%i:%s %m/%d/%y') AS date,
				c.private
			FROM
				records r
				JOIN categories c ON c.uuid = r.category
				JOIN actions a ON a.uuid = r.action
			WHERE
				r.entry = '$insertId'
		";

		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);

		$status = 'Quantified: ' . $row['category'] . ':' . $row['action'];
		if (strlen($row['detail'] > 0)) $status .= ':' . $row['detail'];
		$status .= ' @ ' . $row['date'];

		$tweet = array(
			'status' => $status
		);

		if ($row['private'] != 1) {
			$this->_logger->debug('Tweeting this: ' . $status);

			global $twitterCreds;
			$twitter = new Twitter($twitterCreds['consumerKey'], $twitterCreds['consumerSecret'], $twitterCreds['accessToken'], $twitterCreds['accessTokenSecret']);
			if ($twitter->tweet($tweet) === false) {
				$this->_logger->error('Twitter post failed for record');
			} else {
				$this->_logger->info('Twitter post succeeded for record');
			}
		}
	}

	public function setUser($user)
	{
		$this->_user = mysql_real_escape_string($user);
	}

	public function getUser()
	{
		return $this->_user;
	}

	public function setCategory($category)
	{
		$this->_category = mysql_real_escape_string($category);
	}

	public function getCategory()
	{
		return $this->_category;
	}

	public function setAction($action)
	{
		$this->_action = mysql_real_escape_string($action);
	}

	public function getAction()
	{
		return $this->_action;
	}

	public function setDetail($detail)
	{
		$this->_detail = mysql_real_escape_string($detail);
	}

	public function getDetail()
	{
		return $this->_detail;
	}

	public function setDescription($description)
	{
		$this->_description = mysql_real_escape_string($description);
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function setCreated($created)
	{
		$this->_created = mysql_real_escape_string($created);
	}

	public function getCreated()
	{
		return $this->_created;
	}

	public function setModified($modified)
	{
		$this->_modified = mysql_real_escape_string($modified);
	}

	public function getModified()
	{
		return $this->_modified;
	}
}
