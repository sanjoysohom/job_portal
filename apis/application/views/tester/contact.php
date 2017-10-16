<fieldset>
    <legend>Contact:</legend>
    <form action="<?php echo base_url().'api_'.$this->config->item('test_api_ver').'/user/contact/'; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <label for="name">Name :</label>
        <input type="text" name="name" value=""><br><br>    
        <label for="title">Title :</label>
        <input type="text" name="title" value=""><br><br>        
        <label for="company">Company:</label>
        <input type="text" name="company" value=""><br><br>   
        <label for="phone">Phone:</label>
        <input type="text" name="phone" value=""><br><br>   
        <label for="email_id">Email id:</label>
        <input type="text" name="email_id" value=""><br><br>  
        <label for="comments">Comments:</label>
        <textarea name="comments"></textarea><br><br>           
     <p>
        <label>&nbsp;</label>
        <input type="submit" name="user_submit" value="Fetch" />
    </p>
    </form>
</fieldset>