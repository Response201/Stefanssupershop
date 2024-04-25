<?php 

function showError($error){
    if($error){
        echo "<label class='input' style=\"border:none; font-weight: 100; font-size: 10px; text-align: left; margin: 0; \">
        $error
        </label>";}
        else{
            echo"<br />
            <br />";}
};


?>