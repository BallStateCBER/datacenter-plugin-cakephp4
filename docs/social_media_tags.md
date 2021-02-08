# Social media meta tags

This plugin sets some default Open Graph meta tags in the `og_meta_tags` element, and some values can be changed by
editing the plugin's configuration and setting view variables.

## Configuration
- Enter a general description of the website in `DataCenter.openGraphDescription` to be used in the absence of a
  page-specific description
- In `config/datacenter.php`, the `DataCenter.defaultOpenGraphLogoPath` should be set with a value such as
  `'https://subdomain.cberdata.org/img/logo/og_logo.png'`
- If a different default image is desired for Twitter, set that path as `DataCenter.defaultTwitterLogoPath`
- If a Facebook app is associated with this website, set the value `DataCenter.facebookAppId`
- If this website is associated with a different Twitter handle than @BallStateCBER, set `DataCenter.twitterUsername`

## View variables
- Each page that wants to set page-specific Open Graph meta tags should pass an `$ogMetaTags` array to the view, with
  keys corresponding to the "property" attribute of the tag and values being strings or arrays of strings.
- Array values will be processed as multiple tags
- Any values will be translated into meta tags, but these tags are suggested:
  ```php
  $this->set('ogMetaTags', [
      'og:description' => $article->body,
      'og:image' => [
          Router::url("/img/articles/{$article->id}/main.png", true),
          Router::url("/img/articles/{$article->id}/1.png", true),
          Router::url("/img/articles/{$article->id}/2.png", true),
      ],
      'twitter:image' => Router::url("/img/articles/{$article->id}/main.square.png", true),

      // Specify canonical URL as a string if this page can be accessed via multiple URLs
      'og:url' => Router::url([...], true),

      // If this is an article-like page
      'og:type' => 'article',
  ]);
  ```
- `$pageTitle` and `DataCenter.siteTitle` are automatically integrated into the default meta tags
- `og:description` will be automatically truncated
- Note that all URLs must be full URLs

## Image dimensions
As of January 2020, (Facebook recommends)[https://developers.facebook.com/docs/sharing/webmasters/images/] that images
be
 - As close to 1.91:1 aspect ratio as possible
 - At least 1200 x 630 for the best display on high-resolution devices
 - Not more than 8 MB
