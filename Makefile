#!/usr/bin/make -f

SHELL:=/bin/bash

.PHONY: help init test


# Test that we have the necessary binaries available
define checkExecutables
    $(foreach exec,$(1),\
		$(if $(shell command -v $(exec)),,$(error Unable to find `$(exec)` in your PATH)))
endef

test := $(call checkExecutables, composer)


# Note that all comments with two hashes(#) will be used for output with `make help`. Alignment is tricky!
help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


##
## This is a list of available make commands that can be run.
##


init:               ## Initializes the project and all dependencies
	@composer install


install-vendors:    ## Installs vendor dependencies
	$(call checkExecutables, composer)
	@composer install


update-vendors:     ## Updates vendor dependencies
	$(call checkExecutables, composer)
	@composer update


test:               ## Executes the test suites
	$(call checkExecutables, phpunit)
	@phpunit
