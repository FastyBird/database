# Quick start

The purpose of this extension is to create application events listeners which will watch [Doctrine2](https://www.doctrine-project.org/) database connection and in case connection is closed by server, it will try to reestablish it.

And second function of this extension is to create middleware for handling paginated responses

## Installation

The best way to install **fastybird/database** is using [Composer](http://getcomposer.org/):

```sh
composer require fastybird/database
```

After that, you have to register extension in *config.neon*.

```neon
extensions:
    fbDatabase: FastyBird\Database\DI\DatabaseExtension
```

This extension is dependent on other extensions, and they have to be registered too

```neon
extensions:
    ....
    fbWebServer: FastyBird\WebServer\DI\WebServerExtension
```

> For information how to configure these extensions please visit their doc pages

## Use pagination for response result

There are two conditions which you have to accomplish.

First one, in request attributes have to be present parameters for defining page offset and limit:

```json
{
    "page" : {
        "offset" : number, # zero based attribute
        "limit": number,
    }
}
```

eg.: `http://your.app.com/view/articles?page[offset]=10&page[limit]=50`

Middleware will set offset to result set **offset** to **10** and a **limit** to **50**

The second condition is, response have to made with result set via [ipub/doctrine-orm-query](https://github.com/ipublikuj/doctrine-orm-query) package.

```php
namespace Your\CoolApp\Controllers;

use FastyBird\WebServer\Http;
use Psr\Http\Message;

use Your\CoolApp\Models;

class ArticlesController
{

    private Models\ArticlesRepository $articlesRepository;

    public function readAll(
		Message\ServerRequestInterface $request,
		Http\Response $response
	): Http\Response {
        $find = new FindArticleQuery();
        $find->onlyVisible();
        
        $result = $find->fetch($this->articlesRepository);
        
        return $response
            ->withEntity(Http\ScalarEntity::from($result));
    }

}
```

And that's all. Middleware will take care of your provided result set and modify response to return only requested dataset.

***
Homepage [https://www.fastybird.com](https://www.fastybird.com) and repository [https://github.com/FastyBird/database](https://github.com/FastyBird/database).
