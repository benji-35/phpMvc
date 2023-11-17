# phpMvc

## How to create my page ?

### Create a Controller
Controller allow you to add some url path to your website.
<br>
those url are defined by functions.
```php
namespace App\Controller;

class MyController extends BaseController {
    function myUrl() {
    
    }
}
```
In this example, myUrl function will be used to host 1 url path.

### Configure url paths
You have a config file in `` app/config/Routes.json ``
<br>this file contains 2 important part : 
- controllers :
<br>
List all your controllers. We gonna add ``MyController``
````json
{
  "controllers": {
    "MyController": {
      "namespace": "App\\Controller",
      "location": "myController/MyController.php"
    }
  }
}
````
- urls :<br>
You set all urls you want here. We gonna set the url ``<myHost>/myCustomUrl`` to our function ``myUrl``.
````json
{
  "urls": {
    "/myCustomUrl": {
      "GET": {
        "controller": "MyController",
        "function": "myUrl"
      }
    }
  }
}
````

### Create your view
Now your website is configured and your controllers set. Let's create what your users have to see.
<br>
All webpages are stocked in ``app/view/<Its Controller>/<Function>.php``.
<br>
<br>
In our example the path must be : ``app/view/MyController/myUrl.php``.
<br>
<br>
<br>
Now you know how to add your pages !
