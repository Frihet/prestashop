<?php

function GetStackTrace ($slice = 0)
{
  $st = debug_backtrace ();
  $res = "<table>
<tr><th>Function</th><th>File</th><th>Line</th></tr>\n";

  $st = array_slice ($st, $slice+1);

  foreach ($st as $i)
    {
      $arg_str = array ();
      if (isset ($i['args']))
	{
	  foreach ($i['args'] as $arg)
	    {
	      if (is_numeric ($arg))
		{
		  $arg_str[] = "$arg";
		}
	      else if (is_string ($arg))
		{
		  $msg = $arg;
		  if (strlen($arg) > 20)
		    {
		      $msg = substr ($msg, 0, 15). "...";
		    }
		  $arg_str[] = "'".htmlentities($msg)."'";
	    }
	      else if (is_null ($arg))
		{
		  $arg_str[] = "&lt;null&gt;";
		}
	      else if (is_array($arg))
		{
		  $arg_str [] = "&lt;array&gt;[" . count ($arg) . "]";
		}
	      else if (is_object ($arg))
		{
		  $arg_str [] = "&lt;object&gt;";
		}
	      else
		{
		  $arg_str [] = "&lt;unknown&gt;";
		}
	    }
	}
      $string = "<tr><td><b>";
      if (isset ($i['class']))
	$string .= $i['class']. "::";

      if (isset ($i['function']))
	$string .= $i['function'];

      $string .=" (". implode (", ", $arg_str). ")</b>: ";
      $string .= "</td><td>\n";


      if (isset ($i['file']))
	{
	  $file = $i['file'];
	  if (strlen ($file) > 30)
	    {
	      $file = "..." . substr ($file, strlen ($file)-27);
	    }
	  $string .= $file;
	}
      $string .= "</td><td style='text-align: right;'>\n";

      if (isset ($i['line']))
	{
	  $string .= $i['line'];
	}

      $string .= "</td></tr>\n";
      $res  .= $string;
    }

  $res .= "</table>\n";

  return $res;
}

function FCToolkitErrorHandler ($errno, $errstr, $errfile, $errline)
{
    global $ignore_errno;
    
    if(in_array($errno, $ignore_errno) && !DEBUG ) {
        return true;
        }
/*
    if ($errno != E_USER_ERROR && $errno != E_USER_WARNING && $errno != E_USER_NOTICE)
        return  false;
*/
    echo "<div class='php_error'>";
    switch ($errno)
            {
            case E_USER_ERROR:
                echo "<b>Application error:</b> [$errno] $errstr<br />\n";
                echo "  Fatal error on line $errline in file $errfile";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                echo "Stack trace:";
                echo GetStackTrace (0);
                echo "Aborting...<br />\n";
                exit(1);
                break;
                
            case E_USER_WARNING:
                echo "<b>Application warning:</b> [$errno] $errstr<br />\n";
                echo "Stack trace:";
                echo GetStackTrace (0);
                break;
                
            case E_USER_NOTICE:
                echo "<b>Application notice:</b> [$errno] $errstr<br />\n";
                echo "Stack trace:";
                echo GetStackTrace (0);
                break;
                
            default:
                echo "Unknown error type: [$errno] $errstr<br />\n";
                echo "Stack trace:";
                echo GetStackTrace (0);
                break;
            }
    echo "</div>";

    /* Don't execute PHP internal error handler */
    return true;

    }
set_error_handler("FCToolkitErrorHandler");
