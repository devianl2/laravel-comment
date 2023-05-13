<?php

return [
    'enable_default_rating' => true, // If false, default rating will be null for models that don't have any comments
    'default_rating' => 5, // Default rating for models that don't have any comments

    /**
     * Ratings for commentable models. You can add more ratings here.
     * The key is the rating value and the value is the rating label.
     * You can use the key in your code to get the label.
     * The key is also used to calculate the average rating.
     * The key must be an integer and ascending order.
     */
    'ratings' => [ 
        1 => 'Very Bad',
        2 => 'Bad',
        3 => 'Average',
        4 => 'Good',
        5 => 'Very Good',
    ],
];
