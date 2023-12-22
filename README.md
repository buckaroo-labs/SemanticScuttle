# ![Bookmark star icon](icon.png) SemanticScuttle

SemanticScuttle is a social bookmarking tool experimenting with new features like structured (hierarchical) tags and collaborative descriptions of tags. Originally a fork of Scuttle, it has overtaken its ancestor in stability, features and usability. The querwurzel [fork](https://github.com/querwurzel/semantic-scuttle) brought the software up to PHP 7.3 compatibility, but is no longer in development. This fork's aims are to:
  * bring app compatibility up to PHP 8.2 (for at least the MySQL/mysqli database option, possibly others), avoiding the "Deprecated" messages under PHP 8.2 that will be the "Fatal Error"s of a future PHP version;
  * correct broken links in the documentation; and to
  * bundle a responsive theme (forked from [sscuttlizr](https://github.com/jonrandoem/sscuttlizr)) with minimal features, designed for cases in which this app is embedded in a larger system.
  * secure the application (see warning).

## Warning

We offer no warranty, express or implied, regarding the security of this application. No security testing has been performed, but a visual review has revealed highly questionable coding practices. Many of the associated risks may be mitigated by turning off anonymous editing and user registration capabilities after a single administrative user has been created (see configuration.rst file in doc folder), and/or not exposing the application to the internet. 

## Features
  * LDAP/Active Directory authentication
  * RSS feed support: global feed, user feeds, per-tag feeds, private feeds
  * Public and private bookmarks
  * Delicious and Browser bookmark import
  * Theming support
  * Firefox plugin

## Screenshots
![Screenshot 1](Screenshot1.jpg)
![Screenshot 2](Screenshot2.jpg)

## Origin

  * https://sourceforge.net/projects/semanticscuttle/
  * https://github.com/cweiske/SemanticScuttle
  * https://github.com/querwurzel/semantic-scuttle
  * https://github.com/jonrandoem/sscuttlizr
