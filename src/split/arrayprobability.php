<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Split;

/**
 * This class establishes the split of traffic in probabilities
 * ArrayProbability([50,50]) means there's 50% chance to 
 * fall in control or variation.
 * 
 * However, ArrayProbability([50,50,50]) means each of the 3 versions
 * have a 33% of chance
 */
class ArrayProbability implements SplitInterface
{

	/**
	 *
	 * @var array
	 */
	protected $_splits = [];

	/**
	 *
	 * @var int
	 */
	protected $_random_chance;

	/**
	 *
	 * @throws \BadMethodCallException
	 */
	public function __construct(array $percentages, $random_chance = null)
	{
		foreach ($percentages as $percentage)
		{
			if (!is_numeric($percentage))
			{
				throw new \BadMethodCallException('Probabilities must be set in integers');
			}

			$this->_splits[] = (int) abs($percentage);
		}

		$this->_random_chance = is_numeric($random_chance)
			? (int) $random_chance
			: mt_rand(0, array_sum($this->_splits));
	}

	/**
	 * 
	 * @return int
	 */
	public function createVersion()
	{

		$chosen_version = false;

		$start_point = 0;

		foreach ($this->_splits as $version => $probability)
		{
			if ($this->_random_chance >= $start_point && $this->_random_chance < ($start_point + $probability))
			{
				$chosen_version = $version;
				break;
			}

			$start_point += $probability;
		}

		// Security fallback

		if (false === $chosen_version)
		{
			$chosen_version = 0;
		}

		return $chosen_version;
	}

}
