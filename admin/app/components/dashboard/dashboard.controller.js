angular
	.module('job_portal')
	.controller('dashboardController',["$scope", 'ajaxService', 'CONFIG', '$location', '$timeout', '$cookies', '$state', "helper", "$rootScope",'$stateParams','$window',function($scope,ajaxService,CONFIG,$location,$timeout,$cookies, $state, helper, $rootScope, $stateParams,$window){
		$scope.ChangePassword = {};
		
   $scope.doChangePassword =function(ChangePassword){
			
        var param = {};
        var admin_user_id   	= $cookies.get('admin_user_id');
		param.old_password 		= ChangePassword.old_password;
		param.new_password 		= ChangePassword.new_password;
		param.confirm_password 	= ChangePassword.confirm_password;
		param.admin_user_id 	= admin_user_id;
		param.pass_key 	= $cookies.get('pass_key');

		ajaxService.ApiCall(param,CONFIG.ApiUrl+'admin/ChangePassword',
        $scope.ChangePasswordSuccess, $scope.ChangePasswordError,'post');	
   }

		//updateDegreeDetail success function
		$scope.ChangePasswordSuccess = function(result,status) 
		{
		    if(status == 200) 
		    {
		    	$window.scrollTo(0, 100);
                $scope.successMessage = result.raws.success_message;
                $scope.clearMessage();
                $timeout(function() {
		        	$location.path('dashboard/employee/list');
		        }, CONFIG.TimeOut);
		    }
		}

		//updateDegreeDetail error function
		$scope.ChangePasswordError = function(result) 
		{
            $scope.errorMessage = result.raws.error_message;
            $scope.clearMessage();
		}
		
		$scope.clearMessage = function()
		{
			$timeout(function() 
			{
        		$scope.successMessage = '';
                $scope.errorMessage = '';
            }, CONFIG.TimeOut);
		}

		
	}]);