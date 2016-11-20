# ya-todo-app - Yet another ToDo app

[![Software License][ico-license]](LICENSE.md)

This is a personal project with the goal of building an web-application that allows multiple users to securely store their tasks.

I am very well aware that their are dozens of Todo-apps around, the focus on this one is saving the messages on a server, to allow multiple end-devices show the same tasks, but it should be impossible for the server owner to read messages of other users.

Also, I want to use this as a project to get some practise with [vue.js](http://vuejs.org) frontends.

## Features

* On Register, a random **userkey** gets generated and stored in the User model, **encrypted with a PBKDF2 derivation of the user's password**
* The password is of course only saved as a bcrypt-hash
* On Login, the key gets decrypted and stored in a **encrypted cookie on the user's browser**
* All private data (like tasks) will only be saved encrypted with the **userkey** provided in the cookie (it is never stored in a way the server could use it without the user)
* Two-factor authentication using TOTP

## Install

``` bash
$ composer install (--no-dev -o)
$ cp .env.example .env
$ ./artisan key:generate
```
Adjust *.env* to your environment. Especially, set `JWT_SECRET` to a random string.
``` bash
$ ./artisan migrate
```

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-contributors]: ../../contributors
