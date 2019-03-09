<?php
class Hour
{
    private $student;
    private $time;
    private $duration;
    private $updated;
    private $flag;
    private $cancelBy;

    function __construct($student = "", $time = 0, $duration = -1) {
        $this->student = $student;
        $this->time = $time;
        $this->duration = $duration;
    }

    public function setStudent($student) {
        $this->student = $student;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
    }

    public function setFlag($flag) {
        $this->flag = $flag;
    }

    public function setUpdated($updated) {
        $this->updated = $updated;
    }

    public function setCancelBy($name) {
        $this->cancelBy = $name;
    }

    public function getStudent() {
        return $this->student;
    }

    public function getTime() {
        return $this->time;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function getFlag() {
        return $this->flag;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function getCancelBy() {
        return $this->cancelBy;
    }
}
