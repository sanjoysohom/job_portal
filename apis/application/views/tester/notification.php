<fieldset>
    <legend>Notification List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/notification_list/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>   
        <label for="page">Page :</label>
        <input type="text" name="page" value=""><br><br>        
        <label for="page_size">Page size:</label>
        <input type="text" name="page_size" value=""><br><br>      
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Send Notification:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/send_push_notification/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>  
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Accept friend request:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/accept_friend_request/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
        <label for="fk_friend_user_id">Friend user id:</label>
        <input type="text" name="fk_friend_user_id" value=""><br><br> 
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Deny friend request:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/deny_friend_request/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
        <label for="fk_friend_user_id">Friend user id:</label>
        <input type="text" name="fk_friend_user_id" value=""><br><br> 
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Withdraw friend request:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/withdraw_friend_request/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
        <label for="fk_other_user_id">Other user id:</label>
        <input type="text" name="fk_other_user_id" value=""><br><br> 
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>


<fieldset>
    <legend>Emergency track request:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/emergency_track/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Emergency track request:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/fetch_new_notification/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Update batch:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/update_batch/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>     
        <label for="pass_key">App active :</label> 
        <select name="isappactive">
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
    <legend>Update current postion send push:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/update_current_postion_send_push/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>    
        <label for="fk_friend_user_id">Friend user id :</label>
        <input type="text" name="fk_friend_user_id" value=""><br><br>     
        <label for="lat">lat :</label>
        <input type="text" name="lat" value=""><br><br>    
        <label for="long">long :</label>
        <input type="text" name="long" value=""><br><br>    
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Check user login status:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/check_user_login_status/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>    
        <label for="fk_friend_user_id">Friend user id :</label>
        <input type="text" name="fk_friend_user_id" value=""><br><br>   
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>


