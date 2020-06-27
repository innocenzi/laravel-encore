<?php

use Illuminate\Support\Facades\Config;
use Innocenzi\LaravelEncore\Facade\Encore;
use Innocenzi\LaravelEncore\Tests\TestCase;

class EncoreTest extends TestCase
{
    /** @test **/
    public function it_generates_script_tags_from_entrypoints()
    {
        Config::set('encore.output_path', 'build');
        Config::set('encore.files.entrypoints', 'entrypoints.json');

        Encore::shouldReceive('readJsonFile')
            ->once()
            ->with(\config('encore.files.entrypoints'))
            ->andReturn([
                'entrypoints' => [
                    'app' => [
                        'js' => [
                            '/' . config('encore.output_path') . '/app.js',
                            '/' . config('encore.output_path') . '/test.js',
                        ],
                    ],
                ],
            ]);
        Encore::makePartial();

        $this->assertEquals(
            '<script src="/build/app.js" defer></script><script src="/build/test.js" defer></script>',
            Encore::getScriptTags('app')
        );
    }

    /** @test **/
    public function it_generates_link_tags_from_entrypoints()
    {
        Config::set('encore.output_path', 'build');
        Config::set('encore.files.entrypoints', 'entrypoints.json');

        Encore::shouldReceive('readJsonFile')
            ->once()
            ->with(\config('encore.files.entrypoints'))
            ->andReturn([
                'entrypoints' => [
                    'app' => [
                        'css' => [
                            '/' . config('encore.output_path') . '/app.css',
                            '/' . config('encore.output_path') . '/admin.css',
                        ],
                    ],
                ],
            ]);
        Encore::makePartial();

        $this->assertEquals(
            '<link rel="stylesheet" href="/build/app.css"/><link rel="stylesheet" href="/build/admin.css"/>',
            Encore::getLinkTags('app')
        );
    }

    /** @test **/
    public function it_returns_assets_from_manifest()
    {
        Config::set('encore.output_path', 'build');
        Config::set('encore.files.manifest', 'manifest.json');

        Encore::shouldReceive('readJsonFile')
            ->times(3)
            ->with(\config('encore.files.manifest'))
            ->andReturn([
                'build/0.17df4183.js' => '/build/0.17df4183.js',
                'build/app.js'        => '/build/app.d158d12f.js',
                'build/runtime.js'    => '/build/runtime.420770e4.js',
                'build/image.png'     => '/build/image.z456g32z.png',
            ]);
        Encore::makePartial();

        $this->assertEquals('/build/app.d158d12f.js', Encore::asset('build/app.js'));
        $this->assertEquals('/build/runtime.420770e4.js', Encore::asset('build/runtime.js'));
        $this->assertEquals('/build/image.z456g32z.png', Encore::asset('build/image.png'));
    }
}
