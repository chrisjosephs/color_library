## INTRODUCTION

The Color Library module adds color entities that can be maintained by users and added via a color library to easily find and re-use existing colors.

A major difference between this and the colorapi module is that colors are content entities rather than config entities which is better for content-driven model cases, and for User-generated colors or palettes (e.g., in a design tool), and when there is a need for revisions, moderation and in fields.
*TODO: Can optionally integrate with ColorAPI module to manage global color tokens (e.g., primary, secondary colors) at the config level for config-driven model cases, still ensuring consistency across the site.

The primary use case for this module is:

- Adding and removing colors to a color palette
- Maintaining theme or brand colors for your website
- Can be used by 'easy overlays' module for creating overlay and background effects on top of any node/entity's template
- Color Palette utility icon/block overlay can make it easy to copy and paste colors into any form element from your palettes of saved colors
- Can be exported via views, also for headless websites

Features:

- Default colors are managed separately from the database, avoiding configuration bloat - these are the 216 CSS3 colors that are named.
- User-created colors are managed as content entities, allowing for easy export/import.
- Colors used by modules are similarly saved to palettes named/tagged to the module
- The color selection widget combines both types of colors for a unified user experience.
- Track colors against the css variables names that you are using
- A view to expose your color data to headless frontends as json
- Function to check where color is used to warn user where they will be updated if updated

## REQUIREMENTS

Drupal 11 (will be tested on earlier versions shortly)

## INSTALLATION

Install as you would normally install a contributed Drupal module.
See: https://www.drupal.org/node/895232 for further information.

## CONFIGURATION
- Choose whether and here you would like the color palette selector to appear on admin pages

## MAINTAINERS

Current maintainers:

- Christopher Peter Josephs (Digibitsymicronanocyberweb) - https://www.drupal.org/u/xis23

