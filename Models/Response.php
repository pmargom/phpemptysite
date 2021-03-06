<?php

    class Response {
        var $totalRows;
        var $error;
        var $data;

        // constructor             
        function __construct($dataFromDb, $error) {
            if (!$dataFromDb) {
                $this->totalRows = 0;
                $this->error = $error;
                $this->data = null;
                return;
            }
            $this->totalRows = mysqli_num_rows($dataFromDb);
            $this->error = "";
            $this->data = $this->parseResults($dataFromDb);
        }

        function parseResults($result) {
            $jsonArray = [];
            if ($result) {
                $fields = $result->fetch_fields();
                
                while ($row = $result->fetch_assoc())
                {
                    $jsonItem = array();
                    foreach ($fields as $field) {
                        if ((strcmp($field->name, "pass") != 0) && (strcmp($field->name, "clave") != 0)) {
                            $jsonItem[$field->name] = $row[$field->name];
                        }
                    }                
                    array_push($jsonArray, $jsonItem);
                }
                $result->free();
            }
            return $jsonArray;
        }

        // destructor
        function __destruct() {}

        function getJSON() {
            $jsonArray = array('totalRows' => $this->totalRows);
            $jsonArray['error'] = $this->error;
            $jsonArray['data'] = $this->data;
            header('Content-Type: application/json');
            return json_encode($jsonArray);
        }
    }

?>