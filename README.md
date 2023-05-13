# Laravel Comment and Rating Package

This Laravel package provides a robust solution for managing comments and ratings in your Laravel application. It allows you to easily perform the following actions:


## Installation

You can install the package via Composer by running the following command:

```bash

composer require devianl2/laravel-comment
```

## Usage

To use this package, you need to follow these steps: 
1. **Set up the package** : After installation, make sure to publish the package configuration and migrations.

```bash
php artisan vendor:publish --provider="Devianl2\CommentRateable\CommentRateableServiceProvider" --tag="migrations"
php artisan migrate
``` 
2. **Add the trait to your Post model** : In order to enable comment and rating functionality, add the `CommentRateable` trait to your Post model.

```php

use Devianl2\CommentRateable\Traits\CommentRateable;

class Post extends Model
{
    use CommentRateable;
}
``` 
3. **Perform actions** : You can now use the available methods on your Post model to perform actions, such as retrieving comments, getting average ratings, or deleting comments.

## Use Case

### Save Comment
```php
$post = Post::first();
$comment = $post->comment([
    'title' => 'This is a test title',
    'body' => 'And we will add some shit here',
    'rating' => 5,
    'is_approved' => true, // This is optional and defaults to false
], $user);
```

### Update Comment
```php
$post = Post::first();
$comment = $post->updateComment(
    $commentId,
    [
        'title' => 'This is a test title',
        'body' => 'And we will add some shit here',
        'rating' => 5,
        'is_approved' => true, // This is optional and defaults to false
    ], $user);
```

### Other Methods
1. Get average rating: Retrieve the average rating for a post.
```php
$post = Post::first();
$post->averageRating();
```

2. Get comments: Retrieve all comments for a post.

```php

$post->getComments();
``` 
3. Get approved comments: Retrieve only approved comments for a post.

```php

$post->getComments(true);
``` 
4. Get paginated approved comments: Retrieve approved comments for a post with pagination.

```php

$post->getComments(true, true);
``` 
5. Get all comments without pagination: Retrieve all comments for a post without pagination and limits. (Set the limit to 0 for unlimited comments)

```php

$post->getComments(true, false, 0);
``` 
6. Delete comment: Delete a comment.

```php

$comment->delete();
```


## Contribution

Contributions to this package are always welcome. If you find any issues or want to add new features, please create an issue or submit a pull request on the GitHub repository of this package.
## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT) .
