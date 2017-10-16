<fieldset>
    <legend>Block user List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/block_user_list/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
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
    <legend>Block user:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/block_user/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
        <label for="fk_blocked_user_id">Blocked user id:</label>
        <input type="text" name="fk_blocked_user_id" value=""><br><br>      
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Unblock user:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/unblock_user/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
        <label for="fk_blocked_user_id">Blocked user id:</label>
        <input type="text" name="fk_blocked_user_id" value=""><br><br>      
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>


<fieldset>
    <legend>Track me:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/track_me/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
        <label for="fk_friend_user_id">Friend user id:</label>
        <input type="text" name="fk_friend_user_id" value=""><br><br>  
        <label for="friend_can_track_me">Friend can track me:</label>
        <select name="friend_can_track_me">
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
    <legend>Add friend:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/add_friend/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
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
    <legend>Remove friend:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/remove_friend/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
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
    <legend>Friend details:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/friend_details/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
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
    <legend>Friends List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/friend_list/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>        
        <label for="page">Page :</label>
        <input type="text" name="page" value=""><br><br>        
        <label for="page_size">Page size:</label>
        <input type="text" name="page_size" value=""><br><br>  
        <label for="search_by_phone_email">Search by phone email:</label>
        <input type="text" name="search_by_phone_email" value=""><br><br>  
            
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Search Friends List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/search_friend_list/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
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