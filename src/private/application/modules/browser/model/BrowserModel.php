<?php

class BrowserModel extends AjdeX_Model
{
	public function getCurrentMajor() {
		return (int) $this->current;
	}
	
	public function getCurrentMinor() {
		if (($pos = strpos($this->current, '.')) !== false) {
			return substr($this->current, $pos);
		}
		return false;
	}
}
