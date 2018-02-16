<?php

abstract Class AbstractPerson {
	
	protected $collection;
	protected $age;
	protected $name;
	protected $sex;
	protected $notes;

   	public function setCollection() {
		$this->collection = MongoHelper::createDBInstance();
	}
	public function getCollection () {
		return $this->collection;
	}

	public function setNewNote($noteText) {
        $note = HelperClass::makeNote($noteText);
        $this->notes[] = $note;
        return $note;
    }

    abstract public function setMongoId($mongoId);
	/* Method returns the mongoId for person instance.
	 */
	public function getMongoId() {
		return $this->mongoId;
	}

	abstract public function setAge();
	/* Method returns $this->age.
	 */
	public function getAge() {
		return $this->age;
	}

	abstract public function setSex();
	/* Method returns $this->sex.
	 */
	public function getSex() {					
		return $this->sex;
	}

	abstract public function setName();
	/* Method returns $this->name.
	 */
	public function getName() {
		return $this->name;
	}


}