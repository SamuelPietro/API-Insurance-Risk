<?php

namespace control;

use model\Model;

require ('app\model\model.php');
class Control
{
    
    function error(){
        echo "<h3>Incorrect usage for this API!</h3>";
        echo "Do you want to use the <a href='post.php'>post form</a>?";
    }
    
    function data(){
        header('Content-Type: application/json');
        
        if(isset($_GET['json'])){
            $json = filter_input(INPUT_GET, 'json', FILTER_DEFAULT);
        }
        if(isset($_POST['json'])){
            $json = trim(filter_input(INPUT_POST, 'json', FILTER_DEFAULT));
        }
        $data = json_decode($json,true);
        
        
        $model = new \model\Model(
            $data['age'],
            $data['dependents'],
            $data['house']['ownership_status'],
            $data['income'],
            $data['marital_status'],
            $data['risk_questions'][0],
            $data['risk_questions'][1],
            $data['risk_questions'][2],
            $data['vehicle']['year']
        );
        
        
        
        print_r($model->securityRisk());

        
        

       
    }
    
}
