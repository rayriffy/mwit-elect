MWITS Election System
=====================

Requirements
------------

- PHP 7.1.3+ with r[equired module for Laravel](https://laravel.com/docs/5.6#server-requirements)
- MariaDB 10.3.9+
- `composer`
- `npm` or `yarn`

Installation
------------

01. Clone repository

    ```markdown
    $ git clone https://github.com/rayriffy/hacktech-backend
    Cloning into 'hacktech-backend'...
    remote: Counting objects: 322, done.
    remote: Compressing objects: 100% (183/183), done.
    Receiving objects: remote: Total 322 (delta 143), reused 280 (delta 112), pack-reused 0
    Receiving objects: 100% (322/322), 225.04 KiB | 268.00 KiB/s, done.
    Resolving deltas: 100% (143/143), done.
    ```

02. Install composer package

    ```markdown
    $ composer install
    Loading composer repositories with package information
    Installing dependencies (including require-dev) from lock file
    Package operations: 70 installs, 0 updates, 0 removals
    ...
    ```

03. Install NPM package

    ```markdown
    $ yarn
    yarn install v1.9.4
    [1/4] Resolving packages...
    [2/4] Fetching packages...
    info fsevents@1.2.4: The platform "win32" is incompatible with this module.
    info "fsevents@1.2.4" is an optional dependency and failed compatibility check. Excluding it from installation.
    [3/4] Linking dependencies...
    warning "laravel-mix > img-loader@3.0.0" has unmet peer dependency "imagemin@^5.0.0".
    [4/4] Building fresh packages...
    Done in 76.59s.
    ```

04. Generate CSS/JS resources

    ```markdown
    $ yarn run prod
    yarn run v1.9.4
    npm run production

    > @ production C:\xampp\htdocs\mwitelect.rayriffy.com
    > cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js

    DONE  Compiled successfully in 18827ms

     Asset    Size  Chunks             Chunk Names
      /js/app.js  242 kB       0  [emitted]  /js/app
    /css/app.css  158 kB       0  [emitted]  /js/app
    Done in 29.76s.
    ```.

Routes
------

| Domain | Method   | URI                                        | Name                     | Action  | Middleware               |
|--------|----------|--------------------------------------------|--------------------------|---------|--------------------------|
|        | GET|HEAD | /                                          | home                     | Closure | web                      |
|        | GET|HEAD | admin                                      | admin.home               | Closure | web,checkauth,checkadmin |
|        | GET|HEAD | admin/addcandidate/{elect}                 | admin.candidate.add.page | Closure | web,checkauth,checkadmin |
|        | GET|HEAD | admin/addelect                             | admin.elect.add.page     | Closure | web,checkauth,checkadmin |
|        | POST     | admin/candidate                            | admin.candidate.add      | Closure | web,checkauth,checkadmin |
|        | DELETE   | admin/candidate/delete/{elect}/{candidate} | admin.candidate.delete   | Closure | web,checkauth,checkadmin |
|        | PUT      | admin/candidate/edit/{elect}/{candidate}   | admin.candidate.edit.sys | Closure | web,checkauth,checkadmin |
|        | POST     | admin/elect                                | admin.elect.add          | Closure | web,checkauth,checkadmin |
|        | DELETE   | admin/elect/delete/{elect}                 | admin.elect.delete       | Closure | web,checkauth,checkadmin |
|        | PUT      | admin/elect/edit/{elect}                   | admin.elect.edit.sys     | Closure | web,checkauth,checkadmin |
|        | GET|HEAD | admin/elect/edit/{elect}                   | admin.elect.edit.page    | Closure | web,checkauth,checkadmin |
|        | GET|HEAD | admin/elect/{elect}                        | admin.elect.show         | Closure | web,checkauth,checkadmin |
|        | GET|HEAD | admin/elects                               | admin.elect.all          | Closure | web,checkauth,checkadmin |
|        | POST     | auth                                       | auth                     | Closure | web                      |
|        | GET|HEAD | logout                                     | logout                   | Closure | web                      |
|        | GET|HEAD | user/elect                                 | user.elect               | Closure | web,checkauth            |
|        | POST     | user/vote                                  | user.vote.sys            | Closure | web,checkauth            |
|        | GET|HEAD | user/vote/{elect}                          | user.vote.page           | Closure | web,checkauth            |
|        | GET|HEAD | {fallbackPlaceholder}                      |                          | Closure | web                      |
