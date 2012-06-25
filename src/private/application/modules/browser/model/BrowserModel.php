<?php

class BrowserModel extends Ajde_Model
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
	
	public function getMinimumMajor() {
		return (int) $this->minimum;
	}
	
	public function getMinimumMinor() {
		if (($pos = strpos($this->minimum, '.')) !== false) {
			return substr($this->minimum, $pos);
		}
		return false;
	}
}
