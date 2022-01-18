<?php
session_start();
require 'user.php';
include("functions.php");
if(!$_SESSION['uid']) {
	/**
	 * ���å�����ѿ�UID����Ͽ����Ƥ��ʤ����ϡ�
	 * �����ȥ��������Ԥ���
	 * �̾�桼����UID: int(11)
	 * �����Ȥ�UID: t+TIMESTAMP+DUMMYPID
	**/
	$anonuid = 't' . time() . getPid();
	$sessid = md5(uniqid("AFDSDAE"));
	$_SESSION['uid'] = $anonuid;
}

if(ereg("^t[0-9]+", $_SESSION['uid'])) {
	/**
	 * t�ǻϤޤ�UID�Ǥ���Х����ȥ�������
	**/
	$user = new anonUser($_SESSION['uid']);
	$auth = 0;
} else if(ereg("^[0-9]+$", $_SESSION['uid'])) {
	/**
	 * �����ΤߤǤ�����̾��������
	**/
	$user = new User($_SESSION['uid']);
	$auth = 1;
}

$user->loadbase();
$user->loadmail();
