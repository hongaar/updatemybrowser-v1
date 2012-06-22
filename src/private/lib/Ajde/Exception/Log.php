<?php

class Ajde_Exception_Log extends Ajde_Object_Static
{
	static private function _getFilename()
	{
		return LOG_DIR . date("Ymd") . '.log';
	}
	
	static public function logException(Exception $exception)
	{
		$filename = self::_getFilename();
		if (!is_writable(LOG_DIR))
		{
			throw new Ajde_Exception(sprintf("Directory %s is not writable", LOG_DIR), 90014);
		}
		$fh = fopen($filename, 'a');
		if (!$fh) {
			/*
			 * Don't throw an exception here, since this function is generally
			 * called from an error handler
			 */
			return false;
		}
		fwrite($fh, "\n\n".date("H:i:sP") . ":\n");
		$trace = strip_tags( Ajde_Exception_Handler::trace($exception, Ajde_Exception_Handler::EXCEPTION_TRACE_LOG) );
		fwrite($fh, $trace);
		fclose($fh);
	}
}