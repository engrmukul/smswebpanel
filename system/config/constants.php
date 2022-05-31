<?php
//file : app/config/constants.php

return [
	'USER_GROUP' => '4',
	'SUPER_ADMIN_GROUP' => '1',
	'ADMIN_GROUP' => '2',
	'RESELLER_GROUP' => '3',
    'EMAIL_SERVICE' => env('ENABLE_EMAIL_MODULE', false),
	'RATE_LIMIT'=>env('RATE_LIMIT', 300)
];
