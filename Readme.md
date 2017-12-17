# Laravel Admin Panel Generator
This package is made for speeding up development of admin panel for your Laravel project.
## 1. Installation
1. `composer require vmorozov/laravel_admin_generator`
1. `php artisan vendor:publish` to publish all needed files for admin panel.
## 2. Create Controller
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
## 3. Add record to routes/admin.php
```php
...

AdminRoute::resource(\App\Http\Controllers\Admin\ProductsController::class);
```

## 4. Setup fields in Model
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
    ];
```
##### Available field types:
* text - for simple input type="text"
* textarea - for simple textarea
* select - for one-to-one relationship via select
* select_multiple - for many-to-many relationships via select multiple 

## Advanced Usage

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
