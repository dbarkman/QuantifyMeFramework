<?php

/**
 * Category.model.php
 * Description:
 *
 */

class Category
{
	private $_logger;
	private $_db;

	private $_uuid;
	private $_name;
	private $_created;
	private $_modified;

	public function __construct($logger, $db)
	{
		$this->_logger = $logger;

		$this->_db = $db;
	}

	public static function GetCategoriesForAPI()
	{
		$categoryArray = array();

		$sql = "
			SELECT
				uuid,
				name
			FROM
				categories
			ORDER BY
				sortOrder, name
		";

		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result)) {
			$categoryArray[] = array('name' => $row['name'], 'uuid' => $row['uuid']);
		}

		return $categoryArray;
	}

	public static function GetCategoryUUIDForName($name)
	{
		$sql = "
			SELECT
				uuid
			FROM
				categories
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

	public function createCategory()
	{
		$sql = "
			INSERT INTO
				categories
			SET
				uuid = '$this->_uuid',
				name = '$this->_name',
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
}
