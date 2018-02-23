<?php

function debugmessage($ar)
{
    global $USER;
    if (!$USER->isAdmin()) return;
    echo "<pre>";
    print_r($ar);
    echo "</pre>";
}

function pre($obj,$f=true)
{

	if(!$f) {

		echo "<pre>";
		print_r($obj);
		echo "</pre>";

	} else {

		ob_start();
		?>

		----------------- <?=date("d.m.Y H:i:s",time());?> -----------------------------------
		<?print_r($obj);?>
		-----------------------------------------------------------------
		<?
		$content=ob_get_contents();
		ob_end_clean();
		file_put_contents($_SERVER["DOCUMENT_ROOT"]."/pre.log",$content,FILE_APPEND);

	}

}


function d($var, $die = false)
{
    global $USER;

    if(($USER instanceof CUser AND $USER->IsAdmin()) OR isset($_GET['debug']))
    {
        $trace = debug_backtrace();
        echo '<div style="display: block; position: relative; background-color: #ffffff; border: 1px solid #000000; padding: 5px; margin: 0; font-size: 12px; font-weight: 100;">';
        echo '<div style="display: block; position: relative; color: #808080; font-size: 12px !important; margin: 0; padding: 0;">from (<b style="font-size: 12px !important">'.$trace[0]['file'].'</b>) on line <b style="font-size: 12px !important">'.$trace[0]['line'].'</b></div><pre>';
        if(is_array($var)) print_r($var); else var_dump($var);
        echo '</pre></div>';

        if($die)
        {
            die();
        }
    }
}





