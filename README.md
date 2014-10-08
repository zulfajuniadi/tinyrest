#TinyRest
---

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
        <th>Response</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>GET</td>
        <td>/projects</td>
        <td>Get list of all projects</td>
        <td>
        {"response":[{"title":"Uber Project","id":"5434319cb4b5a"}, {...}, {...}],"error":false,"status":200}
        </td>
    </tr>
    <tr>
        <td>GET</td>
        <td>/projects/:id</td>
        <td>Get detail of a project</td>
        <td>
        {"response":{"title":"Uber Project","id":"5434319cb4b5a"},"error":false,"status":200}
        </td>
    </tr>
    <tr>
        <td>POST</td>
        <td>/projects</td>
        <td>Create new project</td>
        <td>
        {"response":{"title":"Uber Project","id":"5434319cb4b5a"},"error":false,"status":201}
        </td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>/projects/:id</td>
        <td>Update a project detail</td>
        <td>
        {"response":{"title":"Uber Project","id":"5434319cb4b5a"},"error":false,"status":200}
        </td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>/projects/:id</td>
        <td>Delete a project</td>
        <td>
        {"response":{"title":"Uber Project","id":"5434319cb4b5a"},"error":false,"status":200}
        </td>
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
        <th>Response</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>GET</td>
        <td>/todos</td>
        <td>Get list of all todos</td>
        <td>
        {"response":[{"title":"Buy some milk","id":"5434319cbd234"}, {...}, {...}],"error":false,"status":200}
        </td>
    </tr>
    <tr>
        <td>GET</td>
        <td>/todos/:id</td>
        <td>Get detail of a todo</td>
        <td>
        {"response":{"title":"Buy some milk","id":"5434319cbd234"},"error":false,"status":200}
        </td>
    </tr>
    <tr>
        <td>POST</td>
        <td>/todos</td>
        <td>Create new todo</td>
        <td>
        {"response":{"title":"Buy some milk","id":"5434319cbd234"},"error":false,"status":201}
        </td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>/todos/:id</td>
        <td>Update a todo detail</td>
        <td>
        {"response":{"title":"Buy some milk","id":"5434319cbd234"},"error":false,"status":200}
        </td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>/todos/:id</td>
        <td>Delete a todo</td>
        <td>
        {"response":{"title":"Buy some milk","id":"5434319cbd234"},"error":false,"status":200}
        </td>
    </tr>
</tbody>
</table>


###Examples

View the index.php file in the examples folder.

###Event Listeners

TinyREST exposes the ``on`` method that enables you to bind to events on an endpoint. Let's say I were to log all data created on the projects, I would do so like:

```php
    $TinyRest = new Tinyrest\Handle(['projects', 'todos'], '../data/');
    $projects = $TinyRest->router('projects');
    
    // Do something when a new project is created
    $listener_id = $projects->on('create', function($new_data){
        // Log $new_data creation
    });
    
    // To stop listening to the event
    $projects->off('create', $listener_id);
```

Events fired:

<table>
<thead>
    <tr>
        <th>Event</th>
        <th>Closure Arguments</th>
        <th>Description</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td><b>create</b></td>
        <td>
            <ol start="0">
                <li>$new_data</li>
            </ol>
        </td>
        <td>Fired every time a new record is created</td>
    </tr>
    <tr>
        <td><b>update</b></td>
        <td>
            <ol start="0">
                <li>$new_data</li>
                <li>$old_data</li>
            </ol>
        </td>
        <td>Fired every time a record is updated</td>
    </tr>
    <tr>
        <td><b>delete</b></td>
        <td>
            <ol start="0">
                <li>$old_data</li>
            </ol>
        </td>
        <td>Fired every time a record is deleted</td>
    </tr>
</tbody>
</table>


###Data Persistance

TinyREST stores each endpoint data inside it's own .json file in the public directory. To change the directory, provide a second argument to point to your preferred data directory as per example below:

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

**ISC**

Copyright (c) 2014, Zulfa Juniadi bin Zulkifli

Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.

