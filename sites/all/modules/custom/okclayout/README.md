OKC LAYOUT
---------------

OKC Layout is an additionnal module for OKC Design theme.

https://github.com/nyl-auster/okcdesign

It allows to place block quickly on foundation css grid, by specifying foundation classes.

Edit block configuration and set foundation classes like this :

```
   small-6 medium-6 large-12 columns

```

MORE ON CONFIGURATION
---------------------

 Additionnaly, you may use your theme info file to register your
 block grid informations, like this :

```
 okclayout_block_classes[system-main] = large-12 columns
```

 Where "system-main" is "{module_name}-{block_delta}"; and "large-12 columns" is foundation classes.
 module and delta are displayed in html classes (ex : for block-user-online, module is "user" and delta is "online"
 or in the block url configuration :

 for "admin/structure/block/manage/block/15/configure?destination=node", module is "block" and delta is "15".

 If a block as classes in both info files and block configuration page, the block configuration
 page wins againts theme info file.
 Juste empty textarea to make theme info file active again for this block.

DIFFERENT GRIDS BY CONTEXT
---------------------

Drupal is not build in a way we may easily create different grid layout depending on the pages on context.
running drush okc-layout, you can create automatically a very light subtheme of your current active theme.
Using template.php, you can tell him on which contexts you want him to be active.
This allows you to create different layout grids for your site; and to set blocks on different regions according to the context.


