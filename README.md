#Pachico/Abtest

[![Build Status](https://travis-ci.org/pachico/abtest.svg?branch=master)](https://travis-ci.org/pachico/abtest)

Flexible AB test suite that accepts custom Segmentation, Split, Memory and Tracking.

It is now in development phase but feel free to check the examples folder, as they are functional.

The package comes with already built in cases but it's very easy to create a new one that will satisfy your technical or marketing requirements.

##Installation
Via composer

	composer require pachico/abtest

## Main concepts

Before diving into examples, it's important to describe the main actors in this package.

### Test
A **Test** is each individual AB test that will be running in your app. This library allowes you to have as many as you want, each one with unique characteristics.

### Split

**Split** classes are the ones that provide a version for each AB test depending on their own logic to each new user.
This library comes with probability implementations. These can be set as a simple array with **ArrayProbability([50, 50])** or can be fetched from Redis for a more dynamic throttling with **RedisArrayProbability()**.

### Memory

We call **Memory** to the class that will store test versions for an user. 
For most cases, storing values in a cookie will do, however, it's a common case to store it in session.
Most frameworks have their own sessions implementation, reason why this library does not come with a built-in one, since chances are they won't be compatible.
If you want/need a specific session implementation, let me know.

### Segmentation

When running tests, you might not want to run it for all your audience.
You might want to do it only to those surfing with a particular device, or language, or country of origin...
It is not mandatory to specify a segmentation but if you need to, you can use the built-in UserAgent based one **ByDevice()**.
If you need a custom segmentation, you can easily write one that will implement SegmentationInterface and inject it.

### Tracking

AB tests are usless if you cannot track their outcome.
Tracking classes are the ones that take care of registering the AB test for analytical purposes.
This library includes a **GoogleExperiments()** class that returns the tracking code to be included in web pages to be tracked in Google Analytics.
More **Tracking** implementations need to use TrackingInterface and simply be injected.

##Examples
Please check the **examples** folder for real case scenarios.

##Contact me

Feel free to contact me for bug fixes, doubts or requests.

Cheers


