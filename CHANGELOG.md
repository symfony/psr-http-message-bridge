CHANGELOG
=========

6.4
---

 * Import the bridge into the Symfony monorepo and synchronize releases
 * Remove `ArgumentValueResolverInterface` from `PsrServerRequestResolver`
 * Support `php-http/discovery` for auto-detecting PSR-17 factories

2.3.1
-----

 * Don't rely on `Request::getPayload()` to populate the parsed body

2.3.0
-----

 * Leverage `Request::getPayload()` to populate the parsed body of PSR-7 requests
 * Implement `ValueResolverInterface` introduced with Symfony 6.2

2.2.0
-----

 * Drop support for Symfony 4
 * Bump minimum version of PHP to 7.2
 * Support version 2 of the psr/http-message contracts

2.1.3
-----

 * Ignore invalid HTTP headers when creating PSR7 objects
 * Fix for wrong type passed to `moveTo()`

2.1.2
-----

 * Allow Symfony 6

2.1.0
-----

 * Added a `PsrResponseListener` to automatically convert PSR-7 responses returned by controllers
 * Added a `PsrServerRequestResolver` that allows injecting PSR-7 request objects into controllers

2.0.2
-----

 * Fix populating server params from URI in HttpFoundationFactory
 * Create cookies as raw in HttpFoundationFactory
 * Fix BinaryFileResponse with Content-Range PsrHttpFactory

2.0.1
-----

 * Don't normalize query string in PsrHttpFactory
 * Fix conversion for HTTPS requests
 * Fix populating default port and headers in HttpFoundationFactory

2.0.0
-----

 * Remove DiactorosFactory

1.3.0
-----

 * Added support for streamed requests
 * Added support for Symfony 5.0+
 * Fixed bridging UploadedFile objects
 * Bumped minimum version of Symfony to 4.4

1.2.0
-----

 * Added new documentation links
 * Bumped minimum version of PHP to 7.1
 * Added support for streamed responses

1.1.2
-----

 * Fixed createResponse

1.1.1
-----

 * Deprecated DiactorosFactory, use PsrHttpFactory instead
 * Removed triggering of deprecation

1.1.0
-----

 * Added support for creating PSR-7 messages using PSR-17 factories

1.0.2
-----

 * Fixed request target in PSR7 Request (mtibben)

1.0.1
-----

 * Added support for Symfony 4 (dunglas)

1.0.0
-----

 * Initial release
