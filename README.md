# CRUD

CRUD builder for phpframework.

```php
$crud = Crud::create()->table('news')->url('/admin')->title('News');
$crud->fields(
    (new Field('title', 'Title'))->required(),
    (new ReferenceField('user', 'User'))->table('artist'),
    (new Field('content', 'Description'))->required()->textarea(),
    (new DateField('publish_date', 'Publish date'))->required()
);
$crud->listFields('title', 'artist', 'publish_date')->listOrder('publish_date DESC');

$html = $crud->render();
```
