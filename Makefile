# 95th Bomb Group Heritage — Red Feather Club
#
# The site is built with Hugo (brew install hugo); the WordPress theme in
# wordpress-theme/ needs PHP to lint. PHP is managed with asdf and pinned in
# .tool-versions.
#
#   make dev      build, serve and open the site in a browser
#   make serve    live-reloading preview of the site
#   make build    build the site into public/
#   make init     one-time setup: install PHP via asdf
#   make lint     php -l over every theme file
#   make zip      rebuild the uploadable theme package
#
# asdf itself is assumed to be installed already.

SHELL := /bin/bash
.DEFAULT_GOAL := help

PHP_SERIES ?= 8.3
PORT       ?= 1313

THEME_DIR := wordpress-theme/red-feather-club
THEME_ZIP := wordpress-theme/red-feather-club.zip

# Homebrew keg-only libraries the PHP build has to be pointed at explicitly.
BREW := $(shell command -v brew 2>/dev/null)
ifneq ($(BREW),)
BREW_OPT      := $(shell brew --prefix)/opt
PHP_BUILD_ENV := PATH="$(BREW_OPT)/bison/bin:$(BREW_OPT)/re2c/bin:$(PATH)" \
                 PKG_CONFIG_PATH="$(BREW_OPT)/icu4c/lib/pkgconfig:$(BREW_OPT)/libxml2/lib/pkgconfig:$(BREW_OPT)/openssl@3/lib/pkgconfig:$(BREW_OPT)/krb5/lib/pkgconfig:$(BREW_OPT)/libedit/lib/pkgconfig:$(BREW_OPT)/zlib/lib/pkgconfig:$(BREW_OPT)/libzip/lib/pkgconfig:$(PKG_CONFIG_PATH)"
BREW_DEPS := autoconf automake bison freetype gd gettext icu4c krb5 libedit \
             libiconv libjpeg libpng libxml2 libzip openssl@3 pkgconf re2c zlib

# Pin the configure line ourselves. asdf-php's own macOS option builder is
# unusable: os_based_configure_options() echoes diagnostics ("gmp not found,
# not including in installation") inside the function whose stdout *is* the
# option string, so those words land on the ./configure command line and the
# build dies with "Invalid configuration 'gmp'". It also still looks for
# openssl@1.1. Setting PHP_CONFIGURE_OPTIONS skips that branch entirely.
#
# This is a lean CLI build — enough for `php -l`, PHPCS and WP-CLI, without
# dragging in the extensions most likely to fail to configure.
PHP_CONFIGURE_OPTIONS ?= --enable-cli --disable-cgi --disable-fpm --disable-phpdbg \
                         --enable-mbstring --enable-bcmath --with-zlib \
                         --with-openssl=$(BREW_OPT)/openssl@3 \
                         --with-iconv=$(BREW_OPT)/libiconv
PHP_BUILD_ENV += PHP_CONFIGURE_OPTIONS="$(PHP_CONFIGURE_OPTIONS)" \
                 PHP_WITHOUT_PEAR=yes PHP_WITHOUT_PDO_PGSQL=yes
endif

.PHONY: help init deps php lint zip dev serve build hugo

help: ## Show the available targets
	@echo "Red Feather Club — make targets:"
	@echo
	@grep -hE '^[a-zA-Z_-]+:.*?## ' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[1m%-8s\033[0m %s\n", $$1, $$2}'
	@echo

init: deps php ## One-time setup — install the pinned PHP toolchain via asdf

deps: ## Install the Homebrew libraries the PHP build needs (macOS, no-op elsewhere)
ifneq ($(BREW),)
	@echo "==> Homebrew build prerequisites"
	@brew install --quiet $(BREW_DEPS)
else
	@echo "==> No Homebrew found; skipping build prerequisites."
	@echo "    On Debian/Ubuntu see: https://github.com/asdf-community/asdf-php#install-dependencies"
endif

php: ## Install the PHP pinned in .tool-versions (compiles from source — slow)
	@command -v asdf >/dev/null 2>&1 || { \
		echo "asdf is not on PATH. Install it first: https://asdf-vm.com"; exit 1; }
	@asdf plugin list 2>/dev/null | grep -qx php || { \
		echo "==> Adding the asdf php plugin"; \
		asdf plugin add php https://github.com/asdf-community/asdf-php.git; }
	@grep -q '^php ' .tool-versions 2>/dev/null || { \
		version=$$(asdf latest php $(PHP_SERIES)) && \
		echo "==> Pinning php $$version in .tool-versions" && \
		asdf set php "$$version"; }
	@version=$$(awk '$$1 == "php" { print $$2 }' .tool-versions); \
	if [ -x "$$(asdf where php $$version 2>/dev/null)/bin/php" ]; then \
		echo "==> php $$version already installed"; \
	else \
		echo "==> Compiling php $$version — this takes several minutes"; \
		$(PHP_BUILD_ENV) asdf install php "$$version" || { \
			echo; \
			echo "PHP failed to build. Run 'make deps' to install the prerequisites, then retry."; \
			exit 1; }; \
		asdf reshim php >/dev/null 2>&1 || true; \
	fi
	@php --version | head -1

lint: ## Syntax-check every PHP file in the WordPress theme
	@command -v php >/dev/null 2>&1 || { echo "No php on PATH — run 'make init' first."; exit 1; }
	@echo "==> php -l over $(THEME_DIR)"
	@failed=0; \
	while IFS= read -r file; do \
		output=$$(php -l "$$file" 2>&1) || { echo "$$output"; failed=1; }; \
	done < <(find $(THEME_DIR) -name '*.php' | sort); \
	if [ $$failed -eq 0 ]; then \
		echo "    $$(find $(THEME_DIR) -name '*.php' | wc -l | tr -d ' ') files, no syntax errors"; \
	else \
		exit 1; \
	fi

zip: ## Rebuild wordpress-theme/red-feather-club.zip from the theme source
	@find $(THEME_DIR) -name '.DS_Store' -delete
	@rm -f $(THEME_ZIP)
	@cd wordpress-theme && zip -rq $(notdir $(THEME_ZIP)) $(notdir $(THEME_DIR)) -x '*.DS_Store'
	@echo "==> $(THEME_ZIP) ($$(du -h $(THEME_ZIP) | cut -f1 | tr -d ' '), $$(unzip -l $(THEME_ZIP) | tail -1 | awk '{print $$2}') entries)"

hugo:
	@command -v hugo >/dev/null 2>&1 || { echo "No hugo on PATH — install it with 'brew install hugo'."; exit 1; }

dev: hugo ## Build and serve the site with live reload, and open it in a browser
	@hugo server --port $(PORT) --openBrowser

serve: hugo ## Live-reloading preview of the site (override with PORT=8080)
	@hugo server --port $(PORT)

build: hugo ## Build the site into public/ (set RFC_META_TOKEN for live social posts)
	@hugo --minify --cleanDestinationDir
