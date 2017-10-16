angular
	.module('angular_practice')
	.controller('productController', ["$scope", 'ajaxService', 'CONFIG', '$location', '$timeout', '$cookies', '$state', "helper", "$rootScope",'$window', function($scope, ajaxService, CONFIG, $location, $timeout, $cookies, $state, helper, $rootScope,$window)
	{
		//alert($state.$current.name);

		$scope.productsData 		= {};
	    $scope.pageno 				= 1; // initialize page no to 1
	    $scope.itemsPerPage 		= CONFIG.itemsPerPage; 
	    $scope.order_by 			= 'id';
	    $scope.order 				= 'desc';
	    $scope.searchByName 		= '';
		
		// Perform to getAllDegree action
		$scope.getAllProducts = function(pageno, order_by, order)
		{ 
	        //alert(CONFIG.ApiUrl);
	        //alert("hii");
	        $scope.pageno 	= pageno ? pageno : 1;
	       	$scope.order_by = order_by ? order_by : 'id';
	        $scope.order 	= order ? order : 'desc';

	        var getproductsParam = 
	        {
	        	'pass_key' 			: $cookies.get('pass_key'),
	        	'admin_user_id'		: $cookies.get('admin_user_id'),
	            'searchByName'		: $scope.searchByName,
	            'order_by'			: $scope.order_by,
	            'order'				: $scope.order,
	            'page'				: $scope.pageno,
	            'page_size'			: $scope.itemsPerPage
	        };

	        //alert(getDegreeParam.searchByName);
			ajaxService.ApiCall(getproductsParam, CONFIG.ApiUrl+'products/getAllProducts', $scope.getAllProductsSuccess, $scope.getAllProductsError, 'post');
		}

		//getAllDegree success function
		$scope.getAllProductsSuccess = function(result,status) 
		{
			
		    if(status == 200) 
		    {
                $scope.allProducts	= result.raws.data.dataset;
                //alert($scope.allProducts.count);
                //alert($scope.allProducts.product_name);
                //$scope.total_count 	= result.raws.data.count;
               //$scope.imgsrc='/var/www/html/angular/assets/resources/product/thumb/'+$scope.allProducts[0].id+'.'+$scope.allProducts[0].product_image_extension;      
		    }		       
		}

		//getAllDegree error function
		$scope.getAllProductsError = function(result) 
		{
            if(status == 403)
            {
                helper.unAuthenticate();
            }
            else
            {
                $scope.errorMessage = result.raws.error_message;
                $scope.clearMessage();
            }
		}
		//$scope.getAllProducts();


		/****************Search START******************/
		$scope.$watch('searchByName', function(tmpStr) 
		{
		    if (angular.isUndefined(tmpStr))
		    {		    	
		        return 0;
		    }
		    else if(tmpStr=='')
		    {
				$scope.getAllProducts($scope.pageno, $scope.order_by, $scope.order, $scope.searchByName);
		    }
		    else
		    {
		    	$timeout(function() 
		    	{ 
			        if (tmpStr === $scope.searchByName) 
			        {
						
						$scope.getAllProducts($scope.pageno, $scope.order_by, $scope.order, $scope.searchByName);
			        }
			    }, 1000);	
		    }		    
		});
		/**************** Search END ******************/
		$scope.productImage=[];
		$scope.uploadImg = function (element) {
			var tot_length = $scope.productImage.length;
		    $scope.productImage[tot_length] = element.files[0];
		    console.log($scope.productImage);
		}

		$scope.doaddproducts = function(productsData) 
		{ 
			var formdata = new FormData();
			//formdata.append('file',$scope.product_img);
			//alert($scope.productImage.length);
			for(var i = 0; i<$scope.productImage.length; i++)
	        {
	        	formdata.append('file'+i, $scope.productImage[i]);
	        }
			formdata.append('pass_key',$cookies.get('pass_key'));
			formdata.append('admin_user_id',$cookies.get('admin_user_id'));
		    //console.log(formdata);
		    angular.forEach(productsData, function(value, key) {
			formdata.append(key, value);
			});

			

	ajaxService.ApiCallImagePost(formdata, CONFIG.ApiUrl+'products/addProducts', $scope.addProductsSuccess, $scope.addProductsError, 'post');
		}

		//addDegree success function
		$scope.addProductsSuccess = function(result,status) 
		{

		    if(status == 200) 
		    {   $window.scrollTo(0, 100);
		    	$scope.successMessage = result.raws.success_message;
		    	$scope.clearMessage();
		    	$timeout(function() {
		        	$location.path('dashboard/product/list');
		        }, CONFIG.TimeOut);
		    }		       
		}

		//addDegree error function
		$scope.addProductsError = function(result) 
		{
			window.scrollTo(0, 100);
            $scope.errorMessage = result.raws.error_message;
            $scope.clearMessage();
		}
		$scope.deleteProduct = function(productsId,index)
		{
			
		    //alert(productsId);
		    $scope.productsIndex = index;
			var productsParam = {
				'productsID' 	: productsId,
			    'pass_key'		: $cookies.get('pass_key'),
	        	'admin_user_id' : $cookies.get('admin_user_id')
			};
			ajaxService.ApiCall(productsParam, CONFIG.ApiUrl+'products/deleteProducts', $scope.deleteProductsSuccess, $scope.deleteProductsError, 'post');
		}

		$scope.deleteProductsSuccess = function(result, status)
		{
			if(status == 200)
			{   
				$scope.getAllProducts($scope.pageno, $scope.order_by, $scope.order, $scope.searchByName);
				//alert($scope.employeeIndex);
				$scope.successMessage = result.raws.success_message;
				//alert($scope.successMessage);
				$scope.clearMessage();
				$scope.getAllProducts.splice($scope.productsIndex,1);
				//window.location.reload();
				
			}
		}

		$scope.deleteProductsError = function(result, status)
		{
			if(status == 403)
            {
                helper.unAuthenticate();
            } 
            else 
            {
	            $scope.errorMessage = result.raws.error_message;
	            $scope.clearMessage(); 
	        }
		}

		$scope.clearMessage = function()
		{
			$timeout(function() {
        		$scope.successMessage = '';
                $scope.errorMessage = '';
            }, CONFIG.TimeOut);
		}

	}])
	.controller('editProductController',["$scope", 'ajaxService', 'CONFIG', '$location', '$timeout', '$cookies', '$state', "helper", "$rootScope",'$stateParams','$window',function($scope,ajaxService,CONFIG,$location,$timeout,$cookies, $state, helper, $rootScope, $stateParams,$window){
	$scope.productsDetail 	= {};
	$scope.productsID 		= $stateParams.productsID;
	$scope.successMessage 	= '';
    $scope.errorMessage 	= '';
 // Perform to getDegreeDetail action
		$scope.getProductsDetail = function()
		{ 
			var productsParam = {
				'pass_key' 			: $cookies.get('pass_key'),
	        	'admin_user_id'		: $cookies.get('admin_user_id'),
	        	'productsID'        : $scope.productsID
	        };
			ajaxService.ApiCall(productsParam, CONFIG.ApiUrl+'products/getProductsDetail', $scope.getProductsDetailSuccess, $scope.getProductsDetailError, 'post');
		}

 //getDegreeDetail success function
		$scope.getProductsDetailSuccess = function(result,status) 
		{
		    
			//console.log(result.raws.data.dataset);
			//alert("1"+result.raws.data.dataset.product_name);
		    if(status == 200) 
		    {
		    	//alert("1"+result.raws.data.dataset.product_name);
                //$scope.productsDetail = result.raws.data.dataset;
                $scope.productsDetail.id=result.raws.data.dataset.id;
                //alert("1"+$scope.productsDetail.id);
                $scope.productsDetail.product_name = result.raws.data.dataset.product_name;
                $scope.productsDetail.product_code = result.raws.data.dataset.product_code;
                $scope.productsDetail.product_price = result.raws.data.dataset.product_price;
                $scope.productsDetail.fk_category_id = result.raws.data.dataset.fk_category_id;
                $scope.productsDetail.product_img_url = result.raws.data.dataset.product_img_url;
                $scope.productsDetail.category_name = result.raws.data.dataset.fk_category_id.toString();
                //alert($scope.productsDetail.product_name);
		    }
		}

		//getDegreeDetail error function
		$scope.getProductsDetailError = function(result) 
		{
            $scope.errorMessage = result.raws.error_message;
            $scope.clearMessage();
		}

		if($state.$current.name == 'product.update-product')
		{
			$scope.getProductsDetail();
		}

		if($state.$current.name == 'product.product-detail')
		{
			$scope.getProductsDetail();
		}

        $scope.updateProductsDetail= function(productsDetail){
        	//alert(productsDetail.id);
        productsDetail.pass_key=$cookies.get('pass_key');
		productsDetail.admin_user_id=$cookies.get('admin_user_id');
	    ajaxService.ApiCall(productsDetail, CONFIG.ApiUrl+'products/updateProductsDetail',
 	    $scope.updateProductsDetailSuccess,$scope.updateProductsDetailError, 'post');
		}

//updateDegreeDetail success function
		$scope.updateProductsDetailSuccess = function(result,status) 
		{
		    if(status == 200) 
		    {
		    	$window.scrollTo(0, 100);
                $scope.successMessage = result.raws.success_message;
                $scope.clearMessage();
                $timeout(function() {
		        $location.path('dashboard/product/list');
		        }, CONFIG.TimeOut);
		    }
		}

		//updateDegreeDetail error function
		$scope.updateProductsDetailError = function(result) 
		{
			window.scrollTo(0, 100);
            $scope.errorMessage = result.raws.error_message;
            $scope.clearMessage();
		}

		$scope.getImageDetail = function()
		{ 
			var productsParam = {
				'pass_key' 			: $cookies.get('pass_key'),
	        	'admin_user_id'		: $cookies.get('admin_user_id'),
	        	'productsID'        : $scope.productsID
	        };
			ajaxService.ApiCall(productsParam, CONFIG.ApiUrl+'products/ImageDetail', $scope.ImageDetailSuccess, $scope.ImageDetailError, 'post');
		}

 //getDegreeDetail success function
		$scope.ImageDetailSuccess = function(result,status) 
		{
		  
		    if(status == 200) 
		    {
		    	//alert("1"+result.raws.data.dataset.product_name);
                //$scope.productsDetail = result.raws.data.dataset;
                $scope.productsDetail.id=result.raws.data.dataset.id;
                //alert("1"+$scope.productsDetail.id);
                $scope.productsDetail.product_firstimage = result.raws.data.dataset.product_img_url1;
                $scope.productsDetail.product_secondimage = result.raws.data.dataset.product_img_url2;
                
		    }
		}

		//getDegreeDetail error function
		$scope.ImageDetailError = function(result) 
		{
            $scope.errorMessage = result.raws.error_message;
            $scope.clearMessage();
		}

		if($state.$current.name == 'product.update-firstimage')
		{
			
			$scope.getImageDetail();
		}

		if($state.$current.name == 'product.update-secondimage')
		{
			
			$scope.getImageDetail();
		}
		
		$scope.uploadproImg = function (element) {
			
		    $scope.productImage= element.files[0];
		    //console.log($scope.productImage);
		}
		$scope.doUpdateImage=function()
		{
			var formdata = new FormData();
			formdata.append('file', $scope.productImage);
			formdata.append('id',$scope.productsID);
			formdata.append('pass_key',$cookies.get('pass_key'));
			formdata.append('admin_user_id',$cookies.get('admin_user_id'));
			ajaxService.ApiCallImagePost(formdata, CONFIG.ApiUrl+'products/updateFirstImage', $scope.updateFirstImageSuccess, $scope.updateFirstImageError, 'post');

		}
		$scope.updateFirstImageSuccess = function(result,status) 
		{

		    if(status == 200) 
		    {   $window.scrollTo(0, 100);
		    	$scope.successMessage = result.raws.success_message;
		    	$scope.clearMessage();
		    	$timeout(function() {
		        	$location.path('dashboard/product/list');
		        }, CONFIG.TimeOut);
		    }		       
		}

		//addDegree error function
		$scope.updateFirstImageError = function(result) 
		{
			window.scrollTo(0, 100);
            $scope.errorMessage = result.raws.error_message;
            $scope.clearMessage();
		}

		$scope.doUpdateSecondImage=function()
		{
			var formdata = new FormData();
			formdata.append('file', $scope.productImage);
			formdata.append('id',$scope.productsID);
			formdata.append('pass_key',$cookies.get('pass_key'));
			formdata.append('admin_user_id',$cookies.get('admin_user_id'));
			ajaxService.ApiCallImagePost(formdata, CONFIG.ApiUrl+'products/updateSecondImage', $scope.updateSecondImageSuccess, $scope.updateSecondImageError, 'post');

		}
		$scope.updateSecondImageSuccess = function(result,status) 
		{

		    if(status == 200) 
		    {   $window.scrollTo(0, 100);
		    	$scope.successMessage = result.raws.success_message;
		    	$scope.clearMessage();
		    	$timeout(function() {
		        	$location.path('dashboard/product/list');
		        }, CONFIG.TimeOut);
		    }		       
		}

		//addDegree error function
		$scope.updateSecondImageError = function(result) 
		{
			window.scrollTo(0, 100);
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
	}])
