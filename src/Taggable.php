<?php

namespace Denismitr\Tagging;

use Denismitr\Tagging\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait Taggable
{
    use TaggableScopes, TagUsedScopes;


    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }


    public function tag($tags)
    {
        $this->addTags(
            $this->getWorkableTags($tags)
        );
    }


    public function untag($tags = null)
    {
        if ($tags === null) {
            $this->removeAllTags();

            return;
        }

        $this->removeTags(
            $this->getWorkableTags($tags)
        );
    }


    public function retag($tags)
    {
        $this->removeAllTags();

        $this->tag($tags);
    }


    private function removeTags(Collection $tags)
    {
         $this->tags()->detach($tags);

         foreach($tags->where('count', '>', 0) as $tag) {
            $tag->decrement('count');
         }
    }


    private function removeAllTags()
    {
        $this->removeTags($this->tags);
    }


    private function addTags(Collection $tags)
    {
        $newlySynced = $this->tags()->syncWithoutDetaching(
            $tags->pluck('id')->toArray()
        );

        foreach (array_get($newlySynced, 'attached') as $attachedId) {
            $tags->where('id', $attachedId)->first()->increment('count');
        }
    }


    private function getWorkableTags($tags)
    {
        if ( is_array($tags) ) {
            return $this->getTagModels($tags);
        }

        if ( $tags instanceof Model ) {
            return $this->getTagModels([$tags->slug]);
        }

        return $this->filterTagsColection($tags);
    }


    private function filterTagsColection(Collection $tags)
    {
        return $tags->filter(function($tag) {
            return $tag instanceof Model;
        });
    }


    private function getTagModels(array $tags)
    {
        return Tag::whereIn('slug', $this->normalizeTagNames($tags))->get();
    }


    private function normalizeTagNames(array $tags)
    {
        return array_map(function($tag) {
            return str_slug($tag);
        }, $tags);
    }
}
