<?php

namespace DigitalStars;

class SimpleAPI {
    private $data = [];
    private $flag = 0;
    public $answer = [];
    public $module = '';

    public function __construct() {
        $this->data = $_POST + $_GET;

        if (!isset($this->data['module']))
            $this->error('missed module');
        else {
            $this->module = $this->data['module'];
            if(isset($this->data['json_data'])) {
                $this->data = json_decode($this->data['json_data'], true);
                if($this->data == null)
                    $this->error('json invalid');
            }
        }
    }

    public function __destruct() {
        header('Content-Type: application/json');
        exit(json_encode($this->answer));
    }

    public function error($text) {
        $this->answer['error'] = $text;
        exit();
    }

    public function params($params) {
        if(!$this->flag) {
            $this->flag = 1;
            if ($this->array_keys_exist($params))
                return $this->data;
            else
                $this->error('missed params');
        } else
            exit();
    }

    private function array_keys_exist($keys) {
        foreach ($keys as $key) {
            if ($key{0} == '?')
                continue;
            if (!array_key_exists($key, $this->data) | !isset($this->data[$key]))
                return false;
        }
        return true;
    }
}
