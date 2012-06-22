<?php

abstract class AjdeX_Filter extends Ajde_Object_Standard
{		
	const FILTER_IS 	= ' = ';
	const FILTER_NOT 	= ' != ';
	const FILTER_LIKE 	= ' LIKE ';
	
	abstract public function prepare(AjdeX_Db_Table $table = null);
}