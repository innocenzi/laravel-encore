<?php

use Illuminate\Support\Facades\Blade;
use Innocenzi\LaravelEncore\EncoreServiceProvider;
use Innocenzi\LaravelEncore\Tests\TestCase;

class BladeDiretivesTest extends TestCase
{
    protected array $directives = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->directives = Blade::getCustomDirectives();
    }

    /** @test **/
    public function it_creates_directives()
    {
        $this->assertArrayHasKey(EncoreServiceProvider::SCRIPTS_BLADE_DIRECTIVE, $this->directives);
        $this->assertArrayHasKey(EncoreServiceProvider::STYLES_BLADE_DIRECTIVE, $this->directives);
    }

    /** @test **/
    public function it_parses_script_directive_arguments()
    {
        $this->assertEquals(
            '<?php echo Innocenzi\LaravelEncore\Facade\Encore::getScriptTags(e("scripts"),e(false)); ?>',
            $this->directives[EncoreServiceProvider::SCRIPTS_BLADE_DIRECTIVE]('"scripts", false')
        );

        $this->assertEquals(
            '<?php echo Innocenzi\LaravelEncore\Facade\Encore::getScriptTags(e("scripts"),e(true)); ?>',
            $this->directives[EncoreServiceProvider::SCRIPTS_BLADE_DIRECTIVE]('"scripts", true')
        );

        $this->assertEquals(
            '<?php echo Innocenzi\LaravelEncore\Facade\Encore::getScriptTags(e("app"),e(false)); ?>',
            $this->directives[EncoreServiceProvider::SCRIPTS_BLADE_DIRECTIVE]('')
        );
    }

    /** @test **/
    public function it_parses_link_directive_arguments()
    {
        $this->assertEquals(
            '<?php echo Innocenzi\LaravelEncore\Facade\Encore::getLinkTags(e("link")); ?>',
            $this->directives[EncoreServiceProvider::STYLES_BLADE_DIRECTIVE]('"link"')
        );

        $this->assertEquals(
            '<?php echo Innocenzi\LaravelEncore\Facade\Encore::getLinkTags(e("app")); ?>',
            $this->directives[EncoreServiceProvider::STYLES_BLADE_DIRECTIVE]('')
        );
    }
}
