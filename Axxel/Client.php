<?php

namespace Axxel;

class Client
{

	protected $_socket;

	protected $_server;

	protected $_port;

	public function __construct($server="127.0.0.1", $port=1589)
	{
		$this->_server = $server;
		$this->_port = $port;
	}

	function getAcl($name, $initialization=null)
	{
		$acl = new Acl($this, $name);
		if (is_callable($initialization)) {
			if (!$this->isAcl($name)) {
				$initialization($acl);
			}
		}
		return $acl;
	}

	function send($command)
	{
		if (!$this->_socket) {
			$this->_socket = pfsockopen($this->_server, $this->_port, $errno, $errstr, 1);
		}

		if (!$this->_socket) {
			throw new Exception("$errstr ($errno)");
		}

		fwrite($this->_socket, "$command\n");
		while (!feof($this->_socket)) {
			$d = fgets($this->_socket, 128);
			if (substr($d, strlen($d) - 1, 1) == "\n") {
				break;
			}
		}

		return json_decode($d);
	}

	function createAcl($name)
	{
		$status = $this->send("var a = axxel.createAcl('" . addslashes($name) . "'); typeof a == 'object';");
		return new Acl($this, $name);
	}

	function isAcl($name)
	{
		$status = $this->send("axxel.isAcl('" . addslashes($name) . "')");
		if ($status->status == 'ok') {
			return $status->result;
		}
	}

}
