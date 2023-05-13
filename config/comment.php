<?php

return [
    'enable_default_rating' => true, // If false, default rating will be null for models that don't have any comments
    'default_rating' => 5, // Default rating for models that don't have any comments
    'ratings' => [ // Ratings for commentable models. You can add more ratings here.
        1 => 'Very Bad',
        2 => 'Bad',
        3 => 'Average',
        4 => 'Good',
        5 => 'Very Good'
    ],
];
