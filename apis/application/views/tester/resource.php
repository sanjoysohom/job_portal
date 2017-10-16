<fieldset>
    <legend>Resource List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/resource_list/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>    
        <label for="page">Page :</label>
        <input type="text" name="page" value=""><br><br>        
        <label for="page_size">Page size:</label>
        <input type="text" name="page_size" value=""><br><br>   
        <label for="resource_type">Resource type:</label>
        <select name="resource_type">
            <option value="">Select</option>
            <option value="V">Video</option>
            <option value="I">Image</option>
            <option value="D">Documents</option>
            <option value="L">Link</option>
        </select>      
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Resource details using Resource id:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/resource_details/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key :</label>
        <input type="text" name="pass_key" value=""><br><br>  
        <label for="resource_id">Resource Id :</label>
        <input type="text" name="resource_id" value=""><br><br>  
        <label for="resource_type">Resource type :</label>
        <select name="resource_type">
            <option value="">Select</option>
            <option value="V">Video</option>
            <option value="I">Image</option>
            <option value="D">Documents</option>
            <option value="L">Link</option>
        </select>            
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>