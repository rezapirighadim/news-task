<?php

namespace App\Services;
use App\Models\UserPreference;
use App\Repositories\ArticleRepository;
use App\Services\ArticleService;

class UserPreferenceService
{
    public function getPreferences(int $userId)
    {
        return UserPreference::where('user_id', $userId)->first();
    }

    public function updatePreferences(int $userId, array $data)
    {
        return UserPreference::updateOrCreate(
            ['user_id' => $userId],
            [
                'preferred_sources' => $data['preferred_sources'] ?? null,
                'preferred_categories' => $data['preferred_categories'] ?? null,
                'preferred_authors' => $data['preferred_authors'] ?? null,
            ]
        );
    }

    public function getPersonalizedArticles(int $userId, array $filters)
    {
        $preferences = $this->getPreferences($userId);

        if (!$preferences) {
            return (new ArticleService(new ArticleRepository()))->search($filters);
        }

        // Merge user preferences with filters
        if (empty($filters['source']) && !empty($preferences->preferred_sources)) {
            $filters['sources'] = $preferences->preferred_sources;
        }

        if (empty($filters['category']) && !empty($preferences->preferred_categories)) {
            $filters['categories'] = $preferences->preferred_categories;
        }

        return (new ArticleService(new ArticleRepository()))->search($filters);
    }
}
