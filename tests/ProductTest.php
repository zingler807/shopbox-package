<?php
// run tests by changing to this package directory & typing into console  vendor/bin/phpunit
namespace Tests\Feature;

use Orchestra\Testbench\TestCase;

class ProductTest extends TestCase
{

  protected $baseUrl = 'http://shop.test';
    protected function setUp(): void
    {
      parent::setUp();

      // Your code here
    }

  /** @test */
  public function it_doesnt_add_product_without_title(){
      $response = $this->call('GET','/');
     $response->assertStatus(422);
  }
}


 ?>
