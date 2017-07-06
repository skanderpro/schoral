<?php

namespace Qubants\Scholar\Models;


class Configurations
{

	private $data = [];

	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	public function __get($name) {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}

		$trace = debug_backtrace();
		trigger_error(
			'Неопределенное свойство в __get(): ' . $name .
			' в файле ' . $trace[0]['file'] .
			' на строке ' . $trace[0]['line'],
			E_USER_NOTICE);
		return null;
	}

	public function getArray() {
		return $this->data;
	}

	public function __isset($name) {
		return isset($this->data[$name]);
	}

	public function __unset($name) {
		unset($this->data[$name]);
	}
}

