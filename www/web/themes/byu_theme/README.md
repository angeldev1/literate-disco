# BYU Drupal 8 Theme
> Drupal 8 Theme using Bootstrap &amp; Components

## INSTALLATION
1. Using composer, run `composer require drupal/byu_theme`.
2. Go to Appearance > Settings > byu_theme. You will see settings similar to 
the byu2017_d7 theme.

These sections each have several options.
BYU FONTS
BYU HEADER
BYU GENERAL PAGE
BYU FOOTER

## Understanding Header/Footer Components
You can read the full documentation for the BYU Header & Footer components on 
these pages: http://2017-components-demo.cdn.byu.edu/ and 
http://webcommunity.byu.edu/html-5

### Questions? Ask the Group
The Engineering team of web developers around campus that supports the 
components is on slack. Join the byuweb team (see http://webcommunity.byu.edu/) 
and go to the #engineering-group channel.

## Search Options
The search in the byu header can be disabled in the header settings.
You can also customize how it works. It is using the byu-search component. 

If you use the default core search module, it will work out of the box.

### Using Different Search Modules
You are able to use different search modules (i.e. Custom Search or Google 
Custom Search). If the search component gets confused finding your search/text 
input and your button/submit input, the theme has settings provided to tell it 
specifically which elements to target.

For example, if you use the Custom Search module, you will want to specify:
`input[data-drupal-selector="edit-keys"]` for the Search Box element
and 
`input[data-drupal-selector="edit-submit"]` for the Search Button element.
These fields take simple css selectors, so if your search module isn't working, 
make sure you are using a css selector that will not target multiple divs, and 
that will not change. (i.e. id's of these search elements often change once you 
start searching or reloading the page.)

## Hidden Region

This theme comes with a region that is used purely for referencing the blocks in that region. This is useful in situations such as using a block entity reference field in some content type. Putting the block in the hidden field would allow you to still use the block in that block entity reference field, but it wouldn't appear on the website.
