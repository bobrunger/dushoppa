<?php

declare(strict_types=1);

namespace Application\Model;

class Product
{

    public $id;
    public $name;
    public $description;
    public $price;
    public $image;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->description = !empty($data['description']) ? $data['description'] : null;
        $this->price = !empty($data['price']) ? $data['price'] : null;
        $this->image = !empty($data['image_url']) ? $data['image_url'] : null;
    }
}
