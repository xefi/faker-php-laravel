<?php

namespace Xefi\Faker\Laravel\Tests\Unit;

use Xefi\Faker\Faker;

class HelperTest extends TestCase
{
    public function testFakerHelper() {
        $faker = faker();
        
        $this->assertInstanceOf(Faker::class, $faker);
    }

    public function testFakerHelperWithNullLocale() {
        $faker = faker(null);

        $this->assertEquals(null, $faker->getLocale());
    }
}