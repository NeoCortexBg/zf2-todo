<?php
namespace Todo\Model;

abstract class DateHelper
{
	public static function dateSql($time = null)
	{
		return date('Y-m-d H:i:s', ($time !== null) ? $time : time());
	}
}