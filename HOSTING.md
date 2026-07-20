# Hosting on a Raspberry Pi via Cloudflare Tunnel

Context: the live site's PHP stopped executing at some point (matthewmorrone.com now
serves `.php` files as raw static text instead of running them — confirmed by fetching
`ipa.php` directly and getting back unexecuted PHP source with
`content-type: application/x-httpd-php`, plus a 405 on the `POST` requests the chart's
AJAX calls make). This doc is the plan for moving hosting to a Raspberry Pi on the LAN,
exposed via a Cloudflare Tunnel, so PHP actually runs again.

**Gap to close before cutover:** this repo only contains the `ipa` subproject. The rest
of matthewmorrone.com (the `/header`, `/left`, `/main`, `/right` routes) lives in a
separate codebase not present here. That content needs to be deployed to the Pi too,
or the rest of the site will break even though `/projects/ipa` works.

## 1. Prep the Pi

```bash
sudo apt update && sudo apt full-upgrade -y
sudo apt install -y apache2 php libapache2-mod-php
```

`ipa.php` uses short `<?` tags, which PHP 8 disables by default — without this you'll
reproduce the exact "raw PHP served as text" bug seen on the live site:

```bash
echo "short_open_tag = On" | sudo tee /etc/php/*/apache2/conf.d/99-short-open-tag.ini
sudo systemctl restart apache2
```

Deploy this repo (and whatever serves the rest of the site) into `/var/www/html/`.
Verify over the LAN before touching DNS: `http://<pi-lan-ip>/` and
`http://<pi-lan-ip>/projects/ipa/` (or wherever it's placed).

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

- `matthewmorrone.com` → Service `HTTP` → `localhost:80`
- `www.matthewmorrone.com` → same

Adding a public hostname here **automatically creates/overwrites the CNAME DNS
record** for that hostname, pointing it at the tunnel. This is the step that
actually cuts the live domain over from its current origin to the Pi.

## 4. De-risk the cutover

Before adding the root `matthewmorrone.com` hostname, add a throwaway one first
(e.g. `pi-test.matthewmorrone.com` → `localhost:80`) and verify the whole site —
including the IPA chart's AJAX calls to `ipa.php` — loads correctly through *that*
while the real domain is untouched. Only once that checks out, add the root and
`www` hostnames to go live. If anything looks wrong after cutover, edit/delete the
public hostname in the dashboard to revert instantly — it's a DNS change, not
destructive to anything on the Pi.

## Open items

- Get the non-`ipa` site content onto the Pi (source not in this repo).
- Decide on an Apache vhost / path config for serving `ipa` under `/projects/ipa`
  specifically, once deployed.
