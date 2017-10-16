<fieldset>
    <legend>Hospital List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/doctor_assigned_hospital_list_under_distributor_salereps/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass Key :</label>
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
    <legend>Hospital List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/doctor_list_under_distributor_salereps/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass Key :</label>
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