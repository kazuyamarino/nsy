<?php

namespace System\Factories;

/**
 * Factory: factory_tmp
 *
 * Build fake rows for the factory_tmp table.
 * Usage: factory('factory_tmp_class', 10)
 */
class factory_tmp_class extends Factory
{
	/**
	 * Default attribute set for one row.
	 *
	 * @return array<string,mixed>
	 */
	public function definition(): array
	{
		return [
			'name'       => $this->faker->name(),
			'created_at' => date('Y-m-d H:i:s'),
		];
	}
}
