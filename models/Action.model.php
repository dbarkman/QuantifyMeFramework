<?php

/**
 * Action.model.php
 * Description:
 *
 */

class Action
{
	private $_logger;
	private $_db;

	private $_uuid;
	private $_name;
	private $_category;
	private $_created;
	private $_modified;

	public function __construct($logger, $db)
	{
		$this->_logger = $logger;

		$this->_db = $db;
	}

	public static function GetActionsForCategoryForAPI($category)
	{
		$actionArray = array();

		$sql = "
			SELECT
				uuid,
				name,
				category
			FROM
				actions
			WHERE
				category = '$category'
			ORDER BY
				sortOrder, name
		";

		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result)) {
			$actionArray[] = array('name' => $row['name'], 'uuid' => $row['uuid']);
		}

		return $actionArray;
	}

	public static function GetActionUUIDForName($name)
	{
		$sql = "
			SELECT
				uuid
			FROM
				actions
			WHERE
				name = '$name'
		";

		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			return $row['0'];
		}
		return 'tbd';
	}

	public function createAction()
	{
		$sql = "
			INSERT INTO
				actions
			SET
				uuid = '$this->_uuid',
				name = '$this->_name',
				category = '$this->_category',
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

	public function setUuid($uuid)
	{
		$this->_uuid = mysql_real_escape_string($uuid);
	}

	public function getUuid()
	{
		return $this->_uuid;
	}

	public function setName($category)
	{
		$this->_name = mysql_real_escape_string($category);
	}

	public function getName()
	{
		return $this->_name;
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

	public function setCategory($category)
	{
		$this->_category = mysql_real_escape_string($category);
	}

	public function getCategory()
	{
		return $this->_category;
	}
}
