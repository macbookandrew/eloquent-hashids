<?php

namespace Mtvs\EloquentHashids\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mtvs\EloquentHashids\Tests\Models\Item;
use Vinkla\Hashids\Facades\Hashids;

class HashidRoutingTest extends TestCase
{
	/**
	 * @test
	 */
	public function it_can_resolve_a_hashid_in_a_linked_route_binding()
	{
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->getKey());

		Route::model('item', Item::class);

		Route::get('/item/{item}', function ($binding) use ($item) {
			$this->assertEquals($item->id, $binding->id);
		})->middleware('bindings');

		$this->get("/item/$hashid");
	}

	/**
	 * @test
	 */
	public function it_supports_specifying_a_field_in_the_route_binding()
	{
		$given = factory(Item::class)->create(['slug' => 'item-1']);

		Route::get('/item/{item:slug}', function (Item $item) use ($given) {
			$this->assertEquals($given->id, $item->id);
		})->middleware('bindings');

		$this->get("/item/item-1");
	}

	/**
	 * @test
	 */
	public function it_returns_the_hashid_of_a_model_as_its_route_key()
	{
		$item = factory(Item::class)->create();

		$hashid = Hashids::encode($item->id);

		$this->assertEquals($hashid, $item->getRouteKey());
	}
}
