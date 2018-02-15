<?php

Class AbstractPerson {
	
	protected $collection;
	protected $mongoId;
	protected $personDocument;
	protected $age;
	protected $name;
	protected $sex;
	protected $notes = [];

	public function getCollection () {
		return $this->collection;
	}

	public function setNewNote($noteText) {
        $note = HelperClass::makeNote($noteText);
        $this->notes[] = $note;
        return $note;
    }

   	public function setCollection() {
		$this->collection = MongoHelper::createDBInstance();
	}

	/* Method returns the mongoId for person instance.
	 */
	public function getMongoId() {
		return $this->mongoId;
	}

	abstract public function setAge();

	abstract public function __construct();


}