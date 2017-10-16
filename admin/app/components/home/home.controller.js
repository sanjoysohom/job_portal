angular
	.module('job_portal')
	.controller('homeController', ["$scope", "$http", "$window", "$q", 'ajaxService', 'CONFIG', '$location', '$timeout', '$cookies', 'helper', function($scope, $http, $window, $q, ajaxService, CONFIG, $location, $timeout, $cookies, helper){
     	// Perform the login action when the user submits the login form
		$scope.doLogin = function(loginData) { 
			//alert(CONFIG.ApiUrl);
			//alert(loginData.username) ; 
			ajaxService.ApiCall(loginData, CONFIG.ApiUrl+'admin/logIn', $scope.loginUserSuccess, $scope.loginUserError, 'post');
		}
		$scope.loginUserSuccess = function(result,status) {
			//alert(result+status);
		    if(status == 200) {
		    	// Setting a cookie
		    	//alert("hii");
		    	$cookies.put('admin_user_id', result.raws.data.admin_user_id,{'path': '/'});
		    	$cookies.put('pass_key', result.raws.data.pass_key,{'path': '/'});
		        $location.path('dashboard/welcome');
		    }		       
		}
		//login error function
		$scope.loginUserError = function(result) {
            $scope.errorMessage = result.raws.error_message;
            $timeout(function() {
        		$scope.successMessage = '';
                $scope.errorMessage = '';
            }, CONFIG.TimeOut);
		}
		// Send verification-code to the email id
		$scope.doforgetPassword = function(forgetPasswordData){ 
			ajaxService.ApiCall(forgetPasswordData, CONFIG.ApiUrl+'admin/forgetPassword', $scope.forgetPasswordSuccess, $scope.forgetPasswordError, 'post');
		}
		//forgetPassword success function
		$scope.forgetPasswordSuccess = function(result,status) {
		    if(status == 200) {
            	$scope.admin_user_id = result.raws.data.admin_user_id;
            	$scope.successMessage = result.raws.success_message;
            	$scope.errorMessage = result.raws.error_message;
		        $location.path('/home/verifyPasscode');
		    }		       
		}
		//forgetPassword error function
		$scope.forgetPasswordError = function(result) {
            $scope.errorMessage = result.raws.error_message;
            $timeout(function() {
        		$scope.successMessage = '';
                $scope.errorMessage = '';
            }, CONFIG.TimeOut);
		}
	}]);