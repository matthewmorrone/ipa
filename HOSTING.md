# Hosting on a Raspberry Pi via Cloudflare Tunnel

Context: the live site's PHP stopped executing at some point (matthewmorrone.com now
serves `.php` files as raw static text instead of running them — confirmed by fetching
`ipa.php` directly and getting back unexecuted PHP source with
`content-type: application/x-httpd-php`, plus a 405 on the `POST` requests the chart's
AJAX calls make). Root cause: the site's other repo (`matthewmorrone.github.io`) has a
`wrangler.jsonc` and deploys as static Cloudflare Pages/Workers assets — a static-only
host, so PHP was never actually running. This doc is the plan (now largely executed)
for moving hosting to a Raspberry Pi on the LAN, exposed via a Cloudflare Tunnel, so
PHP actually runs.

**Pi identity:** the target Pi is `portapi` (SSH alias `pi`, LAN IP `192.168.0.215`,
Debian 13 "trixie", also reachable over Tailscale). It is **not** a dedicated box — it
already runs Pi-hole (owns port 80), Plex (32400), Jellyfin (8096), qBittorrent
(8080/8081), Samba, an MQTT broker, and Docker containers for smart-home integrations.
Nothing about this setup should touch those.

**Status: done —**
- Apache 2.4 + PHP 8.4 installed, `short_open_tag = On` set
  (`/etc/php/8.4/apache2/conf.d/99-short-open-tag.ini`).
- Apache listens on **port 8090** (not 80 — Pi-hole's admin UI owns port 80; changed
  in `/etc/apache2/ports.conf` and the vhost).
- DocumentRoot is **`/var/www/matthewmorrone.com`** (not `/var/www/html` — that's
  Pi-hole's own doc root, sharing an `admin/` folder; kept fully separate to avoid any
  risk of an `rsync --delete` ever touching Pi-hole's files).
- `mod_rewrite` and `mod_headers` enabled, `AllowOverride All` set so the site's
  `.htaccess` (CORS headers, `ipa-chart/` → `ipa/` rewrite, etc.) works.
- `matthewmorrone.github.io` (main site) rsynced to `/var/www/matthewmorrone.com/`.
- This `ipa` repo rsynced to `/var/www/matthewmorrone.com/projects/ipa/`.
- Verified over LAN: `ipa.php` mode=1 returns the real language-file JSON list, mode=2
  returns the full rendered chart table (~26KB HTML) — PHP is executing correctly.

**Also deployed:** the other project repos `build.sh` normally pulls in —
`diachron-db`, `flash`, `morse`, `sheet`, `chess`, `spaaace` (as `space`) — were
cloned directly on the Pi into `/var/www/matthewmorrone.com/projects/` and verified
(`HTTP 200` each over LAN).

**Cloudflare Tunnel: created and routed to the test hostname.**
- Tunnel `pi-website` created in the Zero Trust dashboard (account was on no Zero
  Trust plan yet — selected Free).
- `cloudflared` installed on the Pi via Cloudflare's apt repo, running as a systemd
  service (`sudo cloudflared service install <token>`), connector shows **Connected**.
- Public hostname `pi-test.matthewmorrone.com` → HTTP → `localhost:8090` added (the
  de-risk step from "4. De-risk the cutover" below) — **not** the root domain yet.
- Verified live: `https://pi-test.matthewmorrone.com/` returns 200, and
  `ipa.php` through the tunnel returns correct data (mode=1 language list). Full path
  confirmed working: public HTTPS → Cloudflare Tunnel → Pi Apache:8090 → PHP.

**Cutover: done.** `matthewmorrone.com` and `www.matthewmorrone.com` now route through
the tunnel to the Pi:
- Both DNS records changed from CNAME → `matthewmorrone-github-io.pages.dev`
  (Cloudflare Pages) to CNAME → `35781c67-9f6a-4c42-8e52-1e22c3ca8e9d.cfargotunnel.com`.
- Tunnel ingress rules added for both hostnames → `http://localhost:8090` (in addition
  to the existing `pi-test` rule).
- Verified live: `https://matthewmorrone.com/` → 200; `www` redirects to the apex
  (pre-existing redirect, unrelated to this change) which then serves 200;
  `ipa.php` mode=1 and mode=2 both return correct data through the public domain.
- Note: the tunnel's "Add route" UI couldn't auto-create the DNS records itself
  (it errored with "DNS record already exists" against the pre-existing Pages CNAMEs)
  — the DNS records were edited manually instead, pointed at
  `<tunnel-id>.cfargotunnel.com`, before adding the ingress rules separately.

**Gap still open:**
- `autocorrect` (linked from `left.html`) has no matching repo under
  `github.com/matthewmorrone` (`autocorrect`, `autocorrect-web`, `autocorrects`,
  `autocorrect.js` all 404) and isn't referenced in `build.sh` either — its source
  needs to be tracked down before that nav link will work.

## 1. Prep the Pi — done, see Status above

Redeploying after local changes is just:

```bash
rsync -az --delete --exclude='.git' --exclude='.DS_Store' \
  /Users/matthewmorrone/Projects/matthewmorrone.github.io/ pi:/var/www/matthewmorrone.com/
rsync -az --delete --exclude='.git' --exclude='.DS_Store' \
  /Users/matthewmorrone/Projects/ipa/ pi:/var/www/matthewmorrone.com/projects/ipa/
```

No MySQL is required — despite the original README, `ipa.php` never touches a
database; it only reads files from `languages/` and `tables/`.

## 2. Create the tunnel (Cloudflare Zero Trust dashboard)

1. **Zero Trust dashboard → Networks → Tunnels → Create a tunnel**
2. Choose **Cloudflared**, name it (e.g. `pi-website`)
3. Pick the **Debian/ARM** install command it generates — a one-liner with a token
   baked in, e.g.:

```bash
sudo mkdir -p --mode=0755 /usr/share/keyrings
curl -fsSL https://pkg.cloudflare.com/cloudflare-main.gpg | sudo tee /usr/share/keyrings/cloudflare-main.gpg >/dev/null
echo 'deb [signed-by=/usr/share/keyrings/cloudflare-main.gpg] https://pkg.cloudflare.com/cloudflared $(lsb_release -cs) main' | sudo tee /etc/apt/sources.list.d/cloudflared.list
sudo apt update && sudo apt install cloudflared
sudo cloudflared service install <TOKEN-FROM-DASHBOARD>
```

This installs `cloudflared` as a systemd service that auto-connects and
auto-restarts on reboot.

## 3. Point hostnames at the Pi

In the dashboard, on that tunnel, add **Public Hostnames**:

- `matthewmorrone.com` → Service `HTTP` → `localhost:8090`
- `www.matthewmorrone.com` → same

Adding a public hostname here **automatically creates/overwrites the CNAME DNS
record** for that hostname, pointing it at the tunnel. This is the step that
actually cuts the live domain over from its current origin to the Pi.

## 4. De-risk the cutover

Before adding the root `matthewmorrone.com` hostname, add a throwaway one first
(e.g. `pi-test.matthewmorrone.com` → `localhost:8090`) and verify the whole site —
including the IPA chart's AJAX calls to `ipa.php` — loads correctly through *that*
while the real domain is untouched. Only once that checks out, add the root and
`www` hostnames to go live. If anything looks wrong after cutover, edit/delete the
public hostname in the dashboard to revert instantly — it's a DNS change, not
destructive to anything on the Pi.

## Open items

- Deploy the other project repos (`diachron-db`, `flash`, `morse`, `sheet`, `chess`,
  `spaaace`, and `autocorrect`) to `/var/www/matthewmorrone.com/projects/` the same
  way `ipa` was, so those nav links stop 404ing.
- Create the Cloudflare Tunnel and point it at `localhost:8090` (steps 2–4 above,
  requires the Cloudflare Zero Trust dashboard — not done yet).
