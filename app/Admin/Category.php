<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    
    public function getAllCategories($where){
        return $this->where($where)->get()->toArray();
    }
    
    
    
    public function getCategoryDetail($where){
        return $this->where($where)->first();
    }
    
    
    
    public function saveCategory($data){
        $this->category_name = $data['category_name'];
        $this->date_created = date(getConstant('DATETIME_DB_FORMAT'));
        return $this->save();
    }
    
    
    public function updateCategory($data,$where){
        $update = array(
            'category_name' => $data['category_name']
        );
        return $this->where($where)->update($update);
    }
    
    public function deleteCategory($where){
        $update = array(
            'status' => getConstant('STATUS_DELETED')
        );
        return $this->where($where)->update($update);
    }
}
