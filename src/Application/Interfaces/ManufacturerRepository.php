<?php

namespace Application\Interfaces;

interface ManufacturerRepository {
    public function getManufacturerByName(string $mname): ?\Application\Entities\Manufacturer;
    public function getManufacturerById(int $id): ?\Application\Entities\Manufacturer;
    public function createNewManufacturer(string $mname): ?\Application\Entities\Manufacturer;
}