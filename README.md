# salusperaquam

![PHP tests](https://github.com/flavioski/salusperaquam/workflows/PHP%20tests/badge.svg)
![NodeJS tests](https://github.com/flavioski/salusperaquam/workflows/NodeJS%20tests/badge.svg)
![JS tests](https://github.com/flavioski/salusperaquam/workflows/JS%20tests/badge.svg)
![Build & Release draft](https://github.com/flavioski/salusperaquam/workflows/Build%20&%20Release%20draft/badge.svg)
![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/flavioski/salusperaquam)
[![GitHub license](https://img.shields.io/github/license/flavioski/salusperaquam)](https://github.com/flavioski/salusperaquam/LICENSE.md)

## About
Salus Per Aquam - Thermae center's plugin for PrestaShop e-commerce solution.

## About

Warning! This is for Academic purpose. Do not put into production

## Reporting issues

In order to contact me, send an email. Thank you.

## Building the module


### Direct download

If you want to get a zip ready to install on your shop. You can directly download it by clicking [here][direct-download].


### Production

1. Clone this repo `git clone git@github.com:flavioski/salusperaquam.git`
2. `make build-prod-zip`

The zip will be generated in the root directory of the module.

### Development

1. Clone this repo
2. `make docker-build`
3. `make watch-front`

I also recommend you to install the [vuejs-devtools][vuejs-devtools].

#### Switch on sandbox (Advanced) *Experimental*


## Contributing

This module is open source extensions for the PrestaShop e-commerce solution. Everyone is welcome and even encouraged to contribute with their own improvements.

### Requirements

Contributors **must** follow the following rules:

* **Make your Pull Request on the "dev" branch**, NOT the "master" branch.
* Do not update the module's version number.
* Follow [the coding standards][1].

### Process in details

Contributors wishing to edit a module's files should follow the following process:

1. Create your GitHub account, if you do not have one already.
2. Fork this project to your GitHub account.
3. Clone your fork to your local machine in the ```/modules``` directory of your PrestaShop installation.
4. Create a branch in your local clone of the module for your changes.
5. Change the files in your branch. Be sure to follow the [coding standards][1]!
6. Push your changed branch to your fork in your GitHub account.
7. Create a pull request for your changes **on the _'dev'_ branch** of the module's project. Be sure to follow the [contribution guidelines][2] in your pull request. If you need help to make a pull request, read the [GitHub help page about creating pull requests][3].
8. Wait for one of the core developers either to include your change in the codebase, or to comment on possible improvements you should make to your code.

That's it: you have contributed to this open source project! Congratulations!

## License

This module is released under the [MIT License][MIT]


[vuejs]: https://vuejs.org/
[vuejs-devtools]: https://github.com/vuejs/vue-devtools
[direct-download]: https://github.com/flavioski/salusperaquam/releases/latest/download/salusperaquam.zip
[1]: https://devdocs.prestashop.com/1.7/development/coding-standards/
[2]: https://devdocs.prestashop.com/1.7/contribute/contribution-guidelines/
[3]: https://help.github.com/articles/using-pull-requests
[AFL-3.0]: https://opensource.org/licenses/AFL-3.0
[MIT]: https://opensource.org/licenses/MIT
