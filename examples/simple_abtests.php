<?php

use \Pachico\Abtest;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Abtest\Config\Chainable(
	new Abtest\Memory\Cookie('my_abtest_cookis', -1), new Abtest\Tracking\GoogleExperiments(true)
);

$configurator->addTest('colour', new Abtest\Split\ArrayProbability([50, 50]), null, 'colour_tracking_id');
$configurator->addTest('size', new Abtest\Split\ArrayProbability([50, 50]), null, 'size_tracking_id');

$abtest_engine = new Abtest\Engine($configurator);
?>

<html>
	<body style="color: #353535; margin: 30px; font-family: Arial;">
		<h1>Simple test</h1>
		<p>These tests use cookies as Memory. Delete cookies to see these values change.</p>

		<?php if ($abtest_engine->getTest('colour')->isParticipant()) : ?>

			<h2>I participate in Colour AB test</h2>

			<?php if (0 === $abtest_engine->getTest('colour')->getVersion()) : ?>

				<p style="color: black">This is control version (black)</p>

			<?php elseif (1 === $abtest_engine->getTest('colour')->getVersion()) : ?>

				<p style="color: blue">This is variation 1 (blue)</p>

			<?php endif; ?>

		<?php endif; ?>

		<hr>

		<?php if ($abtest_engine->getTest('size')->isParticipant()) : ?>

			<h2>I participate in Size AB test</h2>

			<?php if (0 === $abtest_engine->getTest('size')->getVersion()) : ?>

				<p style="font-size: 14px">This is control version (14px)</p>

			<?php elseif (1 === $abtest_engine->getTest('size')->getVersion()) : ?>

				<p style="font-size: 20px">This is variation 1 (20px)</p>

			<?php endif; ?>

		<?php endif; ?>

		<hr>

		<p>And what follows is the Tracking::track() output</p>	

		<textarea style="width: 600px; height: 200px; padding: 10px"><?= htmlspecialchars($abtest_engine->track()); ?></textarea>


	</body>
</html>