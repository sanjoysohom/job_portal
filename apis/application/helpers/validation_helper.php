<?php

    //validation functions

    //validate email id
    function validateEmail($email_id)
    {
        if (filter_var($email_id, FILTER_VALIDATE_EMAIL))
        {
                return 1;
        }else{
                return 0;
        }
    }
	



?>