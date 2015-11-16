<?php
class DataWrapper {
    private $data;

    function __construct($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function jsonEncode() {
        return json_encode($this->data);
    }
}