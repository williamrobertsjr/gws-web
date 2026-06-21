# Deployment & Server Setup Notes

Cloudways git deploy only syncs the theme (`wp-content/themes/gws-web/`). The items below live outside that scope and must be set up manually on any new server or clone.

Any changes outside of public_html should be documented here.

---

## 1. Private files directory

Price lists, CSVs, and other gated data files live outside the web root so they require a login to download.

**Copy from production:**
```
private_html/docs-data/
└── pricing/
    ├── 2025/
    └── 2026/
        ├── jan/
        └── june/
```

After copying, verify a download link works for a logged-in user, e.g.:
`https://www.gwstoolgroup.com/docs/pricing/2026/june/GWS_PRICE_LIST_55_JUNE_26.xlsx`

---

## 2. Rapid quote update scripts

The hourly stock update scripts also live outside the web root.

**Copy from production:**
```
private_html/scripts/
├── instaquote_to_sql.py
├── update_rapid_quote.sh
└── update.log
```

`update_rapid_quote.sh` reads `Instaquote.csv` from `private_html/docs-data/` and updates the `rapid_quote` database table.

---

## 3. Cron job

Add to the server crontab (`crontab -e`):

```
# Update rapid_quote at 8am, 10am, 12pm, 3pm, 5pm, 7pm CST
0 14,16,18,21,23,1 * * * /home/master/applications/dev/private_html/scripts/update_rapid_quote.sh
```

> **Note:** Update the path if the application name changes from `dev`.

---

## 4. Files already handled by git deploy or Cloudways clone

These do NOT need manual setup — they come along automatically:

| File | How it's handled |
|---|---|
| `public_html/download.php` | Cloned with `public_html` |
| `public_html/.htaccess` | Cloned with `public_html` |
| `wp-content/themes/gws-web/db_connection.php` | Gitignored — stays on server, not overwritten by deploys |

---

## 5. What lives where (summary)

| Location | What's there | Managed by |
|---|---|---|
| `public_html/` | WordPress + theme | Cloudways git deploy |
| `public_html/docs/quality/` | ISO certs, compliance PDFs | Manual / public |
| `public_html/docs/speed_and_feed/` | Speed & feed charts | Manual / public |
| `private_html/docs-data/pricing/` | Price lists (login required) | Manual |
| `private_html/scripts/` | Rapid quote update scripts | Manual |
| System crontab | Hourly stock update job | Manual |
