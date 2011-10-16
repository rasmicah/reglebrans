<?php

class UrlHelper
{
	const CONTROLLER_PARAM = 'controller';
	const ACTION_PARAM = 'action';

	public static function getScriptUrl()
	{
		$scriptUrl  = null;
		$scriptName = basename($_SERVER['SCRIPT_FILENAME']);
		
		if(basename($_SERVER['SCRIPT_NAME']) === $scriptName)
		{
			$scriptUrl = $_SERVER['SCRIPT_NAME'];
		}
		else if(basename($_SERVER['PHP_SELF']) === $scriptName)
		{
			$scriptUrl = $_SERVER['PHP_SELF'];
		}
		else if(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName)
		{
			$scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
		}
		else if(($pos = strpos($_SERVER['PHP_SELF'], '/'.$scriptName)) !== false)
		{
			$scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos).'/'.$scriptName;
		}
		else if(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0)
		{
			$scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
		}
		else
		{
			// unable to determine entry script url, throw an exception or something
		}

		return $scriptUrl;
	}

	public static function getBaseUrl()
	{
		$baseUrl = rtrim(dirname(self::getScriptUrl()), '\\/');

		return $baseUrl;
	}

	/**
	 * Generates a url in the form index.php?controller=controller_name&action=action_name&param1=valx&param2=valy
	 * UrlHelper::generateUrl('music','play', array('track'=>'1', 'repeat'=>'no')); produces index.php?controller=music&action=play&track=1&repeat=no
	 * $params is optional so UrlHelper::generateUrl('music','play'); produces index.php?controller=music&action=play
	 */
	public static function generateUrl($controller, $action, $params = array())
	{
		$url = strtr(self::getScriptUrl().'?{controller_param}={controller}&{action_param}={action}', array(
			'{controller_param}'=>self::CONTROLLER_PARAM, 
			'{action_param}'=>self::ACTION_PARAM,
			'{controller}'=>$controller, 
			'{action}'=>$action
		));

		foreach($params as $param => $value)
		{
			if($param == self::CONTROLLER_PARAM || $param == self::ACTION_PARAM)
			{
				continue;
			}
			$url .= '&'.$param.'='.$value;
		}

		return $url;
	}
}