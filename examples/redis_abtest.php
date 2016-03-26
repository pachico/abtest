<?php

/**
 * @author Mariano F.co Benítez Mulet <nanodevel@gmail.com>
 * @copyright (c) 2016, Mariano F.co Benítez Mulet
 */
use \Pachico\Abtest;

require __DIR__ . '/../vendor/autoload.php';

// Let's store in Redis a key with split to see it running
// Uncomment the following two lines to set them
// $redis = new Abtest\Util\RedisConnector('127.0.0.1');
// $redis->set('ABTESTS:test_key', json_encode([50, 50]));

$abtest_engine = new Abtest\Engine(
	new Abtest\Config\FromArray(__DIR__ . '/configuration/redis_split.php')
);
?>

<html>
	<body style="color: #353535; margin: 30px; font-family: Arial;">
		<h1>Simple test</h1>
		<p>These tests use cookies as Memory. Delete cookies to see these values change.</p>

		<?php if ($abtest_engine->getTest('image_test')->isParticipant()) : ?>

			<h2>I participate in Image AB test</h2>

			<?php if (0 === $abtest_engine->getTest('image_test')->getVersion()) : ?>

				<div>
					<p>This is control version (mountain)</p>
					<img src="https://pixabay.com/static/uploads/photo/2015/11/07/11/45/mountain-lake-1031458_960_720.jpg">
				</div>

			<?php elseif (1 === $abtest_engine->getTest('image_test')->getVersion()) : ?>

				<div>
					<p>This is variation 1 (beach)</p>
					<img src="https://pixabay.com/static/uploads/photo/2016/01/19/17/37/sunset-beach-1149800_960_720.jpg">
				</div>

			<?php endif; ?>

		<?php endif; ?>


		<hr>

		<p>And what follows is the Tracking::track() output</p>	

		<textarea style="width: 600px; height: 200px; padding: 10px"><?= htmlspecialchars($abtest_engine->track()); ?></textarea>


	</body>
</html>