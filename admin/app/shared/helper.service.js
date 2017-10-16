angular.module('helperServices', [])
    .service('helper', ['$http', '$rootScope', 'CONFIG', 'oauth', 'ajaxService', '$location', '$timeout', '$cookies', function ($http, $rootScope, CONFIG, oauth, ajaxService, $location, $timeout, $cookies) {
        
        $rootScope.allProfession    = {};
        $rootScope.allPaymentType   = {};
        //$rootScope.productDetail    = {};
	    /** --------------------------------------------------------------------------
	     * @ Service Name             : checkUserAuthentication()
	     * @ Added Date               : 14-04-2016
	     * @ Added By                 : Subhankar
	     * -----------------------------------------------------------------
	     * @ Description              : user authentication is checked from here
	     * -----------------------------------------------------------------
	     * @ return                   : array
	     * -----------------------------------------------------------------
	     * @ Modified Date            : 14-04-2016
	     * @ Modified By              : Subhankar
	     * 
	     */
		this.checkUserAuthentication = function(type){
            // Retrieving a cookie
            var admin_user_id   = $cookies.get('admin_user_id');
            var pass_key        = $cookies.get('pass_key');
            var param = {};
            param.admin_user_id = admin_user_id;
            param.pass_key      = pass_key;


            //Call API check_user_authentication
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'admin/checkUserAuthentication', userAuthenticationSuccess, userAuthenticationError, 'post'); 
            
            //User Authentication success function
    		function userAuthenticationSuccess(result){
                $rootScope.userDetails = result.raws.data;
                if(type == 'home'){
                    $location.path('dashboard/welcome');
                }
    		}
            
            //User Authentication error function
            function userAuthenticationError(result){
                //$rootScope.errorMessage = result.raws.error_message;
                $timeout(function() {
                    $rootScope.errorMessage = '';
                }, CONFIG.TimeOut);
                $location.path('/home/login');
            }

    	}

        /** --------------------------------------------------------------------------
         * @ Service Name             : unAuthenticate()
         * @ Added Date               : 16-09-2016
         * @ Added By                 : Piyalee
         * -----------------------------------------------------------------
         * @ Description              : 
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 16-09-2016
         * @ Modified By              : Piyalee
         * 
        */
        this.unAuthenticate = function()
        {
            // Removing a cookie
            $cookies.remove('admin_user_id');
            $cookies.remove('pass_key');
            $rootScope.errorMessage = 'You are not logged in. Please log in and try again.';
            $location.path('/home/login');
        }

        /** --------------------------------------------------------------------------
         * @ Service Name             : getAllProfession()
         * @ Added Date               : 14-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : 
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 14-04-2016
         * @ Modified By              : Subhankar
         * 
        */
        this.getAllProfession = function(){
            var param = {};

            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'admin/getAllProfession', getAllProfessionSuccess, getAllProfessionError, 'post'); 
            
            //Get all profession success function
            function getAllProfessionSuccess(result){
                $rootScope.allProfession = result.raws.data;  
            }
            
            //Get all profession error function
            function getAllProfessionError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }

        /** --------------------------------------------------------------------------
         * @ Service Name             : getAllPaymentType()
         * @ Added Date               : 14-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : 
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 14-04-2016
         * @ Modified By              : Subhankar
         * 
        */
        this.getAllPaymentType = function(){
            var param = {};

            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'admin/getAllPaymentType', getAllPaymentTypeSuccess, getAllPaymentTypeError, 'post'); 
            
            //Get all profession success function
            function getAllPaymentTypeSuccess(result){
                $rootScope.allPaymentType = result.raws.data;  
            }
            
            //Get all profession error function
            function getAllPaymentTypeError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }        


        /** --------------------------------------------------------------------------
         * @ Service Name             : oneTimeCalcInput / emiCalcInput()
         * @ Added Date               : 14-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : 
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 05-09-2016
         * @ Modified By              : Subhankar
         * 
        */
        this.oneTimeCalcInput = function(){
            var param = {};
            param.air = ($rootScope.productDetail.payment_interest.input_air != '') ? $rootScope.productDetail.payment_interest.input_air : 0;

            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/oneTimeCalcInput', oneTimeCalcInputSuccess, oneTimeCalcInputError, 'post');
            
            //Get all profession success function
            function oneTimeCalcInputSuccess(result){
                $rootScope.productDetail.payment_interest.calc_mir = result.raws.data.mir;  
            }
            
            //Get all profession error function
            function oneTimeCalcInputError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        } 

        this.oneTimeCalcDisbursement = function(){
            var param = {};
            param.principal  = ($rootScope.productDetail.payment_interest.input_principle != '') ? $rootScope.productDetail.payment_interest.input_principle : 0;
            param.lpfp = ($rootScope.productDetail.payment_interest.input_lpfp != '') ? $rootScope.productDetail.payment_interest.input_lpfp : 0;
            param.ufp  = ($rootScope.productDetail.payment_interest.input_ufp != '') ? $rootScope.productDetail.payment_interest.input_ufp : 0;
            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/oneTimeCalcDisbursement', oneTimeCalcDisbursementSuccess, oneTimeCalcDisbursementError, 'post');
            
            //Get all profession success function
            function oneTimeCalcDisbursementSuccess(result){
                $rootScope.productDetail.payment_interest.calc_arl      = result.raws.data.arl;  
                $rootScope.productDetail.payment_interest.calc_lpfa     = result.raws.data.lpfa; 
                $rootScope.productDetail.payment_interest.calc_ufa      = result.raws.data.ufa;  
                $rootScope.productDetail.payment_interest.calc_tst      = result.raws.data.tst;  
                $rootScope.productDetail.payment_interest.calc_rufa     = result.raws.data.rufa; 
                $rootScope.productDetail.payment_interest.calc_stufa    = result.raws.data.stufa;
                $rootScope.productDetail.payment_interest.calc_tfdb     = result.raws.data.tfdb; 
                $rootScope.productDetail.payment_interest.calc_da       = result.raws.data.da;  
            }
            
            //Get all profession error function
            function oneTimeCalcDisbursementError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }

        this.oneTimeCalcLenderFee = function(principal, input_lfr, input_npm){
            var param = {};
            param.principal = ($rootScope.productDetail.payment_interest.input_principle != '') ? $rootScope.productDetail.payment_interest.input_principle : 0;
            param.lfr = ($rootScope.productDetail.payment_interest.input_lfr != '') ? $rootScope.productDetail.payment_interest.input_lfr : 0;
            param.npm = ($rootScope.productDetail.payment_interest.input_npm != '') ? $rootScope.productDetail.payment_interest.input_npm : 0;
            param.mir = ($rootScope.productDetail.payment_interest.calc_mir != '') ? $rootScope.productDetail.payment_interest.calc_mir : 0;
            param.tst = ($rootScope.productDetail.payment_interest.calc_tst != '') ? $rootScope.productDetail.payment_interest.calc_tst : 0;

            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/oneTimeCalcLenderFee', oneTimeCalcLenderFeeSuccess, oneTimeCalcLenderFeeError, 'post');
            
            //Get all profession success function
            function oneTimeCalcLenderFeeSuccess(result){
                $rootScope.productDetail.payment_interest.calc_lfa   = result.raws.data.lfa;  
                $rootScope.productDetail.payment_interest.calc_stlf  = result.raws.data.stlf;  
                $rootScope.productDetail.payment_interest.calc_rlf   = result.raws.data.rlf;
                $rootScope.productDetail.payment_interest.calc_ra    = result.raws.data.ra;
                $rootScope.productDetail.payment_interest.pl         = result.raws.data.pl;
            }
            
            //Get all profession error function
            function oneTimeCalcLenderFeeError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }

        this.oneTimePenaltyCalc = function(){
            var param = {};
            param.principal = ($rootScope.productDetail.payment_interest.input_principle != '') ? $rootScope.productDetail.payment_interest.input_principle : 0;
            param.pfpd      = ($rootScope.productDetail.payment_interest.input_pfpd != '') ? $rootScope.productDetail.payment_interest.input_pfpd : 0;
            param.dpd       = ($rootScope.productDetail.payment_interest.input_dpd != '') ? $rootScope.productDetail.payment_interest.input_dpd : 0;
            param.pprm      = ($rootScope.productDetail.payment_interest.input_pprm != '') ? $rootScope.productDetail.payment_interest.input_pprm : 0;
            param.tst       = ($rootScope.productDetail.payment_interest.calc_tst != '') ? $rootScope.productDetail.payment_interest.calc_tst : 0;
            param.ra        = ($rootScope.productDetail.payment_interest.calc_ra != '') ? $rootScope.productDetail.payment_interest.calc_ra : 0;
            param.lfa       = ($rootScope.productDetail.payment_interest.calc_lfa != '') ? $rootScope.productDetail.payment_interest.calc_lfa : 0;

            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/oneTimePenaltyCalc', oneTimePenaltyCalcSuccess, oneTimePenaltyCalcError, 'post');
            
            //Get all profession success function
            function oneTimePenaltyCalcSuccess(result){
                $rootScope.productDetail.payment_interest.pfd    = result.raws.data.pfd;  
                $rootScope.productDetail.payment_interest.tpf    = result.raws.data.tpf;  
                $rootScope.productDetail.payment_interest.prm    = result.raws.data.prm;
                $rootScope.productDetail.payment_interest.rrp    = result.raws.data.rrp;
                $rootScope.productDetail.payment_interest.strp   = result.raws.data.strp;
                $rootScope.productDetail.payment_interest.ra_wp  = result.raws.data.ra_wp;
                $rootScope.productDetail.payment_interest.pl_wp  = result.raws.data.pl_wp;
            }
            
            //Get all profession error function
            function oneTimePenaltyCalcError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }

        this.emiCalcInput = function(){
            var param = {};
            param.principal = ($rootScope.productDetail.payment_interest.input_principle != '') ? $rootScope.productDetail.payment_interest.input_principle : 0;
            param.air       = ($rootScope.productDetail.payment_interest.input_air != '') ? $rootScope.productDetail.payment_interest.input_air : 0;
            param.npm       = ($rootScope.productDetail.payment_interest.input_npm != '') ? $rootScope.productDetail.payment_interest.input_npm : 0;
            param.lty       = ($rootScope.productDetail.payment_interest.input_emi_lty != '') ? $rootScope.productDetail.payment_interest.input_emi_lty : 0;
            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/emiCalcInput', emiCalcInputSuccess, emiCalcInputError, 'post');
            
            //Get all profession success function
            function emiCalcInputSuccess(result){
                $rootScope.productDetail.payment_interest.calc_mir           = result.raws.data.mir;  
                $rootScope.productDetail.payment_interest.calc_emi_tp        = result.raws.data.tp;  
                $rootScope.productDetail.payment_interest.calc_emi_amount    = result.raws.data.emi;                  
                $rootScope.productDetail.payment_interest.emi_calculator     = result.raws.data.emi_calculator;         
            }
            
            //Get all profession error function
            function emiCalcInputError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }

        this.emiCalcDisbursement = function(){
            var param = {};
            param.principal = ($rootScope.productDetail.payment_interest.input_principle != '') ? $rootScope.productDetail.payment_interest.input_principle : 0;
            param.lpfp      = ($rootScope.productDetail.payment_interest.input_lpfp != '') ? $rootScope.productDetail.payment_interest.input_lpfp : 0;
            param.ufp       = ($rootScope.productDetail.payment_interest.input_ufp != '') ? $rootScope.productDetail.payment_interest.input_ufp : 0;
            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/emiCalcDisbursement', emiCalcDisbursementSuccess, emiCalcDisbursementError, 'post');
            
            //Get all profession success function
            function emiCalcDisbursementSuccess(result){
                $rootScope.productDetail.payment_interest.calc_arl      = result.raws.data.arl;  
                $rootScope.productDetail.payment_interest.calc_lpfa     = result.raws.data.lpfa; 
                $rootScope.productDetail.payment_interest.calc_ufa      = result.raws.data.ufa;  
                $rootScope.productDetail.payment_interest.calc_tst      = result.raws.data.tst;  
                $rootScope.productDetail.payment_interest.calc_rufa     = result.raws.data.rufa; 
                $rootScope.productDetail.payment_interest.calc_stufa    = result.raws.data.stufa;
                $rootScope.productDetail.payment_interest.calc_tfdb     = result.raws.data.tfdb; 
                $rootScope.productDetail.payment_interest.calc_da       = result.raws.data.da;  
            }
            
            //Get all profession error function
            function emiCalcDisbursementError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }


        this.emiCalcLenderFee = function(){
            var param = {};
            param.lfr   = ($rootScope.productDetail.payment_interest.input_lfr != '') ? $rootScope.productDetail.payment_interest.input_lfr : 0;
            param.emi   = ($rootScope.productDetail.payment_interest.calc_emi_amount != '') ? $rootScope.productDetail.payment_interest.calc_emi_amount : 0;
            param.tst   = ($rootScope.productDetail.payment_interest.calc_tst != '') ? $rootScope.productDetail.payment_interest.calc_tst : 0;

            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/emiCalcLenderFee', emiCalcLenderFeeSuccess, emiCalcLenderFeeError, 'post');
            
            //Get all profession success function
            function emiCalcLenderFeeSuccess(result){
                $rootScope.productDetail.payment_interest.calc_lfa   = result.raws.data.lfa;  
                $rootScope.productDetail.payment_interest.calc_stlf  = result.raws.data.stlf;  
                $rootScope.productDetail.payment_interest.calc_rlf   = result.raws.data.rlf;
                $rootScope.productDetail.payment_interest.calc_ra    = result.raws.data.ra;
                $rootScope.productDetail.payment_interest.pl         = result.raws.data.pl;
            }
            
            //Get all profession error function
            function emiCalcLenderFeeError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }

        this.emiPenaltyCalc = function(){
            var param = {};
            param.obfpf = ($rootScope.productDetail.payment_interest.input_emi_obfpf != '') ? $rootScope.productDetail.payment_interest.input_emi_obfpf : 0;
            param.pfpd  = ($rootScope.productDetail.payment_interest.input_pfpd != '') ? $rootScope.productDetail.payment_interest.input_pfpd : 0;
            param.dpd   = ($rootScope.productDetail.payment_interest.input_dpd != '') ? $rootScope.productDetail.payment_interest.input_dpd : 0;
            param.pprm  = ($rootScope.productDetail.payment_interest.input_pprm != '') ? $rootScope.productDetail.payment_interest.input_pprm : 0;
            param.tst   = ($rootScope.productDetail.payment_interest.calc_tst != '') ? $rootScope.productDetail.payment_interest.calc_tst : 0;
            param.emi   = ($rootScope.productDetail.payment_interest.calc_emi_amount != '') ? $rootScope.productDetail.payment_interest.calc_emi_amount : 0;
            param.lfa   = ($rootScope.productDetail.payment_interest.calc_lfa != '') ? $rootScope.productDetail.payment_interest.calc_lfa : 0;

            param.outstnd_bal = 9374;

            //Call API get all profession
            ajaxService.ApiCall(param, CONFIG.ApiUrl+'products/emiPenaltyCalc', emiPenaltyCalcSuccess, emiPenaltyCalcError, 'post');
            
            //Get all profession success function
            function emiPenaltyCalcSuccess(result){
                $rootScope.productDetail.payment_interest.fpf    = result.raws.data.fpf;  
                $rootScope.productDetail.payment_interest.pfd    = result.raws.data.pfd;  
                $rootScope.productDetail.payment_interest.tpf    = result.raws.data.tpf;  
                $rootScope.productDetail.payment_interest.prm    = result.raws.data.prm;
                $rootScope.productDetail.payment_interest.rrp    = result.raws.data.rrp;
                $rootScope.productDetail.payment_interest.strp   = result.raws.data.strp;
                $rootScope.productDetail.payment_interest.ra_wp  = result.raws.data.ra_wp;
                $rootScope.productDetail.payment_interest.pl_wp  = result.raws.data.pl_wp;
            }
            
            //Get all profession error function
            function emiPenaltyCalcError(result){
                $rootScope.errorMessage = result.raws.error_message;
            }
        }










        /** --------------------------------------------------------------------------
         * @ Service Name             : getCurrentLocation()
         * @ Added Date               : 14-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : current location coords will return 
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 14-04-2016
         * @ Modified By              : Subhankar
         * 
         */
         this.showErrorMessage = function(meesage){
            alert(meesage);
         }

         /** --------------------------------------------------------------------------
         * @ Service Name             : logOutUser()
         * @ Added Date               : 14-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : log Out User
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 14-04-2016
         * @ Modified By              : Subhankar
         * 
         */
         this.logOutUser = function(){
            $location.path('/');
         }

        /** --------------------------------------------------------------------------
         * @ Service Name             : getAddressFromlatlong()
         * @ Added Date               : 19-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : get Address From lat long
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 19-04-2016
         * @ Modified By              : Subhankar
         * 
         */
         //fetch current address using lat long 
        this.getAddressFromLatLong = function(latLng){
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'latLng': latLng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                       return results[1].formatted_address;
                    }
                }
            });
        }           

        /** --------------------------------------------------------------------------
         * @ Service Name             : getAddressFromlatlong()
         * @ Added Date               : 19-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : get Address From lat long
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 19-04-2016
         * @ Modified By              : Subhankar
         * 
         */
        this.getLatLongFromAddress = function(address){
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': address }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                       var location = results[0].geometry.location;
                       var latLng = {'lat' : location.lat(), 'lng' : location.lng()};
                       return latLng;
                    }
                }else {
                    console.log("Geocode was not successful for the following reason: " + status);
                }
            });
        }

        /** --------------------------------------------------------------------------
         * @ Service Name             : generateMapWithMarker()
         * @ Added Date               : 19-04-2016
         * @ Added By                 : Subhankar
         * -----------------------------------------------------------------
         * @ Description              : generate Map With Marker
         * -----------------------------------------------------------------
         * @ return                   : array
         * -----------------------------------------------------------------
         * @ Modified Date            : 19-04-2016
         * @ Modified By              : Subhankar
         * 
         */
        this.generateMapWithMarker = function(mapId, lat, lng){
            var pyrmont = {lat: lat, lng: lng};
            var latLng = new google.maps.LatLng(lat, lng);
            //map initiating, this will create map and will show in pop up
            var map = new google.maps.Map(document.getElementById(mapId), {
              center: pyrmont,
              zoom: 13
            });

            var marker = new google.maps.Marker({
                map: map,
                animation: google.maps.Animation.DROP,
                draggable:true,
                position: latLng
            });
        } 


        this.logOutAdmin = function (){
            alert();
        }

    }])













    /*
    * --------------------------------------------------------------------------
    * @ Directive Name           : compareTo()
    * @ Added Date               : 14-04-2016
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : Custom directive for adding custom validation for matching password and confirm paasword
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            : 14-04-2016
    * @ Modified By              : Subhankar
    * 
    */  
    .directive('compareTo', function(){
        return {
            restrict: 'A',
            require: "ngModel",
            scope: {
                otherModelValue: "=compareTo"
            },
            link: function (scope, element, attributes, ngModel) {
                ngModel.$validators.compare = function (modelValue) {
                    //alert(modelValue);
                    return modelValue == scope.otherModelValue;
                };
                scope.$watch("otherModelValue", function () {
                    ngModel.$validate();
                });
            }
        };
    })

    /*
    * --------------------------------------------------------------------------
    * @ Directive Name           : numberMask()
    * @ Added Date               : 14-04-2016
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : admin left side menu is managed from here
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            : 14-04-2016
    * @ Modified By              : Subhankar
    * 
    */
    .directive('positiveDecimalNumberMask', function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var config = {
                    'negative'  : false,
                };                
                $(element).numeric(config);            
            }
        }
    })

    .directive('positiveNumberMask', function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var config = {
                    'negative'      : false,
                    'decimal'       : false,
                    'decimalPlaces' : 0,
                };                
                $(element).numeric(config);
            }
        }
    })

    .directive('negetiveNumberMask', function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var config = {
                    'negative'      : true,
                    'decimal'       : false,
                    'decimalPlaces' : 0,
                };                
                $(element).numeric(config);
            }
        }
    })











    /*
     * --------------------------------------------------------------------------
     * @ Directive Name           : dynamicUrl
     * @ Added Date               : 15-12-2015
     * @ Added By                 : Subhankar
     * -----------------------------------------------------------------
     * @ Description              : dynamic url for html5 video src
     * -----------------------------------------------------------------
     * @ Modified Date            : 15-12-2015
     * @ Modified By              : Subhankar
     * 
     */
    .directive('dynamicUrl', function () {
        return {
            restrict: 'A',
            link: function postLink(scope, element, attr) {
                //console.log(attr.dynamicUrlSrc);
                element.attr('src', attr.dynamicUrlSrc);
            }
        };
    })

    /*
     * --------------------------------------------------------------------------
     * @ Directive Name           : scrollOnClick
     * @ Added Date               : 17-05-2016
     * @ Added By                 : Subhankar
     * -----------------------------------------------------------------
     * @ Description              : scroll On Click
     * -----------------------------------------------------------------
     * @ Modified Date            : 17-05-2015
     * @ Modified By              : Subhankar
     * 
     */
    .directive('scrollOnClick', function() {
      return {
        restrict: 'A',
        link: function(scope, $elm) {
          $elm.on('click', function() {
            $("body").animate({scrollTop: $elm.offset().top}, "slow");
          });
        }
      }
    })

    /*
     * --------------------------------------------------------------------------
     * @ Filter Name              : customDateFormat
     * @ Added Date               : 15-12-2015
     * @ Added By                 : Subhankar
     * -----------------------------------------------------------------
     * @ Description              : custom Date Format filter
     * -----------------------------------------------------------------
     * @ Modified Date            : 15-12-2015
     * @ Modified By              : Subhankar
     * 
     */
    .filter('customDateFormat', function ($filter) {
         return function (dateString, dateFormat) {
             var dateString = dateString.replace(/-/g, "/");
             var dateObject = new Date(dateString);
             var convertedDate = $filter('date')(new Date(dateObject), dateFormat);
             return convertedDate;
         };
    })

