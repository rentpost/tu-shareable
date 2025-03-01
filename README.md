# Transunion ShareAble for Rentals API v3 Client

This is an API client written in PHP for Transunion's ShareAble for Rentals API version 3.

## Getting Started

To use this client, you need to have a valid API key and secret. You can obtain these by signing up for the Transunion ShareAble for Rentals service.

There is a `Makefile` in the root directory of this repository.  You can get a list of available
Make receipes by running `make` in the root directory.  The first command will be `make init`, which will
create a `config` file in the root directory.  You will need to edit this file to add your API key and secret.

If you haven't already registered with Transunion, you'll need to run `make register-with-transunion` to register your API keys with the service.  This only needs to be done once.

## Testing

Run `make test` to run the tests.  Tests will make use of your API credentials defined in your `config` file.

## API Documentation

- [Transunion API Documentation](https://rentals-api-ext.shareable.com/v3/swagger/index.html)
