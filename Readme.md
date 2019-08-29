# Laravel Admin Panel Generator
[![Build Status](https://travis-ci.org/freezer278/laravel_admin_panel.svg?branch=master)](https://travis-ci.org/freezer278/laravel_admin_panel)  

This package is made for speeding up development of admin panel for your Laravel project.  
It helps you to handle common tasks in admin panel development for your project.

![preview][preview]

## Features
1. Quick CRUD Generation
1. Working with different field types
1. Handling relationships
1. Search
1. Export Data to xls, csv file
1. Using default Order By and Where conditions
1. Ability to add additional buttons to each list item
1. Files uploading to Model column
1. Files uploading using spatie/laravel-medialibrary package 



## 1. Installation
1. `composer require vmorozov/laravel_admin_generator`
1. `php artisan vendor:publish` and select `Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider` to publish all needed files for admin panel.

## 2. Setup
### 1. Create Controller
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;

class ProductsController extends CrudController
{
    protected $model = Product::class;
    protected $url = 'products';
    protected $titlePlural = 'Товары';
    protected $titleSingular = 'Товар';    
}
```
### 2. Add record to routes/admin.php
```php
...

AdminRoute::resource(\App\Http\Controllers\Admin\ProductsController::class);
```

### 3. (Optional) Setup fields

#### Available field types:
* text - for simple input type="text"
* textarea - for simple textarea
* number - for input type="number"
* email - for input type="email"
* date - for input type="date"
* datetime - for input type="datetime"
* file - for input type="file"
* select - for one-to-one relationship via select
* select_multiple - for many-to-many relationships via select multiple


#### in Model
```php
public $adminFields = [

        'name' => [
            'label' => 'Name',
            'displayInForm' => true,
            'displayInList' => true,
            'searchable' => true,
            'min' => 2,
            'max' => 50,

        ],
        'description' => [
            'label' => 'Description',
            'displayInForm' => true,
            'displayInList' => true,
            'searchable' => false,
            'min' => 2,
            'max' => 5000,

        ],
        'price' => [
            'label' => 'Price',
            'displayInForm' => true,
            'displayInList' => true,
            'field_type' => 'number',
            'min' => 0,
            'max' => 100000,
        ],
        'user_id' => [
            'label' => 'User Id',
            'displayInForm' => true,
            'displayInList' => true,
            'min' => 0,

            'field_type' => 'select',
            'relation' => 'user',
            'relation_model' => User::class,
            'relation_display_attribute' => 'name',
        ],
        'users' => [
            'label' => 'Users Many To Many',
            'displayInForm' => true,
            'min' => 0,

            'field_type' => 'select_multiple',
            'relation' => 'users',
            'relation_model' => User::class,
            'relation_display_attribute' => 'name',
        ],
        
        'updated_at' => [
            'displayInForm' => true,
            'displayInList' => false,
        
            'field_type' => 'date_time',
        ]
    ];
```

#### In Controller
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;

class ProductsController extends CrudController
{
    protected $model = Product::class;
    protected $url = 'products';
    protected $titlePlural = 'Товары';
    protected $titleSingular = 'Товар';
    protected $columnParams = [
        'name' => [
                    'label' => 'Name',
                    'displayInForm' => true,
                    'displayInList' => true,
                    'searchable' => true,
                    'min' => 2,
                    'max' => 50,
        
                ],
                'description' => [
                    'label' => 'Description',
                    'displayInForm' => true,
                    'displayInList' => true,
                    'searchable' => false,
                    'min' => 2,
                    'max' => 5000,
        
                ],
    ];
}
```

### 3. Add link to the sidebar
Open file resources/views/vendor/vmorozov/laravel_admin_generator/layouts/sidebar.blade.php and add your links to the generated admin panel endpoints.
 

## 3. Advanced Usage

### Add default order by and where clauses to the list query
```php
protected function setup()
{
    $this->addDefaultWhereClause('password', '!=', null);
    $this->addDefaultOrderByClause('id', 'desc');
}
```

### Add additional button to each item in list
```php
protected function setup()
{
    // without putting entity id to the url
    $this->addListItemButton(url('/test_button'), 'test button');
    
    // with putting entity id to the url
    $this->addListItemButton(url('/test_button/{id}'), '<i class="fa fa-check" aria-hidden="true"></i> test button', 'btn btn-success', ['target' => '_blank']);

}
```

### Search
To add search functionality you should just add searchable param to the field setup
```php
public $adminFields = [
        'name' => [
            'searchable' => true,
        ],
    ];
```

### Working with spatie/laravel-medialibrary
To start using this package in admin panel [install package](https://docs.spatie.be/laravel-medialibrary/v6/installation-setup).  
Than you should add additional setups to your model:
```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Vmorozov\LaravelAdminGenerator\App\Utils\ModelTraits\AdminPanelTrait;

class Product extends Model implements HasMedia
{
    use AdminPanelTrait;
    use HasMediaTrait;

    public $mediaCollections = [
        'main_image' => [
            'name' => 'Main image',
            'single_file' => true
        ],
        'gallery' => [
            'name' => 'Gallery'
        ],
    ];
    
//  Some other code here     
}    
```
##### There are tho kinds of available collections setups
1. Simple collection for multiple files
2. Collection for single file (Such as avatar image of user, main image of product, etc).
To make collection single use `'single_file' => true` parameter.

[preview]: preview.png 
