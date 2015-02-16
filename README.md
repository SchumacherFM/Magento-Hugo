Magento-Hugo
============

WIP = Work in Progress and cancelled.

Magento Hugo generates JSON streams for [Hugo](http://gohugo.io) - Static site generator #golang.

This module depends on this [Hugo version](https://github.com/SchumacherFM/hugo/tree/SchumacherFM_SourceJSON).
You must compile Hugo yourself to get it working. If you ask me nicely ;-) I'll can
send you binaries for any OS.

This is pretty time consuming module and I'm not sure if it's worth to continue the 
work because you can easily implement a full page cache in Magento which does the same as
generating static sites with Hugo. 

But if you want to host your Magento site on an AWS S3 instance or Github pages then this 
would be the perfect solution. You'll only need an e.g. Angular JS ap integrated which 
handles the cart, checkout, etc to the real Magento store.

Or if you plan to use Magento without the cart features and use it only as a data provider then
Hugo would be perfect for that task.

#### Todo Magento module

- Implementation of category controller to generate the menu from the Magento categories
- FrontMatter model needs an update on the menu integration
- All phtml files in the template folder must be converted to markdown

#### Todo Hugo

- A nice template for Magento especially to handle recursively the categories.

#### Heads up

The JSON stream for product view pages is already working correctly so you can try it out.

With the original Magento demo data Hugo will fail to render the pages because some menu keys
can't be found.

Downloading the JSON stream takes longer than Hugo needs for generating the pages 
(on my MacBook working with localhost)

Compatibility
-------------

- Magento >= 1.5
- php >= 5.2.0

For Magento2 please use [https://github.com/SchumacherFM/Magento2-Hugo](https://github.com/SchumacherFM/Magento2-Hugo) @todo

Support / Contribution
----------------------

Report a bug using the issue tracker or send us a pull request.

Instead of forking I can add you as a Collaborator IF you really intend to develop on this module. Just ask :-)

I am using that model: [A successful Git branching model](http://nvie.com/posts/a-successful-git-branching-model/)

For versioning have a look at [Semantic Versioning 2.0.0](http://semver.org/)

History
-------

#### 0.1.0

- Initial release

License
-------

[The Open Software License 3.0 (OSL-3.0)](http://opensource.org/licenses/osl-3.0.php)

Author
------

[Cyrill Schumacher](http://cyrillschumacher.com)

[My pgp public key](http://www.schumacher.fm/cyrill.asc)
