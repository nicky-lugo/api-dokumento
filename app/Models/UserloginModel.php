<?php namespace App\Models;

use CodeIgniter\Model;

class UserloginModel extends Model{
  protected $table = 'user_logins';
  protected $primaryKey = 'ExternalId';
  protected $allowedFields = ['IsSuccess',								
							  'ChangedByDate',	
							  'ChangedByUser',	
							  'CreatedByUser',	
							  'ChangedDuringVersion',	
                              'IsMobile',
                              'IsWebsite',
                              'UserId',
                              'ExternalId',
                              'EmailAddress',
                              'IpAddress',
                              'IpAddressNumber',
                              'SessionId',
							  'UserAgent',
							  'LocalLanguage',
							  'LocalTime',
							  'DisplayName',
							  'MobilePhoneNumber',
							  'PhotoUrl',
							  'ProviderId',
							  'Cookies'];  
						  
}
