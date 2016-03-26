<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */

namespace Pachico\Abtest\Segmentation;

/**
 *
 * All Segmentation classes must implement
 * this interface
 */
interface SegmentatIoninterface
{

	/**
	 * return boolean
	 */
	public function isParticipant();
}
