<?php
namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Traits\SearchTrait;
use Laracle\ShopBox\Models\Product;
use Laracle\ShopBox\Models\Variant;
use Laracle\ShopBox\Traits\ImageTrait;
use Laracle\ShopBox\Traits\TagTrait;
use Log;

class Collection extends Model
{
    use HasFactory, SearchTrait, ImageTrait, TagTrait;

    protected $guarded = [];

    protected $search = ['name'];

    protected $with = ['tags','images'];


    protected $casts = [
      'options' => 'array'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class,'collection_product')->orderBy('order');
    }

    public function getVariants(){

          if ($this->type == 'automated') {


            $varQuery = Product::query();

            $ar = [];


            foreach ($this->options['options'] as $option) {

              if ($option['method'] == '.%') {
                $option['method'] = 'like';
                $option['value'] = '%'.$option['value'];
              } else if ($option['method'] == '%.'){
                $option['method'] = 'like';
                $option['value'] = $option['value'].'%';
              } else if ($option['method'] == '%%'){
                $option['method'] = 'like';
                $option['value'] = '%'.$option['value'].'%';
              } else if ($option['method'] == '!%'){
                $option['method'] = 'not like';
                $option['value'] = '%'.$option['value'].'%';
              }

              array_push($ar,[$option['key'],$option['method'],$option['value']]);
            }

            if ($this->options['type'] == 'all') {


              $varQuery->whereHas('variants', function ($q) use ($ar) {
                    $q->where($ar);
              })->orWhereHas('defaultProduct', function ($q) use ($ar) {
                    $q->where($ar);
              });


            } else {
              $varQuery->whereHas('variants', function ($q) use ($ar) {
                    $q->where('id','>',-1)->orWhere($ar);
              })->orWhereHas('defaultProduct', function ($q) use ($ar) {
                    $q->where('id','>',-1)->orWhere($ar);
              });

            }

            return $varQuery->paginate(20);

          } else {
            return $this->products()->paginate(20);
          }
    }

}
