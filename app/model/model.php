<?php

namespace model;

class Model {
    private int $baseScore;
    private int $homeScore;
    private int $disabilityScore;
    private int $lifeScore;
    private int $autoScore;

    function __construct(
        int $age,
        int $dependents,
        String $ownership_status = null,
        int $income,
        String $marital_status,
        int $risk_questions_1,
        int $risk_questions_2,
        int $risk_questions_3,
        int $year = 0
    )
    {
        $this->age = $age;
        $this->dependents = $dependents;
        $this->ownership_status = $ownership_status;
        $this->income = $income;
        $this->marital_status = $marital_status;
        $this->risk_questions_1 = $risk_questions_1;
        $this->risk_questions_2 = $risk_questions_2;
        $this->risk_questions_3 = $risk_questions_3;
        $this->year = $year;
    }
    function baseScore(){
        //First, it calculates the base score by summing the answers from the risk questions, resulting in a number ranging from 0 to 3. Then, it applies the following rules to determine a risk score for each line of insurance.
        $baseScore = $this->risk_questions_1 + $this->risk_questions_2 + $this->risk_questions_3;
        
        //If the user is under 30 years old, deduct 2 risk points from all lines of insurance.
        if ($this->age < 30){$baseScore = $baseScore - 2;}
        
        //If she is between 30 and 40 years old, deduct 1.
        if ($this->age >= 30 && $this->age <= 40){$baseScore = $baseScore - 1;}

        //If her income is above $200k, deduct 1 risk point from all lines of insurance.
        if ($this->income > 200000){$baseScore = $baseScore - 1;}

        return $baseScore;
    }

    function autoScore(){
        $autoScore = $this->baseScore();
        
        //If the user's vehicle was produced in the last 5 years, add 1 risk point to that vehicle’s score.
        if($this->year > date("Y") - 5) {$autoScore = $autoScore + 1;}
        
        return $autoScore;

    }

    function disabilityScore(){
        $disabilityScore = $this->baseScore();

        //If the user's house is mortgaged, add 1 risk point to her disability score.
        if($this->ownership_status == "mortgaged") {$disabilityScore = $disabilityScore + 1;}

        //If the user has dependents, add 1 risk point to both the disability score.
        if($this->dependents > 0) {$disabilityScore = $disabilityScore + 1;}

        //If the user is married, remove 1 risk point from disability.
        if($this->marital_status == "married") {$disabilityScore = $disabilityScore - 1;}

        return $disabilityScore;

    }
    
    function homeScore(){
        $homeScore = $this->baseScore();

        //If the user's house is mortgaged, add 1 risk point to her home score.
        if($this->ownership_status == "mortgaged") {$homeScore = $homeScore + 1;}

        return $homeScore;
        
    }
    
    function lifeScore(){
        $lifeScore = $this->baseScore();

        //If the user has dependents, add 1 risk point to life score.
        if($this->dependents > 0) {$lifeScore = $lifeScore + 1;}

        //If the user is married, add 1 risk point to the life score .
        if($this->marital_status == "married") {$lifeScore = $lifeScore + 1;}

        return $lifeScore;
    }

    
    function securityRisk(){
        $array = [];
        
        //If the user doesn’t have income, vehicles or houses, she is ineligible for disability, auto, and home insurance, respectively.
        if($this->year <= 0){$array[] = "ineligible";}
        else{
            if($this->autoScore() <= 0 ){
                $array[] = "economic";
            }if($this->autoScore() >= 1 && $this->autoScore() <= 2){
                $array[] = "regular";
            }if($this->autoScore() >= 3){
                $array[] = "responsible";
            }
        }
        if($this->income <= 0){$array[] = "ineligible"; }
        elseif($this->age > 60){$array[] = "ineligible";}
        else{
            if($this->disabilityScore() <= 0 ){
                $array[] = "economic";
            }if($this->disabilityScore() >= 1 && $this->autoScore() <= 2){
                $array[] = "regular";
            }if($this->disabilityScore() >= 3){
                $array[] = "responsible";
            }
        }
        if($this->ownership_status = 0){$array[] = "ineligible";}
        else{
            if($this->homeScore() <= 0 ){
                $array[] = "economic";
            }if($this->homeScore() >= 1 && $this->homeScore() <= 2){
                $array[] = "regular";
            }if($this->homeScore() >= 3){
                $array[] = "responsible";
            }
        }
        
        //If the user is over 60 years old, she is ineligible for disability and life insurance.
        if($this->age > 60){$array[] = "ineligible";}  
        else{
            if($this->lifeScore() <= 0 ){
                $array[] = "economic";
            }if($this->lifeScore() >= 1 && $this->autoScore() <= 2){
                $array[] = "regular";
            }if($this->lifeScore() >= 3){
                $array[] ="responsible";
            }
        }
        

        $keys = array("auto","disability","home","life");
        $array = array_combine($keys, $array);
        return json_encode($array, JSON_PRETTY_PRINT);
        
    }


}