<fieldset>
    <legend>Check user login status:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/check_user_login_status/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
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