WARNING:
=======

Due to the design of ATutor’s module system, the module’s name need
to be hard-coded in a lot of places, including:

- The directory name
- module.xml
- module.php (4 occurrences)
- module_delete.php
- module_groups.php (2 occurrences)
- module_news.php

In addition, the directory path is also hard-coded in several places,
including:

- module.php (THROUGHOUT THE WHOLE FILE)
- dropdown/posts.inc.php
- and throughout the whole code base

In this partial rewrite the second case (i.e., the directory path
being hard-coded throughout the whole code base) is partially mitigated
by moving the hard-coded path to lib/module.inc.php

This means that when this module finally replaces the original forums
module, you will need to make ABSOLUTELY sure that every occurrence of
the module's temporary new name and directory path are replaced with
the standard name and path.

In an “extra” module we use $_module_pages, but in a standard module
we use $this->_pages.

Note that this module uses the same tables as the standard forums module.
