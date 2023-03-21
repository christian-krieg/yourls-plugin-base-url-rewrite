# BaseURL Rewrite [![Listed in Awesome YOURLS!](https://img.shields.io/badge/Awesome-YOURLS-C5A3BE)](https://github.com/YOURLS/awesome-yourls/)

<!-- Once you have committed code, get your plugin listed in Awesome YOURLS ! See https://github.com/YOURLS/awesome-yourls -->

Tested with [YOURLS](https://yourls.org), version `1.9.2`.

## Usage

### General

This plugin rewrites short URLs by replacing the original base URL (as specified
by ``YOURLS_SITE`` in `config.php`) with the base URL given in the plugin's
configuration option as specified in the settings. If the base URL configuration
option is empty, the original short URL stays untouched, and is returned.

This base URL configuration option can be different from the domain from which
YOURLS is served (i.e., ``YOURLS_SITE``).  This plugin rewrites short URLs such
that they point to another (base) URL (e.g., ``https://acme.com/r``), which
can be helpful if you want to serve YOURLS from a different sub-domain (e.g.,
``https://link.acme.com``), but generate short links for a sub-directory of the
domain for your main web presence (e.g., ``https://acme.com/r``).

:bulb: **NOTE:** You must take care that the other end (e.g.,
``https://acme.com/r``) performs redirects back to the site where YOURLS is
served from (e.g. ``https://link.acme.com``).

![The admin interface of the plugin](config.png)

### Example

Lets assume the following:

    YOURLS_SITE: "https://link.acme.com" (a constant defined in `config.php`)
    ck_base_url: "https://acme.com/r" (a configuration option)

Then, YOURLS will create the (example) short link

    https://acme.com/r/Ac3fG

instead of

    https://link.acme.com/Ac3fG

It is assumed that the system behind ``https://acme.com/r`` performs (e.g.,
wildcard) redirects from ``https://acme.com/r/(.*)`` to
``https://link.acme.com/$1`` which are then further redirected to the respective
target URLs.


## Installation

### The easy way: git

Change directory to `user/plugins` and clone the `git` repository of this plugin:

    cd user/plugins
    git clone https://github.com/christian-krieg/yourls-plugin-baseurl-rewrite

### The hard way: manual copy

1. In `user/plugins`, create a new folder named `yourls-plugin-baseurl_rewrite`.
2. Drop these files in that directory.
3. Go to the Plugins administration page (e.g. `http://sho.rt/admin/plugins.php`) and activate the plugin.
4. Have fun!

## License

This package is licensed under the [MIT License](LICENSE).
