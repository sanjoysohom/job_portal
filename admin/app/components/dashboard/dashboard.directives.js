angular.module('angular_practice')
    /*
     * --------------------------------------------------------------------------
     * @ Directive Name           : dashboardHeader()
     * @ Added Date               : 14-04-2016
     * @ Added By                 : Subhankar
     * -----------------------------------------------------------------
     * @ Description              : admin dashboard header is managed from here
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 14-04-2016
     * @ Modified By              : Subhankar
     * 
     */



    .directive('dashboardHeader', function() {
        return {
        	controllerAs : 'dh',
        	controller : function($scope, $timeout, CONFIG, ajaxService, $location, $cookies){
        		var dh = this;
                
                // Retrieving a cookie
                var admin_user_id   = $cookies.get('admin_user_id');
                var pass_key        = $cookies.get('pass_key');
                var param = {};
                param.admin_user_id = admin_user_id;
                param.pass_key      = pass_key;

                dh.logout = function(){
                    ajaxService.ApiCall(param, CONFIG.ApiUrl+'admin/logOut', dh.logoutSuccess, dh.logoutError, 'post');                   
                }
                //login success function
                dh.logoutSuccess = function(result,status){
                    if(status == 200){

                        // Removing a cookie
                        $cookies.remove('admin_user_id');
                        $cookies.remove('pass_key');

                        //console.log($cookies.getAll());

                        $scope.successMessage = result.raws.success_message;
                        //$scope.errorMessage = result.raws.error_message;
                        $location.path('/home/login');
                    }
                }                
                //login error function
                dh.logoutError = function(result){
                    $scope.errorMessage = result.raws.error_message;
                    $timeout(function() {
                        $scope.errorMessage = '';
                        $scope.successMessage = '';
                    }, CONFIG.TimeOut);
                }



				return dh;
        	},
            templateUrl: 'app/components/dashboard/views/dashboard.header.html'
        };
    })

    /*
     * --------------------------------------------------------------------------
     * @ Directive Name           : dashboardBreadcrumb()
     * @ Added Date               : 14-04-2016
     * @ Added By                 : Subhankar
     * -----------------------------------------------------------------
     * @ Description              : admin dashboard header is managed from here
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 14-04-2016
     * @ Modified By              : Subhankar
     * 
     */
    .directive('dashboardBreadcrumb', function() {
        return {
            controllerAs : 'dbc',
            controller : function($scope, $timeout, CONFIG, ajaxService, $location, $cookies){
                var dbc = this;
                return dbc;
            },
            templateUrl: 'app/components/dashboard/views/dashboard.breadcrumb.html'
        };
    })


    .directive('dashboardPageTitle', function() {
        return {
            controllerAs : 'dpt',
            controller : function($scope, $timeout, CONFIG, ajaxService, $location, $cookies){
                var dpt = this;
                return dpt;
            },

            templateUrl: 'app/components/dashboard/views/dashboard.pageTitle.html'
        };
    })

    .directive('dashboardRightSection',function(){
     return {
    
    templateUrl: 'app/components/dashboard/views/dashboard.rightSection.html'

     };
    })


.directive('dashboardLeftSection',function(){
     return {
    templateUrl: 'app/components/dashboard/views/dashboard.leftSection.html'

     };
    });