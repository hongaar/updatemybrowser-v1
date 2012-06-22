<?php

class Ajde_Document_Format_Js extends Ajde_Document
{
	public function render()
	{
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		Ajde::app()->getResponse()->addHeader('Content-type', 'text/javascript');
		Ajde::app()->getResponse()->addHeader('Cache-control', 'public');
		return parent::render();
	}
}