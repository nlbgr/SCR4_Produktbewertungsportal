<?php

namespace Application\Entities;

class Rating {
    function __construct(
        private int $id,
        private string $datetime,
        private string $comment,
        private int $grade,
        private string $user,
        private int $productId
    ) { }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function getGrade(): int
    {
        return $this->grade;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }


}