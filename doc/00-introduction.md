# Letterpress - A formatting library

## Introduction

Letterpress is a formatting library for PHP. It can help you transform various 
kinds of input into semantically correct HTML. Additionally, it will enhance 
text content typography. Albeit being perfectly able to just enhance already
existing HTML, the main use case for Letterpress is transforming Markdown 
input into a presentable state with the least amount of manual labour. Thus, 
with the default configuration, this is all the usage code needed:

```php
$letterpress = new Letterpress();
$html = $letterpress->press($markdown);
```

The following manual sections will dive deeper into the different areas of
transforming and enhancing input:

- Transforming Markdown to HTML
- Fixing and enhancing HTML semantics
- Fixing and enhancing content typography
- Enhancing and unifying rich media references
