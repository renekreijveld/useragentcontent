User Agent Detection Content plugin
====================================

Functions
---------

* This system plugin detects the user agent of your website visitor and can be used in Joomla articles to display content acoordingly.

* Possible tests on content:

...{ifdesktop} This only shows if browser is desktop {/ifdesktop}

...{!ifdesktop} This shows everywhere except if browser is desktop {/ifdesktop}

...{iftablet} This only shows if browser is tablet {/iftablet}

...{!iftablet} This shows everywhere except if browser is tablet {/iftablet}

...{ifmobile} This only shows if browser is mobile {/ifmobile}

...{!ifmobile} This shows everywhere except if browser is mobile {/ifmobile}

* This plugin uses the excellent Mobile_Detect library: https://github.com/serbanghita/Mobile-Detect
