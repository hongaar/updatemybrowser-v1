<?php

class BrowserCollection extends Ajde_Collection
{
	public function getJSON()
	{
		$includeKeys = array(
			'name',
			'current',
			'minimum',
			'update_url',
			'info_url'
		);
		$array = array();
		foreach($this as $browser) {
			$browserValues = array();
			foreach($includeKeys as $key) {
				$browserValues[$key] = $browser->get($key);
			}
			$array[$browser->shortname] = $browserValues;
		}
		return json_encode($array);
	}
}
