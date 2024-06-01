<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masters extends Model
{
    use HasFactory;

    protected $table = 'master';
    protected $fillable = [
        'sort',
        'category',
        'name_th',
        'name_en',
        'detail_th',
        'detail_en',
        'number_days',
        'account_name',
        'account_number',
        'type_name',
        'entry_fields',
        'picture',
        'remark_th',
        'remark_en',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    public static function check_field($category, $field, $datakey){
        $query = Masters::where('category', $category)
        ->whereNull('deleted_at')
        ->select('id', 'name_th', 'type_name')->get();

        $similarV = '';
        foreach ($query as $key => $value) {
            $cut_space = preg_replace('/[[:space:]]+/', '', trim($value->name_th));
            $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

            if (strtoupper($txt_string) == strtoupper($datakey)) {
                $similarV = [
                    'name_th' => $value->name_th,
                    'type_name' => $value->type_name
                ];
            }
        }

        return $similarV;
    }

    // public static function check_field_account_number($category, $field, $datakey){
    //     $query = Masters::where('category', $category)
    //     ->whereNull('deleted_at')
    //     ->select('id', 'account_number', 'type_name')->get();

    //     $similarV = '';
    //     foreach ($query as $key => $value) {
    //         $cut_space = preg_replace('/[[:space:]]+/', '', trim($value->account_number));
    //         $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

    //         if (strtoupper($txt_string) == strtoupper($datakey)) {
    //             $similarV = [
    //                 'account_number' => $value->account_number,
    //                 'type_name' => $value->type_name
    //             ];
    //         }
    //     }

    //     return $similarV;
    // }

    public static function check_field2($category, $field, $datakey, $type_name){
        $query = Masters::where('category', $category)
        ->where('type_name', $type_name)->whereNull('deleted_at')
        ->select('id', 'name_th', 'type_name')->get();

        $similarV = '';
        foreach ($query as $key => $value) {
            $cut_space = preg_replace('/[[:space:]]+/', '', trim($value->name_th));
            $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

            if (strtoupper($txt_string) == strtoupper($datakey)) {
                $similarV = [
                    'name_th' => $value->name_th,
                    'type_name' => $value->type_name
                ];
            }
        }

        return $similarV;
    }

    public static function check_field_edit($id, $category, $field, $datakey){
        $query = Masters::whereNotIn('id', [$id])
                            ->where('category', $category)
                            ->whereNull('deleted_at')
                            ->select('id', 'name_th', 'type_name')
                            ->get();

        $similarV = '';
        foreach ($query as $key => $value) {
            $cut_space = preg_replace('/[[:space:]]+/', '', trim($value->name_th));
            $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

            if (strtoupper($txt_string) == strtoupper($datakey)) {
                $similarV[] = $value->name_th;
            }
        }

        return $similarV;
    }

    // public static function check_field_account_number_edit($id, $category, $field, $datakey){
    //     $query = Masters::whereNotIn('id', [$id])
    //                         ->where('category', $category)
    //                         ->whereNull('deleted_at')
    //                         ->select('id', 'account_number', 'type_name')
    //                         ->get();

    //     $similarV = '';
    //     foreach ($query as $key => $value) {
    //         $cut_space = preg_replace('/[[:space:]]+/', '', trim($value->account_number));
    //         $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

    //         if (strtoupper($txt_string) == strtoupper($datakey)) {
    //             $similarV[] = $value->account_number;
    //         }
    //     }

    //     return $similarV;
    // }
}
