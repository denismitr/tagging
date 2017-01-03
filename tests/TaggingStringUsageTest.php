<?php

class TaggingStringUsageTest extends TestCase
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
    public function it_can_tag_lesson()
    {
        $this->lesson->tag(['laravel', 'php']);

        $this->assertCount(2, $this->lesson->tags);

        foreach (['PHP', 'Laravel'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }


    /** @test */
    public function it_can_untag_lesson()
    {
        $this->lesson->tag(['laravel', 'php', 'Redis', 'Postgres']);
        $this->lesson->untag(['laravel', 'php', 'Redis']);

        $this->assertCount(1, $this->lesson->tags);

        foreach (['Postgres'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /** @test */
    public function it_untag_all_lesson_tags()
    {
        $this->lesson->tag(['laravel', 'php', 'Redis', 'Postgres']);
        $this->lesson->untag();

        $this->lesson->load('tags');

        $this->assertCount(0, $this->lesson->tags);
        $this->assertEquals(0, $this->lesson->tags->count());
    }


    /** @test */
    public function it_can_retag_lesson_tags()
    {
        $this->lesson->tag(['laravel', 'php', 'Redis', 'Postgres']);
        $this->lesson->retag(['laravel', 'Fun stuff', 'Redis']);

        $this->lesson->load('tags');

        $this->assertCount(3, $this->lesson->tags);

        // foreach (['Laravel', 'Fun stuff', 'Redis'] as $tag) {
        //     $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        // }
        $this->assertIn(['Laravel', 'Fun stuff', 'Redis'], $this->lesson->tags->pluck('name'));
    }


    /** @test */
    public function it_ignores_non_existing_tags_on_tagging()
    {
        $this->lesson->tag(['laravel', 'C++', 'Redis']);

        $this->assertCount(2, $this->lesson->tags);

        $this->assertIn(['Laravel', 'Redis'], $this->lesson->tags->pluck('name'));
    }


    /** @test */
    public function it_inconsistent_tag_cases_are_normalized()
    {
        $this->lesson->tag(['laRavel', 'REdis', 'teSTing', 'Fun stuff']);

        $this->assertCount(4, $this->lesson->tags);

        $this->assertIn(['Laravel', 'Fun stuff', 'Redis', 'Testing'], $this->lesson->tags->pluck('name'));
    }
}
