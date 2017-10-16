<fieldset>
    <legend>Lead List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/lead_list/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="page">Page :</label>
        <input type="text" name="page" value=""><br><br>        
        <label for="page_size">Page size:</label>
        <input type="text" name="page_size" value=""><br><br>       
        <label for="search_query">Search query:</label>
        <input type="text" name="search_query" value=""><br><br>        
        <label for="filter_by">Filter by:</label>        
        <input type="text" name="filter_by" value='{"lead_status":"OP","addition_timestamp":"2015-12-08 10:44:24"}'><br><br>  
        <label for="sort_by">Sort by:</label>
        <select name="order_by">
            <option value="">Select</option>
            <option value="addition_timestamp">Date</option>
            <option value="title">Title</option>
            <option value="contact_f_name">Contact</option>
            <option value="company_name">Company</option>
            <option value="lead_status">Status</option>
        </select><br><br>
        <label for="sort_type">Sort type:</label>
        <select name="order_type">
            <option value="">Select</option>
            <option value="asc">ASC</option>
            <option value="desc">DESC</option>
        </select><br><br> 
        <label for="pass_key">Pass key:</label>
        <input type="text" name="pass_key" value=""><br><br>   
        <p>
            <label>&nbsp;</label>
            <input type="submit" name="user_submit" value="Fetch"/>
        </p>
    </form>
</fieldset>

<fieldset>
    <legend>Lead details using lead id:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/lead_details_using_lead_id/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="lead_id">Lead Id :</label>
        <input type="text" name="lead_id" value=""><br><br>       
        <label for="pass_key">Pass key:</label>
        <input type="text" name="pass_key" value=""><br><br>     
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Lead add:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/add_lead/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="pass_key">Pass key:</label>
        <input type="text" name="pass_key" value=""><br><br>   
         <p>
            <label>title</label>
            <input type="text" name="title" value="" />
        </p>
         <p>
            <label>description</label>
            <input type="text" name="description" value="" />
        </p> 
        <p>
            <label>contact_f_name</label>
            <input type="text" name="contact_f_name" value="" />
        </p> 
        <p>
            <label>contact_l_name</label>
            <input type="text" name="contact_l_name" value="" />
        </p> 
        <p>
            <label>contact_type</label>
            <input type="text" name="contact_type" value="" />
        </p> 
        <p>
            <label>contact_email</label>
            <input type="text" name="contact_email" value="" />
        </p> 
        <p>
            <label>contact_mobile</label>
            <input type="text" name="contact_mobile" value="" />
        </p> 
        <p>
            <label>company_name</label>
            <input type="text" name="company_name" value="" />
        </p> 
        <p>
            <label>street1</label>
            <input type="text" name="street1" value="" />
        </p> 

        <p>
            <label>street2</label>
            <input type="text" name="street2" value="" />
        </p> 
        <p>
            <label>street3</label>
            <input type="text" name="street3" value="" />
        </p> 
        <p>
            <label>city</label>
            <input type="text" name="city" value="" />
        </p> 
        <p>
            <label>state</label>
            <input type="text" name="state" value="" />
        </p> 
        <p>
            <label>country</label>
            <input type="text" name="country" value="" />
        </p> 
        <p>
            <label>zipcode</label>
            <input type="text" name="zipcode" value="" />
        </p> 
        <p>
            <label>phone</label>
            <input type="text" name="phone" value="" />
        </p> 
         <p>
            <label>fax</label>
            <input type="text" name="fax" value="" />
        </p> 
         <p>
            <label>email</label>
            <input type="text" name="email" value="" />
        </p> 
         <p>
            <label>lead_category</label>
            <select name="lead_category">
                <option value="">Select</option>
                <option value="C">Cold</option>
                <option value="W">Warm</option>
                <option value="H">Hot</option>
            </select>
        </p> 
         <p>
            <label>lead_notes</label>
            <input type="text" name="lead_notes" value="" />
        </p> 
         <p>
            <label>notes_by</label>
            <input type="text" name="notes_by" value="" />
        </p> 
        
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Update lead:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/update_lead_status_category/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p>
            <label for="lead_id">Lead Id :</label>
            <input type="text" name="lead_id" value="">
        </p>
         <p>     
            <label for="pass_key">Pass key:</label>
            <input type="text" name="pass_key" value="">
        </p>
         <p>
            <label>Lead status</label>
            <select name="lead_status">
                <option value="">Select</option>
                <option value="OP">Open</option>
                <option value="CW">Closed Won</option>
                <option value="CL">Closed Lost</option>
            </select>
        </p>     
        <p>
            <label>lead_category</label>
            <select name="lead_category">
                <option value="">Select</option>
                <option value="C">Cold</option>
                <option value="W">Warm</option>
                <option value="H">Hot</option>
            </select>
        </p>  
        <p>
            <label>Lead notes</label>
            <input type="text" name="lead_notes" value="">
        </p> 
        <p>
            <label>&nbsp;</label>
            <input type="submit" name="user_submit" value="Fetch" />
        </p>
    </form>
</fieldset>


<fieldset>
    <legend>get unread lead:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/lead_get_unread/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
             
        <label for="pass_key">Pass key:</label>
        <input type="text" name="pass_key" value=""><br><br>     
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>

<fieldset>
    <legend>Update lead read status:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/update_lead_read_status/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
         <p>     
            <label for="pass_key">Pass key:</label>
            <input type="text" name="pass_key" value="">
        </p>
        <p>
            <label>&nbsp;</label>
            <input type="submit" name="user_submit" value="Fetch" />
        </p>
    </form>
</fieldset>

<fieldset>
    <legend>Lead note List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/fetch_lead_notes/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">  
        <label for="lead_id">Lead id:</label>
        <input type="text" name="lead_id" value=""><br><br>   
        <label for="pass_key">Pass key:</label>
        <input type="text" name="pass_key" value=""><br><br>   
        <p>
            <label>&nbsp;</label>
            <input type="submit" name="user_submit" value="Fetch"/>
        </p>
    </form>
</fieldset>


<fieldset>
    <legend>Add Lead note:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/add_lead_notes/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">  
        <label for="fk_lead_id">Lead id:</label>
        <input type="text" name="fk_lead_id" value=""><br><br> 
        <label for="notes">Notes:</label>
        <input type="text" name="notes" value=""><br><br>    
        <label for="pass_key">Pass key:</label>
        <input type="text" name="pass_key" value=""><br><br>   
        <p>
            <label>&nbsp;</label>
            <input type="submit" name="user_submit" value="Fetch"/>
        </p>
    </form>
</fieldset>