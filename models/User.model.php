<?php

/**
 * User.model.php
 * Description:
 *
 */

class User
{
	private $_logger;
	private $_db;

	private $_entry;
	private $_uuid;
	private $_email;
	private $_created;
	private $_modified;

	private $_userArray;

	public function __construct($logger, $db, $email = null)
	{
		$this->_logger = $logger;

		$this->_db = $db;

		if ($email != null) {
			$sql = "
				SELECT
					*
				FROM
					users
				WHERE
					email = '$email'
			";

			$result = mysql_query($sql);
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				$this->_entry = $row['entry'];
				$this->_uuid = $row['uuid'];
				$this->_email = $row['email'];
				$this->_created = $row['created'];
				$this->_modified = $row['modified'];
				$this->_userArray[] = array('entry' => $row['entry'], 'uuid' => $row['uuid'], 'email' => $row['email'], 'created' => $row['created'], 'modified' => $row['modified']);
			} else {
				return null;
			}
		}
	}

	public static function GetUsersForAPI()
	{
		$userArray = array();

		$sql = "
			SELECT
				uuid,
				email
			FROM
				users
			ORDER BY
				email
		";

		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result)) {
			$userArray[] = array('email' => $row['email'], 'uuid' => $row['uuid']);
		}

		return $userArray;
	}

	public function createUser()
	{
		$sql = "
			INSERT INTO
				users
			SET
				uuid = '$this->_uuid',
				email = '$this->_email',
				created = '$this->_created',
				modified = '$this->_modified'
		";

		mysql_query($sql);
		$rowsAffected = mysql_affected_rows();

		if ($rowsAffected === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function getEntry()
	{
		return $this->_entry;
	}

	public function setUuid($uuid)
	{
		$this->_uuid = mysql_real_escape_string($uuid);
	}

	public function getUuid()
	{
		return $this->_uuid;
	}

	public function setEmail($email)
	{
		$this->_email = mysql_real_escape_string($email);
	}

	public function getEmail()
	{
		return $this->_email;
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

	public function getUserArray()
	{
		return $this->_userArray;
	}
}
