<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Slug;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Homeslider','Newsletter','testimonial'];
        foreach($categories as $category){
            $slug = new Slug();
            $slug->slug = Str::slug($category);
            $slug->owner = 'Homeslider';
            $slug->save();
            $cat = new Category;
            $cat->title = $category;
            $cat->type = 'Newsletter';
            $cat->slug_id = $slug->id;
            $cat->media_id = 1;
            $cat->save();
        }
    }
}
