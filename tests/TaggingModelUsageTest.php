<?php

class TaggingModelUsageTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        foreach (['PHP', 'Laravel', 'Testing', 'Redis', 'Postgres', 'Fun stuff'] as $tag) {
            \TagStub::create([
               'name' => $tag,
               'slug' => str_slug($tag),
               'count' => 0
            ]);
        }

        $this->lesson = \LessonStub::create([
            'title' => 'A lesson title'
        ]);
    }

    /** @test */
    public function it_can_tag_a_lesson()
    {
        $this->lesson->tag(\TagStub::where('slug', 'laravel')->first());

        $this->assertCount(1, $this->lesson->tags);
        $this->assertContains('Laravel', $this->lesson->tags->pluck('name'));
    }


    /** @test */
    public function it_can_tag_lesson_with_collection_of_tags()
    {
        $tags = \TagStub::whereIn('slug', ['php', 'laravel', 'testing', 'redis'])->get();

        $this->lesson->tag($tags);

        $this->assertCount(4, $this->lesson->tags);
        $this->assertIn(['PHP', 'Laravel', 'Testing', 'Redis'], $this->lesson->tags->pluck('name'));
    }


    /** @test */
    public function it_can_untag_lesson_tags()
    {
        $tags = \TagStub::whereIn('slug', ['php', 'laravel', 'testing', 'redis'])->get();

        $this->lesson->tag($tags);

        //Using Laravel
        $this->lesson->untag($tags->first());

        $this->assertCount(3, $this->lesson->tags);
        $this->assertIn(['PHP', 'Testing', 'Redis'], $this->lesson->tags->pluck('name'));
    }


    /** @test */
    public function it_can_untag_all_lesson_tags()
    {
        $tags = \TagStub::whereIn('slug', ['php', 'laravel', 'testing', 'redis'])->get();

        $this->lesson->tag($tags);

        $this->lesson->untag();

        $this->lesson->load('tags');

        $this->assertCount(0, $this->lesson->tags);
    }


    /** @test */
    public function it_can_retag_by_new_collection()
    {
        $tags = \TagStub::whereIn('slug', ['php', 'laravel', 'fun-stuff'])->get();
        $retags = \TagStub::whereIn('slug', ['laravel', 'testing', 'redis'])->get();

        $this->lesson->tag($tags);
        $this->lesson->retag($retags);

        $this->lesson->load('tags');

        $this->assertCount(3, $this->lesson->tags);
        $this->assertIn(['Laravel', 'Testing', 'Redis'], $this->lesson->tags->pluck('name'));
    }


    /** @test */
    public function it_filters_out_non_models()
    {
        $tags = \TagStub::whereIn('slug', ['php', 'laravel', 'fun-stuff'])->get();
        $tags->push('does not make sense!');

        $this->lesson->tag($tags);

        $this->assertCount(3, $this->lesson->tags);
    }
}
