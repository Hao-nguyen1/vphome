<?php

namespace App\Traits;

trait QueryScopes
{
    public function scopeKeyword($query, $keyword, $fieldSearch = [], $whereHas = []){
        if(!empty($keyword)){
            if(count($fieldSearch)){
                foreach($fieldSearch as $key => $val){
                    $query->orWhere($val, 'LIKE', '%'.$keyword.'%');
                }
            }else{
                $query->where('name', 'LIKE', '%'.$keyword.'%');
            }
        }
        if(isset($whereHas) && count($whereHas)){
            $field = $whereHas['field'];
            $query->orWhereHas($whereHas['relation'], function($query) use ($field, $keyword){
                $query->where($field, 'LIKE', '%'.$keyword.'%');
            });
        }

        return $query;
    }

    public function scopePublish($query, $publish){
        if(!empty($publish) ){
            $query->where('publish', '=', $publish);
        }
        return $query;
    }

    public function scopeCustomWhere($query, $where = []){
        if(!empty($where)){
            foreach($where as $key => $val){
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query;
    }

    public function scopeCustomWhereRaw($query , $rawQuery){
        if(is_array($rawQuery) && !empty($rawQuery)){
            foreach($rawQuery as $key => $val){
                $query->whereRaw($val[0], $val[1]);
            }
        }
        return $query;
    }

    public function scopeRelationCount($query, $relation){
        if(!empty($relation)){
            foreach($relation as $item){
                $query->withCount($item);
                $query->with($item);
            }
        }
        return $query;
    }

    public function scopeRelation($query, $relation){
        if(!empty($relation)){
            foreach($relation as $relation){
                $query->with($relation);
            }
        }
        return $query;
    }

    public function scopeCustomJoin($query, $join){
        if(!empty($join)){
            foreach($join as $key => $val){
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }
        }
        return $query;
    }

    public function scopeCustomGroupBy($query, $groupBy){
        if(!empty($groupBy)){
            $query->groupBy($groupBy);
        }
        return $query;
    }

    public function scopeCustomOrderBy($query, $orderBy){
        if(isset($orderBy) && !empty($orderBy)){
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query;
    }


    public function scopeCustomDropdownFilter($query, $condition){
        if(count($condition)){
            foreach($condition as $key => $val){
                if($val != 'none' && !empty($val) && $val != ''){
                    $query->where($key, '=', $val);
                }
            }
        }
        return $query;
    }

    public function scopeCustomerCreatedAt($query, $condition){
        if(!empty($condition)){
            $explode = explode('-', $condition);
            $explode = array_map('trim', $explode);
            $startDate = convertDateTime($explode[0], 'Y-m-d 00:00:00', 'm/d/Y');
            $endDate = convertDateTime($explode[1], 'Y-m-d 23:59:59', 'm/d/Y');

           $query->whereDate('created_at', '>=', $startDate);
           $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }

}
