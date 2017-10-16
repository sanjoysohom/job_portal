<?php
/**
 * Amazon S3 services Comonent.
 */
  
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

require 'aws/autoload.php';

class Aws  {
/**
 * @var : name of bucket in which we are going to operate
 */ 	
	public $bucket = 'mpokketstaging';

/**
 * @var : Amazon S3Client object
 */ 	
	private $s3 = null;
	
	
	public function __construct(){	
        //$s3 = \Aws\S3\S3Client::factory(array('signature' => 'v4'));
        $this->s3 = S3Client::factory(array(
            'key' => 'AKIAINANH65PO2US52ZA',
            'secret' => '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',
            'signature' => 'v4',
            'region' => 'ap-south-1',
        ));
		/*$this->s3 = S3Client::factory(array(
			'key' => 'AKIAJTZMAHO7YQXVLH6A',
			'secret' => '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'
		));*/


	}
        
        public function copyfile($bucket,$target_file_path,$tmp_file_path,$acl){
            $data=$this->s3->copyObject([
                        'Bucket'=>$bucket,
                        'Key'=>$target_file_path,
                        'CopySource'=>$tmp_file_path
                    ]);

            return $data;
        }

        public function uploadfile($bucket,$target_file_path,$tmp_file_path,$acl){
            $data=$this->s3->putObject([
                        'Bucket'=>$bucket,
                        'Key'=>$target_file_path,
                        'Body'=>fopen($tmp_file_path,'rb'),
                        'ACL'=>$acl        
                    ]);

            return $data;
        }

        public function deletefile($bucket,$target_file_path,$versionId){
            $this->s3->deleteObject([
                        'Bucket'=>$bucket,
                        'Key' => $target_file_path,
                        'VersionId' => $versionId
                       
                               
                    ]);
        }

         public function directuploadfile($bucket,$target_file_path,$tmp_file_path,$acl){
            $this->s3->putObject([
                        'Bucket'=>$bucket,
                        'Key'=>$target_file_path,
                        //'Body'=>fopen($tmp_file_path,'rb'),
                        'SourceFile' => $tmp_file_path,
                        'ACL'=>$acl        
                    ]);
        }

        public function downloadfile($bucket,$target_file_path,$destination_path){
            $this->s3->getObject([
                        'Bucket'=>$bucket,
                        'Key'=>$target_file_path,
                        'SaveAs'=>$destination_path
                             
                    ]);
        }

        public function if_exists($bucket,$target_file_path){
           $result =  $this->s3->doesObjectExist($bucket,$target_file_path);
           if($result==1){
            return true;
           }else{
            return false;
           }

        }
        
       
			
}
