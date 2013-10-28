<?php

namespace Axxel;

class Acl
{

	protected $_axxel;

	protected $_name;

	public function __construct($axxel, $name)
	{
		$this->_axxel = $axxel;
		$this->_name = $name;
	}

	public function addRole($name)
	{
		return $this->_axxel->send("typeof axxel.getAcl('" . addslashes($this->_name) . "').addRole('" . addslashes($name) . "')=='object';");
	}

	public function isRole($name)
	{
		return $this->_axxel->send("axxel.getAcl('" . addslashes($this->_name) . "').isRole('" . addslashes($name) . "');");
	}

	public function getRoles()
	{
		return $this->_axxel->send("JSON.stringify(axxel.getAcl('" . addslashes($this->_name) . "').getRoles());");
	}

	public function addResource($name)
	{
		return $this->_axxel->send("typeof axxel.getAcl('" . addslashes($this->_name) . "').addResource('" . addslashes($name) . "')=='object';");
	}

	public function isResource($name)
	{
		return $this->_axxel->send("axxel.getAcl('" . addslashes($this->_name) . "').isResource('" . addslashes($name) . "');");
	}

	public function getResources()
	{
		return $this->_axxel->send("JSON.stringify(axxel.getAcl('" . addslashes($this->_name) . "').getResources());");
	}

	public function allow($role, $resource, $action)
	{
		return $this->_axxel->send("typeof axxel.getAcl('" .
			addslashes($this->_name) . "').allow('" .
			addslashes($role) . "', '" .
			addslashes($resource) . "', '" .
			addslashes($action) . "')=='object';");
	}

	public function isAllowed($role, $resource, $permissions)
	{

		$name = addslashes($this->_name);
		$roleName = addslashes($role);
		$resourceName = addslashes($resource);

		if (is_array($permissions)) {
			$perms = array();
			foreach ($permissions as $permission) {
				$perms[] = '"' . addslashes($permission) . '"';
			}
			$status = $this->_axxel->send("axxel.getAcl('" . $name . "').allowed('" . $roleName . "', '" . $resourceName . "', [" . join(", ", $perms) . "]);");
		} else {
			$status = $this->_axxel->send("axxel.getAcl('" . $name . "').allowed('" . $roleName . "', '" . $resourceName . "', '" . addslashes($permissions) . "');");
		}

		if ($status->status == 'ok') {
			return $status->result;
		}

		return false;
	}

}