#TinyRest

###What is it?

A quick and dirty way to get a php REST backend running with two lines of code. Not to be used on production. I use it mainly to test client side javascript / mobile apps during development.

###Installation

```bash
composer require zulfajuniadi/tinyrest:0.*
```

###Usage

Let's say my little app has two restful endpoints:
- /projects
- /tasks

By instantiating TinyREST like so: 
```php
new Tinyrest\Handle(['projects', 'todos']);
```
I now have a fully functional backend for my app that handles these routes:

Projects Routes:

<table>
<thead>
    <tr>
        <th>Method</th>
        <th>Edpoint</th>
        <th>Description</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>GET</td>
        <td>/projects</td>
        <td>Get list of all projects</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>/projects/:id</td>
        <td>Get detail of a project</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>/projects</td>
        <td>Create new project</td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>/projects/:id</td>
        <td>Update a project detail</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>/projects/:id</td>
        <td>Delete a project</td>
    </tr>
</tbody>
</table>

Todos Routes:

<table>
<thead>
    <tr>
        <th>Method</th>
        <th>Edpoint</th>
        <th>Description</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>GET</td>
        <td>/todos</td>
        <td>Get list of all todos</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>/todos/:id</td>
        <td>Get detail of a todo</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>/todos</td>
        <td>Create new todo</td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>/todos/:id</td>
        <td>Update a todo detail</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>/todos/:id</td>
        <td>Delete a todo</td>
    </tr>
</tbody>
</table>

###Examples

View the index.php file in the examples folder.

###Data Persistance

It stores each endpoint's data inside it's own .json file in your public directory. To change the directory, provide a second argument to point to your preferred data directory as per example below:

```php
new Tinyrest\Handle(['projects', 'todos'], '../data/');
```

###Contributions Welcome

You contributions are very much appreciated mainly in these areas:
- Providing unit tests
- Bug reporting and fixing
- Documentations

Please fork this repository and create a pull request for it to be merged.

###License

ISC

