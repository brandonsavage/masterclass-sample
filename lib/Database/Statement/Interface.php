<?php

interface Database_Statement_Interface {
    public function fetch($fetch_type);

    public function fetchAll($fetch_type);

    public function execute(array $params = array());

    public function rowCount();
}