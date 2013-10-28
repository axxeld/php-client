<?php

require "Axxel/Client.php";
require "Axxel/Acl.php";

$axxel = new Axxel\Client();

$acl = $axxel->getAcl("my-acl", function($acl) {

	$acl->addRole("Administrators");
	$acl->addResource("Customers");
	$acl->allow("Administrators", "Customers", "insert");
	$acl->allow("Administrators", "Customers", "update");

});

var_dump($acl->isAllowed(
	"Administrators",
	"Customers",
	"update"
));
