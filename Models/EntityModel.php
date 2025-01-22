<?php


namespace Models;

interface EntityModel {
    public function all();
    public function create($recordFactoryType);
    public function delete();
    public function recordFactory_fixed();
    public function recordFactory_describe();
    public function count($times, $method);
    public function execute();
}