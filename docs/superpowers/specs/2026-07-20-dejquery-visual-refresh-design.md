# De-jQuery + Visual Refresh Design

## Context

`index.html` and `submit.html` currently depend on a bundled `jquery.js` and the
jQuery UI CDN (script + CSS) for all interactivity: AJAX calls to `ipa.php`, DOM/event
handling, a custom `antiselect` plugin, and (in `index.html` only) draggable/resizable
popups for the "extras" reference tables. Visually, both pages are unchanged since
their original 2013-era design: harsh black 1px borders everywhere, dead `@font-face`
links (the `themes.googleusercontent.com` Raleway/Open Sans URLs 404), and a plain
text-link menu with a black/lightgray "on/off" color toggle.

This is two changes, sequenced deliberately so a functional regression and a visual
change are never introduced in the same step:

1. **Remove the jQuery/jQuery UI dependency** from `index.html`, `submit.html`, and
   `util.js`, preserving existing behavior exactly — with one deliberate UX
   exception (see below) — then verify every interaction still works.
2. **Visual refresh** of both pages once (1) is verified working, changing only
   presentation (CSS, fonts, a new light/dark toggle) — no further behavior changes.

## Phase 1 — Remove jQuery dependency

**Files touched:** `index.html`, `submit.html`, `util.js`. `jquery.js` is deleted from
the repo once nothing references it; the jQuery UI `<script>` and `<link
rel="stylesheet">` are removed from both HTML files.

**Conversions** (mechanical, one-to-one, behavior-preserving):

| jQuery | Vanilla replacement |
|---|---|
| `$.post(url, data, cb)` | `fetch(url, {method: 'POST', body: new URLSearchParams(data)}).then(r => r.text()).then(cb)` |
| `$(selector)` | `document.querySelector` / `querySelectorAll` |
| `.click(fn)` | `addEventListener('click', fn)` |
| `.toggleClass()` / `.addClass()` / `.removeClass()` | `classList.toggle()` / `.add()` / `.remove()` |
| `.css(prop, val)` | `element.style[prop] = val` |
| `.each(fn)` | `NodeList.forEach` (or `for...of`, NodeLists are iterable) |
| `.find()` / `.children()` | `querySelector(All)` scoped to the element |
| `.html(str)` / `.append(str)` | `innerHTML` / `insertAdjacentHTML` |
| `$.fn.antiselect` (custom plugin in `util.js`) | plain function taking a NodeList/element and toggling the same `user-select` CSS properties |
| `$.expr[':'].contains` (custom selector in `util.js`) | plain helper, e.g. finding a `<select>` option by matching `textContent` |
| `String.prototype.define('chomp'/'capital', ...)` | dropped — `util.js` already has plain `chomp()`/`capital()` functions that do the same thing |

`document.execCommand('copy')` (used by the Copy menu item) is **not** jQuery-related
and is left as-is — not in scope for this pass.

**Deliberate exception — the "extras" popups:**
Today, clicking Coarticulations/Diacritics/Suprasegmentals/Tones creates a floating
`<div class='popup'>` made draggable (by its header) and resizable (from its corner)
via jQuery UI. There's no built-in vanilla equivalent, and reimplementing
drag/resize from scratch is unnecessary complexity for what these panels actually need.
Instead, each extras label becomes an inline toggle: clicking it shows/hides its table
in a dedicated panel area below the main menu row. Multiple can be open at once,
independently (not an accordion — opening one does not close another). The
draggable/resizable behavior and its associated positioning code (`left`,
`top`, `z-index`, snap) are removed entirely, since the panels no longer float.

Everything else keeps its exact current behavior:
- Sound playback on symbol click, typing into `#scratch`, Copy/Sound/Type/Link/Selection/Rotate/Clear toggles
- Language dropdown switching (`initialize()`, `render()`) including the URL query-param deep link behavior
- `submit.html`'s checkbox toggling, "Toggle All"/"Show All" behavior, and form submission to `ipa.php` mode 3

**Verification:** manual, in-browser, before moving to Phase 2. Exercise every
interaction listed above on both pages and confirm no console errors and identical
observable behavior (apart from the extras-popup UX change, which is expected).

## Phase 2 — Visual refresh

**Files touched:** `index.html`, `submit.html` (styling only — the vanilla JS from
Phase 1 is not touched further except for the one new theme-toggle handler).

### Color system & theme toggle
CSS custom properties on `:root` (`--bg`, `--text`, `--border`, `--accent`, etc.), with
a `[data-theme="dark"]` override block. A new sun/moon toggle button next to the
language dropdown sets `data-theme` on `<html>`, persisted via `localStorage`,
defaulting to `prefers-color-scheme` on first visit.

- Light: off-white background, dark charcoal (not pure black) text, one accent color
  for links/active states.
- Dark: deep gray-blue background, soft off-white text, same accent re-tuned for
  contrast.

### Typography
- **Fraunces** for page titles/section headers — a serif with academic/editorial
  character, distinct from generic sans-serif defaults.
- **Inter** for all UI chrome (menu, dropdown, table headers, footer) — very legible
  at small sizes.
- IPA symbols in table cells (`.sound`) keep the current `arial` system-font stack
  untouched, to avoid any risk to Unicode/IPA glyph rendering.
- Loaded via Google Fonts `<link>`, replacing the dead `@font-face` blocks.

### Menu
Each menu `<li>` (Copy/Sound/Type/Link/Selection/Rotate/Clear, and submit.html's
`Toggle All`/`Submit` buttons) becomes a pill-shaped toggle button: rounded corners,
subtle border, filled background + accent text when active, muted/outline when
inactive — replacing the current black-vs-lightgray text color toggle.

### Table
Harsh 1px solid black borders become a soft gray (`--border`). The header row (place
of articulation) gets a light background tint. Cells get a subtle hover highlight to
help trace a row/column visually. The `rowspan`/`colspan` structure generated by
`ipa.php` is untouched — styling only.

### Extras inline panels
Styled consistently with the main table's soft-grid treatment (same border color,
header tint) rather than the old bare popup look.

### Dropdown & footer
Native `<select>` gets border-radius and a focus ring instead of the bare control.
Footer text gets improved spacing/line-height; link color matches the new accent.

### Explicitly out of scope
Layout width/breakpoints (both pages keep their current fixed-width, non-responsive
containers), the PHP-generated table markup/structure, and any JS behavior beyond the
one new theme-toggle handler.

## Testing

No automated JS test suite exists for either page (the repo's only test is
`tests/ChompTest.php`, PHP-side). Verification for both phases is manual,
in-browser, covering every interactive feature listed above, on both `index.html` and
`submit.html`, in both light and dark theme after Phase 2.
