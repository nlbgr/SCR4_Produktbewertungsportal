<?php

namespace Application;

class EditRatingCommand {
    const Error_NotAuthenticated = 0x01;
    const Error_RatingDoesNotExist = 0x02;
    const Error_CreateProductFailed = 0x03;

    public function __construct(
        private Interfaces\RatingsRepository $ratingsRepository,
        private Services\AuthenticationService $authenticationService,
    ) {}

    public function execute(int $ratingId, int $grade, string $comment, int $prodId): int {
        $comment = trim($comment);

        $errors = 0;

        $userId = $this->authenticationService->getUserId();
        if ($userId === null) {
            $errors |= self::Error_NotAuthenticated;
            return $errors;
        }

        $r = $this->ratingsRepository->editRating($ratingId, $grade, $comment, $userId, $prodId);
        if ($r === null) {
            $errors |= self::Error_CreateProductFailed;
        }


        return $errors;
    }

}