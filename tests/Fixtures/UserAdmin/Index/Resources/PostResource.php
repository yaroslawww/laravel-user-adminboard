<?php

namespace UserAdmin\Tests\Fixtures\UserAdmin\Index\Resources;

use UserAdmin\Http\Resources\ListResource;
use UserAdmin\Tests\Fixtures\Models\Post;

class PostResource extends ListResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Post $post */
        $post = $this->resource;

        return [
            'id'            => $post->getKey(),
            'title'         => $post->title,
            /* Actions data */
            'actions_meta' => [
                'edit' => [
                    'url' => 'edit/' . $post->getKey(),
                ],
                'preview' => [
                    'url' => 'preview/' . $post->getKey(),
                ],
            ],
        ];
    }
}
