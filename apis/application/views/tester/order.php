<fieldset>
    <legend>Order List:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/order_list/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="page">Page :</label>
        <input type="text" name="page" value=""><br><br>        
        <label for="page_size">Page size:</label>
        <input type="text" name="page_size" value=""><br><br>   
        <label for="pass_key">Pass key:</label>
        <input type="text" name="pass_key" value=""><br><br>   
        <p>
            <label>&nbsp;</label>
            <input type="submit" name="user_submit" value="Fetch"/>
        </p>
    </form>
</fieldset>
<fieldset>
    <legend>Order details using order id:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/order_details_using_order_id/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p>
            <label for="order_id">Order id:</label>
            <input type="text" name="order_id" value="">
        </p> 
        <p>
            <label for="pass_key">Pass key:</label>
            <input type="text" name="pass_key" value="">
        </p>  
        <p>
            <label>&nbsp;</label>
            <input type="submit" name="user_submit" value="Fetch"/>
        </p>
    </form>
</fieldset>