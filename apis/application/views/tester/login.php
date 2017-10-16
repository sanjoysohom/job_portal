<fieldset>
    <legend>Access token:</legend>
    <form name="login" action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/token/' ?>" method="post">
        <label for="full_name">client_credentials:</label>
        <input type="text" name="grant_type" value="client_credentials"><br><br>
        <label for="mobile">client_id :</label>
        <input type="mobile" name="client_id" value="9d911a9a00ef11e48aff0019d114582"><br><br>
        <label for="login_pwd">Client Secret: </label>
        <input type="text" name="client_secret" value="463ceaeab4db11e3aa520019d119645"><br><br>
        
        <input type="submit" value="Submit">
    </form> 
</fieldset>

<fieldset>
    <legend>Activate user:</legend>
    <form name="login" action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/activate_user/' ?>" method="post">
        <label for="passcode">Passcode :</label>
        <input type="text" name="passcode" value=""><br><br>
        <input type="submit" value="Submit">
    </form> 
</fieldset>

<fieldset>
    <legend>Log in:</legend>
    <form name="login" action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/login/' ?>" method="post">
        <label for="username">Email/phone :</label>
        <input type="text" name="username" value=""><br><br>
        <label for="username">Password :</label>
        <input type="password" name="password" value=""><br><br>
        <label for="gps_coordinate">Gps Coordinate : </label>
        <input type="text" name="gps_coordinate" value="22.5922096 88.411333"><br><br>
        <label for="appname">App Name :</label>
        <input type="text" name="appname" value="Medicita"><br><br>
        <label for="device_uid">Device Uid :</label>
        <input type="text" name="device_uid" value="ssss45675gvbhnghmgm"><br><br>
        <label for="device_token">Device Token :</label>
        <input type="text" name="device_token" value="dfghrfhj456547657tghjghmhjmh"><br><br>
        <label for="device_name">Device Name :</label>
        <input type="text" name="device_name" value="Sony"><br><br>
        <label for="device_model">Device Model :</label>
        <input type="text" name="device_model" value="Xperia L"><br><br>
        <label for="device_version">Device Version :</label>
        <input type="text" name="device_version" value="c2104"><br><br>
        <label for="device_os">Device OS :</label>
        <input type="text" name="device_os" value="iOS"><br><br>
        <label for="push_mode">Push Mode :</label>
        <input type="text" name="push_mode" value="S"><br><br>
        <label for="appversion">App Version :</label>
        <input type="text" name="appversion" value="1.0.0"><br><br>  
        <input type="submit" value="Submit">
    </form> 
</fieldset>

<fieldset>
    <legend>Log Out:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/logout/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
    <p>
        <label for="pass_key">Pass Key :</label>
        <input type="text" name="pass_key" value="">
    </p>
    <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>


<fieldset>
    <legend>Forgot Password:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/forgot_password/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="login_id">Email:</label>
        <input type="text" name="data" value=""><br><br>         
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Verify Passcode:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/verify_passcode/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="passcode">Pass code :</label>
        <input type="text" name="passcode" value=""><br><br>         
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Change Password:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/change_password/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="passcode">Pass code :</label>
        <input type="text" name="passcode" value=""><br><br>   
        <label for="password">Password :</label>
        <input type="password" name="password" value=""><br><br>  
        <label for="confirm_password">Confirm Password :</label>
        <input type="password" name="confirm_password" value=""><br><br>          
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Edit Profile:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/edit_profile/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>   
        <label for="mobile">Mobile :</label>
        <input type="text" name="mobile" value=""><br><br>  
        <label for="email">Email :</label>
        <input type="text" name="email" value=""><br><br> 
        <label for="full_name">Full name :</label>
        <input type="text" name="full_name" value=""><br><br> 
        <label for="profile_picture">Profile picture:</label>
        <input type="text" name="profile_picture" value=""><br><br> 
        <label for="global_tracking">Global tracking :</label>
        <select name="global_tracking">
            <option value="">Select</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Update batch:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/update_batch/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass Key :</label>
        <input type="text" name="pass_key" value=""><br><br>   
        <label for="isappactive">Is app active :</label>
        <input type="text" name="isappactive" value=""><br><br>          
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Fetch user details:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/user_details/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>         
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Change Password after login:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/change_password_after_login/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>
        <label for="current_password">Password :</label>
        <input type="password" name="current_password" value=""><br><br>     
        <label for="new_password">New Password :</label>
        <input type="password" name="new_password" value=""><br><br>  
        <label for="confirm_password">Confirm Password :</label>
        <input type="password" name="confirm_password" value=""><br><br>          
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>


<fieldset>
    <legend>Delete profile picture:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/delete_profile_picture/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>  
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>
