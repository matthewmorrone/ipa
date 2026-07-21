ipa-chart
=========
This is a static client-side app — no PHP, MySQL, or Apache required. `index.html`
and `submit.html` fetch the `.txt` data files in `languages/` and `tables/` directly
and render the chart entirely in JavaScript (see `util.js`).

To run locally, serve this directory with any static file server, e.g.:

    python3 -m http.server 8000

then open `http://localhost:8000/index.html`. Opening the file directly via `file://`
will not work — browsers block `fetch()` of local files under that scheme.

`languages/index.json` lists the available language files for the dropdown; if you
add a new file to `languages/`, add its filename to that list too.

"Submit a language" (`submit.html`) composes a `mailto:` link with the selected
letters instead of writing to the server, since a static site has no server to write
to — review and add submissions to `languages/` (and `languages/index.json`) by hand.

Legacy
------
`ipa.php` is the original server-side implementation the client-side code above was
ported from. It's no longer used by either page, but is kept for reference. It still
requires `short_open_tag = On` in PHP's config if you ever run it directly, since it
uses bare `<?` tags.

Running tests
-------------
The only test suite covers `ipa.php`'s `chomp()` (the legacy server-side code above,
not the client-side app). Ensure PHP is installed and run:

    php -d short_open_tag=On tests/ChompTest.php
