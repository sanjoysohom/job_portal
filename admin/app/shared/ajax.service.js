angular.module('ajaxServices', [])
.service('ajaxService', ['$http', '$rootScope', '$q', 'oauth', function ($http, $rootScope, $q, oauth) {
		//converting json data to post param
        var self=this;

        this.queryParams = function(source) {
		  var array = [];		
		  for(var key in source) {
			 array.push(encodeURIComponent(key) + "=" + encodeURIComponent(source[key]));
		  }		
		  return array.join("&");
		}
		
		//this ajax post will be used in case of php post ajax
		this.AjaxPhpPost = function (data, route, successFunction, errorFunction,method , header) {
            $rootScope.myPromise = $http({ method: 'post', dataType: "jsonp",crossDomain:true, url: route,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},data: this.queryParams(data) })
                .success(function (response, status, headers, config) {  
                    successFunction(response, status);
                }).error(function (response) {  
                    console.log("Error"+response);
            });
            
        }		

        //this ajax post will be used in case of php post ajax
		this.AjaxSyncPhpPost = function (data, route, successFunction, errorFunction,method , header) {
            var deferred = $q.defer();
            $rootScope.myPromise = $http({ method: 'post', dataType: "jsonp",crossDomain:true, url: route,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},data: this.queryParams(data) })
                .then(function (response, status, headers, config) { 
            		deferred.resolve(response.data);
                })
                return deferred.promise;
            
        }		
		
		//this ajax post will be used in case of php post ajax
		/*this.AjaxPhpPostFile = function (data, route, successFunction, errorFunction) {	
			$rootScope.myPromise = $http({ method: 'post', dataType: "jsonp",crossDomain:true, url: route,
			headers: {'Content-Type': undefined},data: data ,
			  transformRequest: angular.identity})
			.success(function (response, status, headers, config) {                      
				successFunction(response, status);  
			}).error(function (response) {  
                 console.log("Error"+response);    
			});
        }*/



		









		//this ajax post will be used in case of php post ajax
		self.ApiCall = function (data, route, successFunction, errorFunction, method , header) {
			var data_token = {
				grant_type: 'client_credentials',
				client_id: oauth.client_id,
				client_secret: oauth.client_secret,
			};
			var route_token = oauth.token_url;
			$rootScope.myPromise = $http({ method: 'post', dataType: "jsonp",url: route_token,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},data: this.queryParams(data_token) })
			.success(function (response, status, headers, config) {  
				// alert(response.access_token);
				localStorage.setItem('access_token',response.access_token);
				if(method==''){
					method='post';
				}
				self.AjaxCallGetPostPutDelete(data,route,successFunction,errorFunction,method);
				//successFunction(response, status);
			}).error(function (response,status) {  
				errorFunction(response, status);
			});
		}


		//this ajax post will be used in case of php post ajax
		self.ApiCallImagePost = function (data, route, successFunction, errorFunction,method , header) {
			var data_token = {
				grant_type: 'client_credentials',
				client_id: oauth.client_id,
				client_secret: oauth.client_secret,
			};
			var route_token=oauth.token_url;
			$rootScope.myPromise = $http({ method: 'post', dataType: "jsonp",url: route_token,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},data: this.queryParams(data_token) })
			.success(function (response, status, headers, config) {  
				// alert(response.access_token);
				localStorage.setItem('access_token',response.access_token);
				if(method==''){
				method='post';
				}
				self.AjaxPhpPostFile(data,route,successFunction,errorFunction);
				//successFunction(response, status);
			}).error(function (response,status) {  
				errorFunction(response, status);
			});
		}


		self.AjaxCallGetPostPutDelete = function (data, route, successFunction, errorFunction,method , header) {
			if(method==''){
				method='post';
			}
			var access_token= localStorage.getItem('access_token');
			$http({ method: method, dataType: "jsonp",url: route,
			headers: {'Content-Type': 'application/x-www-form-urlencoded','Authorization':'Bearer '+access_token},data: this.queryParams(data) })
			.success(function (response, status, headers, config) {  
				// alert(response.response.status.access_token_status);
				successFunction(response, status);
			}).error(function (response,status) {  
				//console.log(response);
				errorFunction(response, status);
				//console.log("Error"+response);
			});  
		}
			
		
		//this ajax post will be used in case of php post ajax
		this.AjaxPhpPostFile = function (data, route, successFunction, errorFunction) {		
            var access_token= localStorage.getItem('access_token');	
			$http({ method: 'post', url: route,
			headers: {'Content-Type': undefined,'Authorization':'Bearer '+access_token},data: data ,
			  transformRequest: angular.identity})
			.success(function (response, status, headers, config) {                      
				successFunction(response, status);  
			}).error(function (response) {  
                 errorFunction(response, status); 
			});
        }




    }]);