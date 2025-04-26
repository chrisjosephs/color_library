## INTRODUCTION

The Color Library module adds color entities that can be maintained by users and added via a color library to easily find and re-use existing colors.

The difference between this and the color_field module is that colors can also be individually saved.  A major difference between this and the colorapi module is that colors are content entities rather than config entities which is better for content-driven model cases, and for User-generated colors or palettes (e.g., in a design tool or when created by a dependant module's field), and when there is a need for revisions, moderation and in fields. A full rationale is given at the end of this document.

*TODO: Can optionally integrate with ColorAPI module which manages global color entities (e.g., primary, secondary colors) at the *config* level for config-driven model cases, still ensuring enforced consistency across the site.

The primary use case for this module is:

- Adding and removing colors to a color library with color palettes
- Maintaining theme or brand colors for your website
- Can be used by 'Layer Styles' module for creating overlay and background effects to overlay or underlay any node/entity's template
- Color Palette utility icon/block overlay can make it easy to copy and paste colors into any form element from your palettes of saved colors
- Can be exported via views, also for headless websites

Features:

- Default colors are managed separately from the database, avoiding configuration bloat - these are the 216 CSS3 colors that are named.
- User-created colors are managed as content entities
- Colors used by modules/fields are similarly saved to palettes named/tagged to the module
- The color selection widget combines both types of colors for a unified user experience.
- Tokens/$css-variables-names to allow paste into WYSIWYG (use both CID and the name)
  - todo: try and encourage semantic tokens (primary-background instead of color name) or go off id: \[color:42:hex]
  - todo: colorapi config colors also as tokens
  - todo: switchable theme contexts
- Track colors against the css variables names that you are using
- A view to expose your color data to headless frontends as json
- Function to check where color is used to warn user where they will be updated if updated
- Todo: compatible for exporting/creating through Drupal GraphQL
- Todo: tags (currently comma listed 255 var field only - break out into table)

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


###  Why choose a **Color Entity** over a **Color Field** to manage colors?

Offers distinct advantages depending on your specific needs and the complexity of your color management requirements. Here's a breakdown of the advantages of using a Color Entity over a simple Color Field:

**Advantages of a Color Entity:**

1.  **Reusability and Consistency:**
* **Centralized Management:** Color entities are managed centrally. Once a color entity is created (e.g., "Primary Blue," "Accent Green," "Neutral Gray"), it can be referenced and reused across multiple content types, fields, and even configuration settings throughout your Drupal site.
* **Consistent Definitions:** This ensures that the same semantic color (like "Primary Blue") always uses the exact same hexadecimal, RGB, or other color value wherever it's applied. This promotes visual consistency across your website.
* **Reduced Redundancy:** You avoid having to define the same color value repeatedly within individual fields on different content types.

2.  **Semantic Meaning and Context:**
* **Descriptive Names:** Color entities allow you to give meaningful, semantic names to your colors (e.g., "Call to Action Background," "Article Text," "Link Hover"). This provides context and makes it easier to understand the purpose of each color in your design system.
* **Beyond Just a Value:** An entity can hold more than just the color value. You could potentially add descriptions, usage guidelines, or even associate it with specific design tokens or branding guidelines within the entity itself.

3.  **Enhanced Maintainability:**
* **Centralized Updates:** If you need to change a specific color (e.g., adjust the shade of your "Primary Blue"), you only need to update the color value within the single Color entity. All references to that entity across your site will automatically reflect the change. This significantly simplifies maintenance and rebranding efforts.
* **Easier Tracking and Auditing:** It's easier to track where specific semantic colors are being used across your site when they are managed as entities.

4.  **Potential for Advanced Features (Scalability):**
* **Relationships:** You could potentially establish relationships between Color entities. For example, you might have a base "Blue" entity and then variations like "Blue Light," "Blue Dark" as separate entities related to the base color.
* **Metadata:** You could add metadata to Color entities, such as accessibility information (contrast ratios), associated color palettes, or the brand it belongs to.
* **Programmatic Access:** Entities are first-class citizens in Drupal's API, making it easier for developers to programmatically access, manipulate, and extend color information.

5.  **Integration with Design Systems:**
* **Stronger Foundation:** Color entities provide a more robust foundation for implementing and managing a design system within Drupal. They allow you to treat colors as fundamental design elements with their own identity and properties.
* **Token Management Potential:** While not inherently a token system, Color entities can be a step towards a more token-based approach by providing semantic names and centralized management.

**When a Color Field Might Suffice:**

A simple Color Field is often sufficient when:

* You need a color picker for a one-off instance on a specific content type.
* The color is highly specific to a particular field and won't be reused elsewhere.
* You don't need semantic naming or centralized management of colors.
* The complexity of managing color entities outweighs the benefits for your project's scale.

**In essence, a Color Entity elevates the concept of color in Drupal from a mere data point within a field to a distinct, reusable, and semantically meaningful element within your content model and design system.** It offers significant advantages for consistency, maintainability, and scalability, especially in larger and more complex Drupal projects.

Think of it this way: a Color Field is like having individual color swatches scattered throughout your project, while a Color Entity is like having a well-organized color palette with defined names and properties that you can consistently refer to.
