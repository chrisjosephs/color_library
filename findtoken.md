Todo: for more robust and automated token tracking in Drupal programmatically (as of **April 21, 2025**), you'll need to leverage Drupal's APIs and potentially write custom code. Here's a breakdown of how you can approach this:

**1. Utilizing the Configuration System:**

* **Iterating Through Configuration:** Drupal's configuration is stored as configuration objects. You can programmatically load and inspect these objects for token usage.
* **`\Drupal::configFactory()->listAll()`:** This service allows you to get a list of all configuration object names.
* **`\Drupal::config($config_name)->getRawData()`:** This allows you to load the raw data of a specific configuration object as an array. You can then recursively search this array for token patterns.
* **Example (Finding a specific token in configuration):**

    ```php
    <?php

    namespace Drupal\my_module\Service;

    use Drupal\Core\Config\ConfigFactoryInterface;

    class TokenTracker {

      protected $configFactory;

      public function __construct(ConfigFactoryInterface $configFactory) {
        $this->configFactory = $configFactory;
      }

      /**
       * Recursively searches an array for a specific token.
       *
       * @param array  $data
       * The array to search.
       * @param string $token
       * The token to find (e.g., 'node:title').
       * @param string $configName
       * The name of the configuration object being searched.
       * @param array  $results
       * An array to store the results.
       * @param string $keyPath
       * The current key path within the array.
       */
      protected function findTokenInArray(array $data, string $token, string $configName, array &$results, string $keyPath = '') {
        foreach ($data as $key => $value) {
          $currentKeyPath = $keyPath ? $keyPath . '.' . $key : $key;
          if (is_array($value)) {
            $this->findTokenInArray($value, $token, $configName, $results, $currentKeyPath);
          } elseif (is_string($value) && strpos($value, $token) !== FALSE) {
            $results[] = [
              'config' => $configName,
              'path' => $currentKeyPath,
              'value' => $value,
            ];
          }
        }
      }

      /**
       * Finds all occurrences of a specific token in Drupal configuration.
       *
       * @param string $token
       * The token to search for (e.g., 'node:title').
       *
       * @return array
       * An array of results, where each result is an associative array
       * containing 'config', 'path', and 'value'.
       */
      public function findTokenInConfiguration(string $token): array {
        $results = [];
        $allConfig = $this->configFactory->listAll();
        foreach ($allConfig as $configName) {
          try {
            $configData = $this->configFactory->get($configName)->getRawData();
            $this->findTokenInArray($configData, $token, $configName, $results);
          }
          catch (\Exception $e) {
            // Handle cases where configuration might be invalid or inaccessible.
            \Drupal::logger('my_module')->warning('Error accessing configuration: @config - @error', ['@config' => $configName, '@error' => $e->getMessage()]);
          }
        }
        return $results;
      }

    }
    ```

* **Usage:** Inject the `TokenTracker` service and call the `findTokenInConfiguration()` method with the token you want to find.

    ```php
    $token = 'node:title';
    $results = \Drupal::service('my_module.token_tracker')->findTokenInConfiguration($token);
    foreach ($results as $result) {
      \Drupal::logger('my_module')->info('Token "@token" found in @config at path "@path" with value "@value"', [
        '@token' => $token,
        '@config' => $result['config'],
        '@path' => $result['path'],
        '@value' => $result['value'],
      ]);
    }
    ```

**2. Analyzing Template Files (Twig):**

* **Iterating Through Theme Files:** You'll need to get a list of all `.html.twig` files in your active theme(s) and any base themes. You can use the `\Drupal::service('theme.registry')->getActiveTheme()->getPath()` and potentially recursively search within the `templates` directory.
* **Reading File Contents:** Use PHP's file handling functions (`file_get_contents()`) to read the content of each Twig file.
* **Regular Expressions:** Employ regular expressions to search for token patterns within the Twig syntax (e.g., `\{\{\s*your_token\s*\}\}`).

    ```php
    <?php

    namespace Drupal\my_module\Service;

    use Drupal\Core\Theme\ThemeManagerInterface;
    use Drupal\Core\Extension\ExtensionDiscoveryInterface;

    class TokenTracker {

      protected $themeManager;
      protected $extensionDiscovery;

      public function __construct(ThemeManagerInterface $themeManager, ExtensionDiscoveryInterface $extensionDiscovery) {
        $this->themeManager = $themeManager;
        $this->extensionDiscovery = $extensionDiscovery;
      }

      /**
       * Recursively finds all .html.twig files in a directory.
       *
       * @param string $dir
       * The directory to search.
       *
       * @return array
       * An array of absolute paths to Twig files.
       */
      protected function findTwigFiles(string $dir): array {
        $files = [];
        $items = scandir($dir);
        foreach ($items as $item) {
          if ($item === '.' || $item === '..') {
            continue;
          }
          $path = $dir . '/' . $item;
          if (is_dir($path)) {
            $files = array_merge($files, $this->findTwigFiles($path));
          } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'twig') {
            $files[] = $path;
          }
        }
        return $files;
      }

      /**
       * Finds all occurrences of a specific token in Twig templates.
       *
       * @param string $token
       * The token to search for (e.g., 'node.title').
       *
       * @return array
       * An array of results, where each result is an associative array
       * containing 'theme', 'file', and 'line'.
       */
      public function findTokenInTwig(string $token): array {
        $results = [];
        $activeTheme = $this->themeManager->getActiveTheme();
        $themePaths = [$activeTheme->getPath()];
        if ($baseThemes = $activeTheme->getBaseThemes()) {
          foreach ($baseThemes as $baseTheme) {
            $themePaths[] = $this->extensionDiscovery->getPath('theme', $baseTheme->getName());
          }
        }

        foreach ($themePaths as $themePath) {
          $twigFiles = $this->findTwigFiles($themePath . '/templates');
          foreach ($twigFiles as $file) {
            $lines = file($file);
            foreach ($lines as $lineNumber => $line) {
              if (strpos($line, '{{ ' . $token . ' }}') !== FALSE || strpos($line, '{{' . $token . '}}') !== FALSE) {
                $results[] = [
                  'theme' => $activeTheme->getName(),
                  'file' => $file,
                  'line' => $lineNumber + 1,
                ];
              }
            }
          }
        }
        return $results;
      }

    }
    ```

* **Usage:** Inject the `TokenTracker` service and call the `findTokenInTwig()` method.

**3. Analyzing PHP Code:**

* **Iterating Through Module and Theme Files:** Similar to Twig files, you'll need to get a list of all `.php` files in your custom modules and themes.
* **Reading File Contents:** Use `file_get_contents()`.
* **Regular Expressions or Token Service Usage:**
  * Search for direct usage of the `\Drupal::token()->replace()` method and identify the tokens being passed as arguments.
  * You might also need to look for variables that are being populated with token values.

**4. Creating a Drush Command:**

* To make this programmatic tracking easier to use, you can wrap the above logic within a custom Drush command. This allows you to run the token analysis from the command line.

    ```php
    <?php

    namespace Drupal\my_module\Commands;

    use Drupal\Core\Extension\ModuleExtensionList;
    use Drupal\my_module\Service\TokenTracker;
    use Drush\Commands\DrushCommands;

    /**
     * Drush commands for token tracking.
     */
    class TokenTrackerCommands extends DrushCommands {

      protected $tokenTracker;

      /**
       * Constructs a new TokenTrackerCommands object.
       *
       * @param \Drupal\my_module\Service\TokenTracker $tokenTracker
       * The token tracker service.
       */
      public function __construct(TokenTracker $tokenTracker) {
        $this->tokenTracker = $tokenTracker;
      }

      /**
       * Finds all occurrences of a specific token in Drupal.
       *
       * @param string $token
       * The token to search for (e.g., 'node:title').
       * @command my_module:find-token
       * @aliases mmt
       * @usage drush my_module:find-token 'user:name'
       */
      public function findToken(string $token): void {
        $this->output()->writeln('Searching for token: ' . $token);

        $configResults = $this->tokenTracker->findTokenInConfiguration($token);
        if (!empty($configResults)) {
          $this->output()->writeln("\nFound in Configuration:");
          $tableData = [];
          foreach ($configResults as $result) {
            $tableData[] = [$result['config'], $result['path'], $result['value']];
          }
          $this->output()->table(['Config', 'Path', 'Value'], $tableData);
        } else {
          $this->output()->writeln("\nNo occurrences found in configuration.");
        }

        $twigResults = $this->tokenTracker->findTokenInTwig($token);
        if (!empty($twigResults)) {
          $this->output()->writeln("\nFound in Twig Templates:");
          $tableData = [];
          foreach ($twigResults as $result) {
            $tableData[] = [$result['theme'], $result['file'], $result['line']];
          }
          $this->output()->table(['Theme', 'File', 'Line'], $tableData);
        } else {
          $this->output()->writeln("\nNo occurrences found in Twig templates.");
        }

        // Add similar logic for PHP code if needed.
      }

    }
    ```

**Key Considerations:**

* **Performance:** Searching through all configuration and code files can be resource-intensive on large sites. Consider optimizing your search or limiting the scope if necessary.
* **Token Syntax Variations:** Be mindful of different token syntaxes used in various contexts (e.g., `[token]` vs. `{{ token }}`). Your search logic might need to accommodate these variations.
* **Complexity:** Programmatic analysis, especially for PHP code, can be complex due to the dynamic nature of code execution.
* **Contributed Modules:** Before writing your own code, it's always worth checking if any contributed modules have emerged by **April 21, 2025**, that provide token management or analysis features.

By implementing these programmatic approaches, you can create a more automated and comprehensive way to track where specific tokens are being used throughout your Drupal site. Remember to adapt the code examples to your specific needs and token syntax variations.


Okay, you want to add the programmatic logic for finding token usage to your custom Color Entity in Drupal. That makes sense if you want to track where the *names* or *machine names* of your Color Entities are being used as tokens within your site.

Here's how you can approach this, keeping in mind that you'll likely be searching for the *machine name* of your Color Entity (which is what would typically be used as a token, e.g., `color:primary_blue`) rather than the color values themselves.

**1. Create a Method in Your Color Entity Class or a Related Service:**

You can either add a method directly to your custom Color Entity class (if you have one) or create a dedicated service that handles this token tracking logic, potentially taking the Color Entity as an argument. A service is generally a cleaner separation of concerns.

Let's assume you have a service called `ColorTokenTracker` for this.

```php
<?php

namespace Drupal\your_module\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Extension\ExtensionDiscoveryInterface;

class ColorTokenTracker {

  protected $configFactory;
  protected $entityTypeManager;
  protected $themeManager;
  protected $extensionDiscovery;

  public function __construct(
    ConfigFactoryInterface $configFactory,
    EntityTypeManagerInterface $entityTypeManager,
    ThemeManagerInterface $themeManager,
    ExtensionDiscoveryInterface $extensionDiscovery
  ) {
    $this->configFactory = $configFactory;
    $this->entityTypeManager = $entityTypeManager;
    $this->themeManager = $themeManager;
    $this->extensionDiscovery = $extensionDiscovery;
  }

  /**
   * Finds all occurrences of a Color Entity's machine name as a token.
   *
   * @param string $colorEntityMachineName
   * The machine name of the Color Entity (e.g., 'primary_blue').
   *
   * @return array
   * An array of results detailing where the token is used.
   */
  public function findColorTokenUsage(string $colorEntityMachineName): array {
    $tokenToFind = 'color:' . $colorEntityMachineName;
    $results = [];

    // Search in Configuration.
    $configResults = $this->findTokenInConfiguration($tokenToFind);
    $results['configuration'] = $configResults;

    // Search in Twig Templates.
    $twigResults = $this->findTokenInTwig($tokenToFind);
    $results['twig'] = $twigResults;

    // @todo: Add logic to search in PHP code if needed.

    return $results;
  }

  /**
   * Recursively searches an array for a specific token.
   * (Same implementation as in the previous example)
   */
  protected function findTokenInArray(array $data, string $token, string $configName, array &$results, string $keyPath = '') {
    // ... (your existing findTokenInArray logic) ...
  }

  /**
   * Finds all occurrences of a specific token in Drupal configuration.
   * (Same implementation as in the previous example)
   */
  protected function findTokenInConfiguration(string $token): array {
    // ... (your existing findTokenInConfiguration logic) ...
  }

  /**
   * Recursively finds all .html.twig files in a directory.
   * (Same implementation as in the previous example)
   */
  protected function findTwigFiles(string $dir): array {
    // ... (your existing findTwigFiles logic) ...
  }

  /**
   * Finds all occurrences of a specific token in Twig templates.
   * (Same implementation as in the previous example)
   */
  protected function findTokenInTwig(string $token): array {
    // ... (your existing findTokenInTwig logic) ...
  }

}
```

**2. Create a Way to Trigger This Tracking:**

You'll need a way to trigger the `findColorTokenUsage()` method for a specific Color Entity. Here are a few options:

* **A Method on the Color Entity Itself:** You could add a method like `getUsage()` to your Color Entity class. This method would then call the `ColorTokenTracker` service.

    ```php
    <?php

    namespace Drupal\your_module\Entity;

    use Drupal\Core\Config\Entity\ConfigEntityBase;
    use Drupal\your_module\Service\ColorTokenTrackerInterface; // Assuming you create an interface

    /**
     * Defines the Color entity.
     *
     * @ConfigEntityType(
     * id = "your_module_color",
     * label = @Translation("Color"),
     * ...
     * )
     */
    class Color extends ConfigEntityBase implements ColorInterface {

      // ... your existing entity properties and methods ...

      /**
       * Gets the usage of this color entity as a token.
       *
       * @param \Drupal\your_module\Service\ColorTokenTrackerInterface $tokenTracker
       * The color token tracker service.
       *
       * @return array
       * An array of results detailing where the token is used.
       */
      public function getUsage(ColorTokenTrackerInterface $tokenTracker): array {
        return $tokenTracker->findColorTokenUsage($this->id());
      }

    }
    ```

  You would then need to inject the `ColorTokenTrackerInterface` into your entity's dependencies (this is a bit more involved and usually done in a controller or service that interacts with the entity).

* **A Controller or Drush Command:** You could create a dedicated controller or Drush command that takes the Color Entity's machine name as an argument and then uses the `ColorTokenTracker` service to find its usage. This is often a more practical approach for administrative tasks.

  **Example Drush Command:**

    ```php
    <?php

    namespace Drupal\your_module\Commands;

    use Drupal\your_module\Service\ColorTokenTracker;
    use Drush\Commands\DrushCommands;

    /**
     * Drush commands for color token tracking.
     */
    class ColorTokenCommands extends DrushCommands {

      protected $colorTokenTracker;

      /**
       * Constructs a new ColorTokenCommands object.
       *
       * @param \Drupal\your_module\Service\ColorTokenTracker $colorTokenTracker
       * The color token tracker service.
       */
      public function __construct(ColorTokenTracker $colorTokenTracker) {
        $this->colorTokenTracker = $colorTokenTracker;
      }

      /**
       * Finds all occurrences of a Color Entity's machine name as a token.
       *
       * @param string $colorMachineName
       * The machine name of the Color Entity to search for (e.g., 'primary_blue').
       * @command your_module:find-color-token-usage
       * @aliases ymfctu
       * @usage drush your_module:find-color-token-usage 'primary_blue'
       */
      public function findColorTokenUsageCommand(string $colorMachineName): void {
        $this->output()->writeln('Searching for token usage of color: color:' . $colorMachineName);
        $results = $this->colorTokenTracker->findColorTokenUsage($colorMachineName);

        if (!empty($results['configuration'])) {
          $this->output()->writeln("\nFound in Configuration:");
          $tableData = [];
          foreach ($results['configuration'] as $result) {
            $tableData[] = [$result['config'], $result['path'], $result['value']];
          }
          $this->output()->table(['Config', 'Path', 'Value'], $tableData);
        } else {
          $this->output()->writeln("\nNo occurrences found in configuration.");
        }

        if (!empty($results['twig'])) {
          $this->output()->writeln("\nFound in Twig Templates:");
          $tableData = [];
          foreach ($results['twig'] as $result) {
            $tableData[] = [$result['theme'], $result['file'], $result['line']];
          }
          $this->output()->table(['Theme', 'File', 'Line'], $tableData);
        } else {
          $this->output()->writeln("\nNo occurrences found in Twig templates.");
        }
      }

    }
    ```

* **A UI Element on the Color Entity Edit Form:** You could add a tab or a button to your Color Entity's edit form that, when clicked, triggers the token usage search and displays the results. This would involve creating a custom form alter or a custom form element and using the `ColorTokenTracker` service.

**3. Register the `ColorTokenTracker` Service:**

Make sure you register your `ColorTokenTracker` service in your module's `your_module.services.yml` file:

```yaml
services:
  your_module.color_token_tracker:
    class: Drupal\your_module\Service\ColorTokenTracker
    arguments: ['@config.factory', '@entity_type.manager', '@theme.manager', '@extension.discovery.system']
```

**Choosing the Right Approach:**

* For administrative tasks and on-demand checks, a **Drush command** is often the most convenient.
* If you need to display token usage information directly when viewing or editing a Color Entity in the Drupal UI, a **UI element on the edit form** would be appropriate.
* Adding a `getUsage()` method to the entity itself can be useful if other parts of your code need to programmatically determine where a color is being used.

Remember to adapt the code examples with your module's namespace and the actual machine name of your Color Entity type. This will allow you to programmatically track where your Color Entities are being used as tokens within your Drupal site.
