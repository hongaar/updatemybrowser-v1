<?php

interface Ajde_Document_Format_Processor
{
	public static function preProcess(Ajde_Layout $layout);	
	public static function postProcess(Ajde_Layout $layout);
}