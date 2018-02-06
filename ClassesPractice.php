<?php

class Person {
	// base properties of person
	public $hgt;
	public $wgt;
	public $sex;
	public $personName;

	//must be constructed with the following parameters, by default a name is not needed.
	public function __construct($height, $weight, $gender, $name = null) {
		$this->hgt = $height;
		$this->wgt = $weight;
		$this->sex = $gender;
		$this->personName = $name;
	}

	/* If the person is constructed with a $name, this function prints their name with the correct pronoun.
	 * If the person is constructed without passing a $name arg, this method prints a gender nuetral pronoun.
	 */
	public function echoPerson() {

		$pronoun = $this->sex == "male" ? "he" : "she";

		if (!is_null($this->personName)) {

			echo $this->personName . " is " . $this->hgt .", " . $this->wgt . ", and " . $pronoun . " is " . $this->sex . ".\n";
		} else {
			echo "This person is " . $this->hgt .", " . $this->wgt . ", and they are " . $this->sex . ".\n";
		}
	}

}

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





