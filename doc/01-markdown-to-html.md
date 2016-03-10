## Transforming Markdown to HTML

The `press()`-method introduced in the previous chapter internally calls up to
three different workers for the various parts of Letterpress' functionality.
The first of these workers is usually the - also externally callable `markdown()`-method. Called externally, usage would be along the lines of this:

```php
$letterpress = new Letterpress();

$html = $letterpress->markdown($markdown);
```

Behind the scenes, Letterpress leverages Parsedown for markdown processing. 
All parsedown configuration options are accessable through the system 
configuration. The configuration keys concerning markdown transformation are
stored under the `letterpress.markdown`-category, thus, to obtain the fully 
qualified name of one of the configuration options listed below, that category 
name needs to be prepended.

### List of configuration options

- `enable` (**true**): This is the global on-off-switch for markdown processing, however, it can be overwritten when forcing processing (see etailed Usage) for details.

- `useMarkdownExtra` (**false**):
- `enableLineBreaks` (**true**):
- `escapeMarkup` (**false**):

