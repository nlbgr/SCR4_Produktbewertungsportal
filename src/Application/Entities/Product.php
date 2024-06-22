<?php

namespace Application\Entities;

class Product {
    public function __construct(
        private int $id,
        private string $name,
        private string $userName,
        private string $manufacturerName
    ) {}

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getManufacturerName(): string
    {
        return $this->manufacturerName;
    }



}