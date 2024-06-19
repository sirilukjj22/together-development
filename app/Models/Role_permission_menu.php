<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_permission_menu extends Model
{
    use HasFactory;

    protected $table = 'role_permission_menu';
    protected $fillable = [
        'user_id',
        'profile',
        'company',
        'guest',
        
        'freelancer',
        'membership',
        'message_inbox',
        'registration_request',
        'message_request',

        'document',
        'banquet_event_order',
        'proposal',
        'hotel_contact_rate',
        'proforma_invoice',
        'billing_folio',

        'general_ledger',
        'sms_alert',
        'revenue',

        'debtor',
        'agoda',
        'elexa',

        'maintenance',
        'request_repair',
        'repair_job',
        'preventive_maintenance',

        'setting',
        'document_template_pdf',
        'user',
        'bank',
        'product_item',
        'quantity',
        'unit',
        'prefix',
        'bank_company',
        'company_type',
        'company_market',
        'company_event',
        'booking',
        
        'select_menu_all',
    ];
}
