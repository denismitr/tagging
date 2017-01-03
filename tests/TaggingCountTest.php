<?php

class TaggingCountTest extends TestCase
{
    protected $lesson;

    public function setUp()
    {
        parent::setUp();

        $this->lesson = \LessonStub::create([
            'title' => 'A lesson title'
        ]);
    }


    /** @test */
    public function it_increments_count_when_tagging()
    {
        $tag = \TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 0
        ]);

        $this->lesson->tag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(1, $tag->count);
    }


    /** @test */
    public function it_decrements_count_when_untagging()
    {
        $tag = \TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 70
        ]);

        $this->lesson->tag(['laravel']);
        $this->lesson->untag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(70, $tag->count);
    }


    /** @test */
    public function it_does_not_allow_count_to_dip_below_zero()
    {
        $tag = \TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 0
        ]);

        $this->lesson->untag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(0, $tag->count);
    }


    /** @test */
    public function it_does_not_increment_twice_on_same_model()
    {
        $tag = \TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 70
        ]);

        $this->lesson->tag(['laravel']);
        $this->lesson->tag(['laravel']);
        $this->lesson->tag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(71, $tag->count);
    }
}
