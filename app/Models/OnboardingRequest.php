<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingRequest extends Model
{
    protected $table = 'onboarding_requests';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'bill_to_address',
        'rental_property_address',
        'owner_birthday',
        'company_name',
        'social_security_number',
        'business_ein_number',
        'routing_number_of_deposites',
        'account_number',
        'account_name',
        'account_card_number',
        'account_exp',
        'account_cvv',
        'housekeeping_closet_access',
        'wifi_lock_Access',
        'security_camera_login_instruction',
        'file1',
        'file2',
    ];
}
