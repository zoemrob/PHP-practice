<?php

class Person {

	public $personHeight;
	public $personWeight;
	public $personGender;

	public function __construct($height, $weight, $gender) {
		$this->personHeight = $height;
		$this->personWeight = $weight;
		$this->personGender = $gender;
	}

	public function echoPerson() {
		echo "This person is " . $this->personHeight .", " . $this->personWeight . ", and they are " . $this->personGender . ".\n";
	}

}

$Zoe = new Person("6'2", "140 lbs.", "male");
$Zoe->echoPerson();

abstract class Human {

	protected $hgt;
	protected $wgt;
	protected $sex;

	abstract protected function setHgt($val);
	abstract protected function showHgt();
	abstract protected function setSex();
}

class Giant extends Human {


	public function setHgt($val) {
		$this->hgt = $val * 2;
	}

	public function showHgt() {
		echo "Wow, Giants are huge! This one is {$this->hgt} ft. tall!\n";
	}

	protected function setSex() {
		$this->sex = "male";
	}
}

$giant = new Giant();
$giant->setHgt(6);
$giant->showHgt();

class Male extends Human {

	function __construct() {
		$this->setHgt();
		$this->setSex();
	}

	public function setHgt($val = "5'11") {
		$this->hgt = $val;
	}

	public function showHgt() {
		if ($this->hgt == "5'11") {
			echo "The average {$this->sex} is {$this->hgt}.\n";
		} else if ($this->hgt > "5'11") {
			echo "But same {$this->sex}s can be {$this->hgt} or taller.\n";
		}
	}

	public function setSex() {
		$this->sex = "male";
	}
}

$guy = new Male();
$guy->showHgt();
$guy->setHgt("6'2");
$guy->showHgt();

class Female extends Male {


	public function setHgt($val = "5'6") {
		$this->hgt = $val;
	}

	public function setSex() {
		$this->sex = "female";
	}


	public function showHgt() {
		if ($this->hgt == "5'6") {
			echo "The average {$this->sex} is {$this->hgt}.\n";
		} else if ($this->hgt < "5'6") {
			echo "But some {$this->sex}s can be as short as {$this->hgt}!\n";
		}
	}
}

$girl = new Female();
$girl->showHgt();
$girl->setHgt("4'11");
$girl->showHgt();




