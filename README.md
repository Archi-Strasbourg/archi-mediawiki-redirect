# archi-mediawiki-redirect

Script that redirects old Archi-Wiki URLs to new MediaWiki URLs

## Usage

Copy ArchiWiki's `config.php` at the root of the MediaWiki project.

Add these rules to MediaWiki's `.htaccess`:

```apache
RewriteRule adresse-(.+)-([0-9]+)\.html vendor/archi-strasbourg/archi-mediawiki-redirect/redirect.php?archiAffichage=adresseDetail&archiIdAdresse=$2 [QSA,L]
RewriteRule personnalite-(.+)-([0-9]+)\.html vendor/archi-strasbourg/archi-mediawiki-redirect/redirect.php?archiAffichage=evenementListe&selection=personne&id=$2 [QSA,L]
```
