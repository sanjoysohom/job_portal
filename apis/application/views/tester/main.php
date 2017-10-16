<html>
    <head>
        <meta charset="utf-8" />
        <title>Trackbud Api Tester Version: <?php echo str_replace('_', '.', $this->config->item('test_api_ver')); ?></title>
        <style  type="text/css">
            a{text-decoration: none; color: #00F;}
        </style>
    </head>
    <body>
        <fieldset> 
            <legend style="font-weight: bold; font-size: 16px;">Index</legend>
            <p>
                Login : <a href="<?php echo $this->config->item('base_url').'tester/home/login/'; ?>">Register,Login,logout,forgot password,edit profile & Change Password</a>
            </p>
            <p>
                Friend : <a href="<?php echo $this->config->item('base_url').'tester/home/block/'; ?>">Block user,list,Add friend,friend details</a>
            </p>

            <p>
                Track : <a href="<?php echo $this->config->item('base_url').'tester/home/track/'; ?>">Check user login, track user position, update user position</a>
            </p>
            <p>
                Notification : <a href="<?php echo $this->config->item('base_url').'tester/home/notification/'; ?>">Notification list</a>
            </p>
           
        </fieldset>
    </body>
</html>  