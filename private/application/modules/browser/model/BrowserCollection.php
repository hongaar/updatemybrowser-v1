<?php

class BrowserCollection extends Ajde_Collection
{
	public function getJSON()
	{
		$array = array();
		foreach($this as $browser) {
			$array[$browser->shortname] = $browser->getValues();
		}
		return json_encode($array);
	}
}
