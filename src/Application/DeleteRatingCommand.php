<?php

namespace Application;

class DeleteRatingCommand {
    const Error_NotAuthenticated = 0x01;
    const Error_DeleteProductFailed = 0x02;

    public function __construct(
        private Interfaces\RatingsRepository $ratingsRepository,
        private Services\AuthenticationService $authenticationService
    ) {}

    public function execute(int $ratingId) {
        $errors = 0;

        $userId = $this->authenticationService->getUserId();
        if ($userId === null) {
            $errors |= self::Error_NotAuthenticated;
            return $errors;
        }

        $result = $this->ratingsRepository->deleteRating($ratingId, $userId);
        if (!$result) {
            $errors |= self::Error_DeleteProductFailed;
        }

        return $errors;
    }

}