<?php

class Ajde_Exception_Handler extends Ajde_Object_Static
{
	public static function __bootstrap()
	{
		// TODO: why is this defined here? also in index.php!
		set_error_handler(array('Ajde_Exception_Handler', 'errorHandler'));
		set_exception_handler(array('Ajde_Exception_Handler', 'handler'));
		return true;
	}

	public static function errorHandler($errno, $errstr, $errfile, $errline)
	{
		error_log(sprintf("PHP error: %s in %s on line %s", $errstr, $errfile, $errline));
		// TODO: only possible in PHP >= 5.3 ?
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	public static function handler(Exception $exception)
	{
		try
		{	
			if (Config::getInstance()->debug === true)
			{
				if (!((get_class($exception) == 'Ajde_Exception' || is_subclass_of($exception, 'Ajde_Exception')) && !$exception->traceOnOutput())) {
					echo self::trace($exception);
				} else {
					Ajde_Exception_Log::logException($exception);				
					Ajde_Http_Response::redirectServerError();
				}
			}
			else
			{
				Ajde_Exception_Log::logException($exception);				
				Ajde_Http_Response::redirectServerError();
			}
		}
		catch (Exception $exception)
		{
			error_log(self::trace($exception, self::EXCEPTION_TRACE_LOG));
			die("An uncatched exception occured within the error handler, see the server error_log for details");
		}
	}

	const EXCEPTION_TRACE_HTML = 1;
	const EXCEPTION_TRACE_LOG = 2;

	public static function trace(Exception $exception, $output = self::EXCEPTION_TRACE_HTML)
	{
		if ($exception instanceof ErrorException)
		{
			$type = "PHP Error " . self::getErrorType($exception->getSeverity());
		}
		elseif ($exception instanceof Ajde_Exception)
		{
			$type = "Ajde uncaught exception " . $exception->getCode();
		}
		else
		{
			$type = "Uncaught exception " . $exception->getCode();
		}

		switch ($output) {
			case self::EXCEPTION_TRACE_HTML:
				if (ob_get_level()) {
					ob_clean();
				}
				
				$traceMessage = '<ol reversed="reversed">';
				self::$firstApplicationFileExpanded = false; 
				foreach($exception->getTrace() as $item) {
					$arguments = null;
					if (!empty($item['args'])) {
						ob_start();
						var_dump($item['args']);
						$dump = ob_get_clean();
						$arguments = sprintf(' with arguments: %s', $dump);
					}
					$traceMessage .= sprintf("<li><em>%s</em>%s<strong>%s</strong><br/>in %s<br/>&nbsp;\n",							
							!empty($item['class']) ? $item['class'] : '&lt;unknown class&gt;',
							!empty($item['type']) ? $item['type'] : '::',
							!empty($item['function']) ? $item['function'] : '&lt;unknown function&gt;',
							self::embedScript(
									isset($item['file']) ? $item['file'] : null,
									isset($item['line']) ? $item['line'] : null,
									$arguments,
									false									
							));					
					$traceMessage .= '</li>';
				}
				$traceMessage .= '</ol>';
				
				$exceptionMessage = sprintf("<h3>%s:</h3><h2>%s</h2> in %s\n",
						$type,
						$exception->getMessage(),
						self::embedScript(
								$exception->getFile(),
								$exception->getLine(),
								$arguments,
								!self::$firstApplicationFileExpanded
						)						
				);

				if ($exception instanceof Ajde_Exception && $exception->getCode()) {
					$exceptionMessage .= sprintf('<span style="border:1px solid black;display:inline-block;"><strong style="border-right:1px solid #aaa;padding:1px 8px;display:inline-block;background-color:yellow;">i</strong> <a href="%s">Ajde documentation on error %s</a>&nbsp;</span>',
						Ajde_Core_Documentation::getUrl($exception->getCode()),
						$exception->getCode()
					);
				}
				
				$exceptionDump = '';
				if (class_exists("Ajde_Dump")) {
					if ($dumps = Ajde_Dump::getAll()) {
						$exceptionDump .= '<h2>Dumps</h2>';
						foreach($dumps as $dump) {
							ob_start();
							var_dump($dump);
							$exceptionDump  .= ob_get_clean();
						}			
					} 
				}
				
				$style = '<style>';
				$style .= file_get_contents(MODULE_DIR . '_core/res/css/debugger/handler.css');
				$style .= '</style>';
				
				$message = $style . $exceptionDump . $exceptionMessage . $traceMessage;
				break;
			case self::EXCEPTION_TRACE_LOG:
				$message = sprintf("%s: %s in %s on line %s",
						$type,
						$exception->getMessage(),
						$exception->getFile(),
						$exception->getLine()
				);
				break;
		}
		return $message;
	}
	
	public static function getErrorType($type)
	{
		switch ($type)
		{
			case 1: return "E_ERROR";
			case 2: return "E_WARNING";
			case 4: return "E_PARSE";
			case 8: return "E_NOTICE";
			case 16: return "E_CORE_ERROR";
			case 32: return "E_CORE_WARNING";
			case 64: return "E_COMPILE_ERROR";
			case 128: return "E_COMPILE_WARNING";
			case 256: return "E_USER_ERROR";
			case 512: return "E_USER_WARNING";
			case 1024: return "E_USER_NOTICE";
			case 2048: return "E_STRICT";
			case 4096: return "E_RECOVERABLE_ERROR";
			case 8192: return "E_DEPRECATED";
			case 16384: return "E_USER_DEPRECATED";
			case 30719: return "E_ALL";
		}
	}

	static $firstApplicationFileExpanded = false;
	 
	protected static function embedScript($filename = null, $line = null, $arguments = null, $expand = false)
	{
		$lineOffset = 3;
		$file = '';
		if (isset($filename) && isset($line))
		{
			$lines = file($filename);
			for ($i = max(0, $line - $lineOffset - 1); $i < min($line + $lineOffset, count($lines)); $i++)
			{
				$lineNumber = str_repeat(" ", 4 - strlen($i + 1)) . ($i + 1);
				if ($i == $line - 1)
				{
					$file .= "<span style='background-color: yellow;'>" . $lineNumber . htmlentities($lines[$i]) . "</span>";
				}
				else
				{
					$file .= $lineNumber . htmlentities($lines[$i]);
				}
			}
		}
		
		if (substr_count($filename, str_replace('/', DIRECTORY_SEPARATOR, APP_DIR))) {
			$filename = '<span style="color: red;">' . $filename . '</span>';
			if (self::$firstApplicationFileExpanded === false) {
				$expand = true;
			}
			self::$firstApplicationFileExpanded = true; 
		}

		$id = md5(microtime());
		return sprintf(
				"<a
					onclick='document.getElementById(\"$id\").style.display = document.getElementById(\"$id\").style.display == \"block\" ? \"none\" : \"block\";'
					href='javascript:void(0);'
				><i>%s</i> on line <b>%s</b></a>&nbsp;<div id='$id' style='display:%s;'><pre style='border:1px solid gray;background-color:#eee;'>%s</pre>%s</div>",
				$filename,
				$line,
				$expand ? "block" : "none",
				$file,
				$arguments
		);
	}
	
}