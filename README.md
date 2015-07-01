Titanium
========

Titanium is an open-source php scaffold. I know there are a bunch of different ones out there but I don't like any of those. So here is my own. You can add and subtract any of the different modules in `modules/`. Look in the `app/` directory for an example of how your app would function. It's pretty cool. There are funny puns in the naming of classes and it is very easy to use and get started with.

## UNDER CONSTRUCTION!!
This framework is very much still in development but you can watch its progress here and contribute if you would like.

## Getting started
There are three parts to a Titanium application. The "Core" where all the standard library is kept, the "Modules" which are little bits of reusable code, and the "Application" which is where you write all of your custom application specific code.

### Core
At the heart of Titanium is its core. It's the only thing you need to get started. The core only has a few features, but it is all you need to get started. It does error handling, autoloading, logging, and has a module system. The modules is where all the fun happens.

### Modules
Modules are little bits of reusable code that work with Titanium. There is a module for networking, there a module for working with a database and many, many, more. You simply declare which modules you want to use in your application and then you can use the module anywhere in your application. You can also write your own modules to then use in your application.

### Your Application
Titanium is very flexible. It only requires you have one file in your application and the rest is up to you. That one file is `runway.php`. The runway is a file that starts up your application...before it can take off your application has to hit the runway. You can see an example of a runway file in the `app/` directory of this repository.
