# Introduction

XenForo-CLI (XfCli) is a command line interface for XenForo. It was created to alleviate the need
for developers to interact with the XenForo Admin Panel or mess around in their file manager while
developing their addons. 

## Installation

<!-- TODO: change to point at upstream if PRing -->

**Clone the repository and its submodules**

`git clone --recursive git@github.com:lol768/XenForo-CLI.git`

Or use `git submodule update --init --recursive` if you already cloned it and forgot to use --recursive.

**Enter the newly made directory**

`cd XenForo-CLI`

**Add repository directory to PATH environmental variable**

Temporarily: `export PATH=$PATH:\`pwd\``

Permanently: Edit .bashrc or similar and add `export PATH=$PATH:/directory/to/repo/here`

**Enter XenForo root directory**

`cd /var/www/XenForo/`

**Use the xf command**

`xf`

## Why?

While XenForo provides a very useful user interface for managing many of the processes involved in
addon development, it has the unfortunate side effect of drawing you out of "the zone" when
developing addons. Not only that, simple things like extending a controller can cost you around 2
minutes to set up. This may not sound like much, but during this time you are pulled entirely out of
the flow you were in while developing an addon. If instead you could do this in 2 seconds, without
even leaving your IDE, this would seriously improve productivity and creativity. Not only because
you just saved yourself 128 seconds, but also because you can continue to work in your "zone", which
arguably costs far more time to get back into.

# Project Status

Currently XfCli is still in heavy development and should be considered an early Alpha. It already
has many working features, but they are all subject to change.

## Current Features

Currently supported commands are:

Addons

 * addon
 * addon add
 * addon import
 * addon install
 * addon list
 * addon select
 * addon show
 * addon uninstall

Code Events

 * extend
 * extend add
 * extend delete
 * listener add
 * listener delete

Phrases

 * phrase add
 * phrase find
 * phrase get

Templates

 * template add

Routes

 * route add

## Todo

 * addon export
 * listener delete
 * extend delete
 * phrase delete
 * phrase update
 * template find
 * template get
 * route find
 * route get

# Developers

XenForo-CLI and it's parent project [PHP-CLI](https://github.com/Naatan/PHP-CLI) are developed and
maintained by [Nathan Rijksen](https://github.com/Naatan) and
[Robert Clancy](https://github.com/Robbo-), it being an open-source project however anyone may feel
free to contribute - in fact we encourage you to do so! This project is entirely for developers,
by developers.

# Contributing

Besides forking and making pull requests you can contribute to this project by reporting bugs and
joining us in our discussions regarding development, for this purpose we have set up a
[google group](https://groups.google.com/forum/#!forum/xenforo-cli). Feel free to stop by and share
your opinion.
