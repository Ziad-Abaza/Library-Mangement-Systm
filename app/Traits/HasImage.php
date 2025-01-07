<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasImage
{
    use InteractsWithMedia;

    /**
     * Register media collections.
     *
     * @param string $collectionName
     * @param bool $singleFile
     * @param string|null $fallbackUrl
     * @param string|null $fallbackPath
     * @return void
     */
    protected function registerImageCollection(string $collectionName = 'image', bool $singleFile = true, string $fallbackUrl = null, string $fallbackPath = null): void
    {
        $collection = $this->addMediaCollection($collectionName)
            ->useDisk('public'); // Adjust disk as needed

        if ($singleFile) {
            $collection->singleFile();
        }

        if ($fallbackUrl && $fallbackPath) {
            $collection->useFallbackUrl($fallbackUrl)
                ->useFallbackPath($fallbackPath);
        }
    }

    /**
     * Register media conversions.
     *
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued(); // Run conversions synchronously

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();
    }

    /**
     * Get the URL of the image with a specific conversion.
     *
     * @param string $collection
     * @param string $conversion
     * @return string
     */
    public function getImageUrl(string $collection = 'image', string $conversion = ''): string
    {
        if ($conversion) {
            return $this->getFirstMediaUrl($collection, $conversion);
        }

        return $this->getFirstMediaUrl($collection);
    }
}
