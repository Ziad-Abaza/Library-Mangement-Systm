<?php

namespace App\Constants;

class MediaConstants
{
    /*
    |--------------------------------------------------------------------------
    | Default Media Paths
    |--------------------------------------------------------------------------
    | This section contains the default image paths used across the application 
    | for various media entities (such as user profiles, authors, books, etc.)
    | when no specific image is provided by the user.
    */
    public const DEFAULT_USER_IMAGE = '/assets/images/static/person.png'; // Default image for user profiles
    public const DEFAULT_AUTHOR_IMAGE = '/assets/images/static/person.png'; // Default image for authors
    public const DEFAULT_AUTHORREQUEST_IMAGE = '/assets/images/static/person.png'; // Default image for author requests
    public const DEFAULT_BOOK_IMAGE = '/assets/images/static/book.png'; // Default image for books
    public const DEFAULT_BOOKSERIES_IMAGE = '/assets/images/static/bookseries.png'; // Default image for book series
    public const DEFAULT_PUBLICATIONREQUEST_IMAGE = '/assets/images/static/publicationrequest.png'; // Default image for publication requests

    /*
    |--------------------------------------------------------------------------
    | Media Conversion Types
    |--------------------------------------------------------------------------
    | This section defines the names used for different image conversion sizes
    | such as thumbnail, medium, etc. These constants are used for converting
    | images to various sizes as needed by the application.
    */
    public const MEDIA_COLLECTION_IMAGE = 'image'; // Collection name for image media
    public const MEDIA_CONVERSION_THUMB = 'thumb'; // Conversion type for thumbnail size
    public const MEDIA_CONVERSION_MEDIUM = 'medium'; // Conversion type for medium size
}
