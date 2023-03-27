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

When using the REST API, this plugin provides an option to specify the name of a
query parameter, whose value holds a base URL to use for the generated short
URL. For instance, if we name the query parameter ``base_url`` in the plugin
configuration, we can request a short URL as follows:

```
curl "https://lnk.acme.com/yourls-api.php?signature=XXXXXXXXXX&action=shorturl&format=txt&base_url=https://pink.pony&url=https://this.is.my.veeeeery.long.url.com/with/a/very/deep/path.html?and=also&some=query&para=meters"

https://pink.pony/vUvNW
```

We can freely choose the name of the query parameter, e.g., we can name it
``foo_bar``, and YOURLS will return the same result:

```
curl "https://lnk.acme.com/yourls-api.php?signature=XXXXXXXXXX&action=shorturl&format=txt&foo_bar=https://pink.pony&url=https://this.is.my.veeeeery.long.url.com/with/a/very/deep/path.html?and=also&some=query&para=meters"

https://pink.pony/vUvNW
```

:bulb: **NOTE:** You must take care that the other end (e.g.,
``https://acme.com/r``, ``https://pink.pony``) performs redirects back to the
site where YOURLS is served from (e.g. ``https://link.acme.com``).

![The admin interface of the plugin](config.png)

### URL precedence

The short link's base URL is chosen as follws:

1. If the query parameter name is set in the plugin's configuration, and YOURLS
   is accessed via the REST API with the query parameter assigned, the **query
   parameter's value** is used as the generated shortlink's base URL.

1. If the query parameter name is not set in the configuration, or no query
   parameter is given in the query string when accessing YOURLS via the REST
   API, the plugin's **default base URL** as configured in the plugin settings
   is used as the generated shortlink's base URL.

1. If no default base URL is set in the plugin configuration, the **value of
   ``YOURLS_SITE``** is used as the generated shortlink's base URL.

### Example

Lets assume the following:

    YOURLS_SITE: "https://link.acme.com" (a constant defined in `config.php`)
    ck_base_url_default: "https://acme.com/r" (a configuration option)
    ck_base_url_query_parameter: "base_url" (a configuration option)

Then, YOURLS will create the (example) short link (when accessed from the YOURLS
admin interface, or from the REST API without specifying the `base_url` query
parameter):

    https://acme.com/r/Ac3fG

instead of

    https://link.acme.com/Ac3fG

If invoking YOURLS via its REST API while setting the ``base_url`` query
parameter to ``https://foo.bar``, YOURLS returns the following shortlink (let's
say the full API call is specified like this:
`https://lnk.acme.com/yourls-api.php?signature=XXXXXXXXXX&action=shorturl&format=txt&base_url=https://foo.bar&url=https://short.this/url/for/me`):

    https://foo.bar/Ac3fG


It is assumed that the system behind ``https://acme.com/r`` performs (e.g.,
wildcard) redirects from ``https://acme.com/r/(.*)`` to
``https://link.acme.com/$1`` which are then further redirected by YOURLS to the
respective target URLs.


## Installation

### The easy way: git

1. Change directory to `user/plugins` and clone the `git` repository of this plugin:

      cd user/plugins
      git clone https://github.com/christian-krieg/yourls-plugin-baseurl-rewrite

1. Go to the Plugins administration page (e.g.
   http://link.acme.com/admin/plugins.php`) and activate the plugin.

1. Have fun!


### The hard way: manual copy

1. Change to directory ``user/plugins``:

       cd user/plugins

1. Download the latest stable release of the plugin from
   https://github.com/christian-krieg/yourls-plugin-base-url-rewrite/releases,
   e.g.:

       wget -c https://github.com/christian-krieg/yourls-plugin-base-url-rewrite/archive/refs/tags/1.0.0.tar.gz

1. Unzip the archive to ``user/plugins``, e.g.:

       tar -xavvf 1.0.0.tar.gz

1. Eventually upload the unzipped archive to your YOURLS instance (e.g., using
   `ftp` or `sftp`)

1. Create a symlink to the extracted folder, and name it
`` yourls-plugin-baseurl-rewrite`` (basically we remove the version string),
   e.g.:

       ln -s yourls-plugin-baseurl-rewrite-1.0.0 yourls-plugin-baseurl-rewrite

1. Go to the Plugins administration page (e.g.
   http://link.acme.com/admin/plugins.php) and activate the plugin.

1. Have fun!


## Updating

### The easy way: git

1. Move into the plugin's directory, and pull the plugin's latest changes::

    git pull

2. Eventually, check out a specific version:

    git checkout 1.1.0

3. Configure the plugin in the administration page

4. Have fun!


### The hard way: manual download and copy

Follow the [installation instructions for manual copy](#the-hard-way-manual-copy),
and choose the most recent version.

## License

This package is licensed under the [MIT License](LICENSE).
