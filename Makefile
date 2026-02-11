#!/usr/bin/make -f

SHELL:=/bin/bash



# Test that we have the necessary binaries available
define checkExecutables
    $(foreach exec,$(1),\
		$(if $(shell command -v $(exec)),,$(error Unable to find `$(exec)` in your PATH)))
endef

test := $(call checkExecutables, composer)


# Note that all comments with two hashes(#) will be used for output with `make help`. Alignment is tricky!
.PHONY: help
help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


##
## This is a list of available make commands that can be run.
##


.PHONY: init
init:                       ## Initializes the project and all dependencies
	@composer install
	@cp ./config.template ./config
	@echo "Config template copied. Please edit the ./config file with your Transunion API credentials."


.PHONY: install-vendors
install-vendors:            ## Installs vendor dependencies
	$(call checkExecutables, composer)
	@composer install


.PHONY: update-vendors
update-vendors:             ## Updates vendor dependencies
	$(call checkExecutables, composer)
	@composer update


.PHONY: register-with-transunion
register-with-transunion:   ## Registers the API key with Transunion
	@bin/register


.PHONY: check-status
check-status:               ## Checks the status of the Transunion API
	@bin/getStatus

.PHONY: test
test:                       ## Executes the test suites
	$(call checkExecutables, phpunit)
	@phpunit
