<?php
require('HelperClass.php');
require('MongoHelper.php');
/*
	Provides the scaffolding for BasePerson and NewEntry
*/
abstract Class AbstractPerson {
	
	protected $collection;
	protected $age;
	protected $name;
	protected $sex;
	protected $notes;

   	protected function setCollection() {
		$this->collection = MongoHelper::createDBInstance();
	}
	protected function getCollection () {
		return $this->collection;
	}

	protected function setNewNote($note) {
        $this->notes[] = $note;
    }

    abstract protected function setMongoId($mongoId);
	/* Method returns the mongoId for person instance.
	 */
	protected function getMongoId() {
		return $this->mongoId;
	}

	abstract protected function setAge();
	/* Method returns $this->age.
	 */
	protected function getAge() {
		return $this->age;
	}

	abstract protected function setSex();
	/* Method returns $this->sex.
	 */
	protected function getSex() {					
		return $this->sex;
	}

	abstract protected function setName();
	/* Method returns $this->name.
	 */
	protected function getName() {
		return $this->name;
	}


}