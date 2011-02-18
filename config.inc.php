<?php

// this section contains the database connection settings

// COIN Portal
$portal_db="coin_portal";
$portal_db_host="tb1.dev.coin.surf.net";
$portal_db_username="COIN_DB_USER";
$portal_db_password="COIN_DB_USER";
 
// COIN EngineBlock
$eb_db="engine_block";
$eb_db_host="tb1.dev.coin.surf.net";
$eb_db_username="COIN_DB_USER";
$eb_db_password="COIN_DB_USER";

// COIN OpenSocial Shindig
$shindig_db="coin_shindig";
$shindig_db_host="tb1.dev.coin.surf.net";
$shindig_db_username="COIN_DB_USER";
$shindig_db_password="COIN_DB_USER";

// COIN Service Registry (Janus)
$janus_db="service_registry";
$janus_db_host="tb1.dev.coin.surf.net";
$janus_db_username="COIN_DB_USER";
$janus_db_password="COIN_DB_USER";

// This section defines some common query handling
// how many rows to show per page
$rowsPerPage = 1000;
// by default we show first page
$pageNum = 1;
// which starts at offset 0
$offset = 0;

// This section defines some common format and input handling
$outputFormat = "html";
$outputFormats = array("html","json","rss","csv","xml");

// test incoming format parameter
if (strlen($_GET["format"]) <> 0) {
	$outputFormat = $_GET["format"];
	
	IF(!array_search($outputFormat, $outputFormats)){
		echo "Not a supported format!";
		break;
	} 
}

// test incoming month parameter
if (strlen($_GET["month"]) <> 0 && is_int($_GET["month"])) {
	$thismonth = $_GET["month"];
	
	IF($month > 12){
		echo "Month must be between 1 and 12!";
		break;
	} 
}

// test incoming year parameter
if (strlen($_GET["year"]) <> 0 && is_int($_GET["year"])) {
	$thisyear = $_GET["year"];
}

// This section defines some common debug handling
$debug = false;
$debugmsg = array();
